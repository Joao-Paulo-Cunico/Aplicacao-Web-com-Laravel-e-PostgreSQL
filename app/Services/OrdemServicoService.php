<?php

namespace App\Services;

use App\Models\Mecanico;
use App\Models\OrdemServico;
use App\Models\Servico;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrdemServicoService
{
    //Nomes centralizados para facilitar o alinhamento com os scripts SQL do grupo. */
    private const FUNCAO_CALCULAR_TOTAL = 'oficina_mecanica.fn_calcular_total_os';

    private const PROCEDURE_FINALIZAR = 'oficina_mecanica.sp_finalizar_os';

    //A trigger baixa o estoque; o cancelamento precisa repor a quantidade. */
    private const DEVOLVER_ESTOQUE_NO_CANCELAMENTO = true;

    private const TRANSICOES = [
        OrdemServico::STATUS_EM_ANDAMENTO => [
            OrdemServico::STATUS_FINALIZADA,
            OrdemServico::STATUS_CANCELADA,
        ],
        OrdemServico::STATUS_FINALIZADA => [],
        OrdemServico::STATUS_CANCELADA => [],
    ];

    // Abre uma nova OS. Toda OS nasce "Em andamento".

    public function abrir(array $dados): OrdemServico
    {
        $mecanico = Mecanico::find($dados['mecanico_id']);

        if (! $mecanico || ! $mecanico->ativo) {
            throw ValidationException::withMessages([
                'mecanico_id' => 'O mecânico informado não está ativo.',
            ]);
        }

        return OrdemServico::create([
            'veiculo_id' => $dados['veiculo_id'],
            'mecanico_id' => $dados['mecanico_id'],
            'data_abertura' => $dados['data_abertura'] ?? now()->toDateString(),
            'status' => OrdemServico::STATUS_EM_ANDAMENTO,
            'quilometragem_entrada' => $dados['quilometragem_entrada'],
            'observacoes' => $dados['observacoes'] ?? null,
            'valor_mao_obra' => $dados['valor_mao_obra'] ?? 0,
        ]);
    }


    public function calcularTotais(OrdemServico $ordem): array
    {
        $totalServicos = $this->somarItens('oficina_mecanica.ordem_servico_servico', $ordem->id_ordem_servico);
        $totalPecas = $this->somarItens('oficina_mecanica.ordem_servico_peca', $ordem->id_ordem_servico);
        $maoDeObra = round((float) $ordem->valor_mao_obra, 2);

        return [
            'mao_obra' => $maoDeObra,
            'servicos' => $totalServicos,
            'pecas' => $totalPecas,
            'total' => round($maoDeObra + $totalServicos + $totalPecas, 2),
        ];
    }

    //Calcula o total pela function PostgreSQL para demonstrar a integração SQL. */
    public function calcularTotalViaFuncao(OrdemServico $ordem): float
    {
        $resultado = DB::select(
            'select ' . self::FUNCAO_CALCULAR_TOTAL . '(?) as total',
            [$ordem->id_ordem_servico]
        );

        return round((float) ($resultado[0]->total ?? 0), 2);
    }

    public function podeTransitar(string $de, string $para): bool
    {
        return in_array($para, self::TRANSICOES[$de] ?? [], true);
    }


    // Lança erro de validação se a mudança de status não for permitida.

    public function validarTransicao(OrdemServico $ordem, string $novoStatus): void
    {
        if (! $this->podeTransitar($ordem->status, $novoStatus)) {
            throw ValidationException::withMessages([
                'status' => "Não é possível mudar a OS de \"{$ordem->status}\" para \"{$novoStatus}\".",
            ]);
        }
    }


    // Usada antes de incluir serviços/peças: só OS em andamento pode ser alterada.

    public function garantirEmAndamento(OrdemServico $ordem): void
    {
        if ($ordem->status !== OrdemServico::STATUS_EM_ANDAMENTO) {
            throw ValidationException::withMessages([
                'status' => 'Esta ordem de serviço não está em andamento e não pode ser alterada.',
            ]);
        }
    }

    //Finaliza a OS pela procedure PostgreSQL, após bloquear a linha na transação. */
    public function fechar(OrdemServico $ordem): void
    {
        try {
            DB::transaction(function () use ($ordem) {
                $ordem = OrdemServico::lockForUpdate()->findOrFail($ordem->id_ordem_servico);
                $this->validarTransicao($ordem, OrdemServico::STATUS_FINALIZADA);

                if ($ordem->data_abertura->isAfter(today())) {
                    throw ValidationException::withMessages([
                        'data_fechamento' => 'A data de fechamento não pode ser anterior à data de abertura.',
                    ]);
                }

                DB::statement('CALL ' . self::PROCEDURE_FINALIZAR . '(?)', [$ordem->id_ordem_servico]);
            });
        } catch (QueryException $exception) {
            throw ValidationException::withMessages([
                'status' => $this->mensagemDoBanco($exception),
            ]);
        }
    }

    //Cancela a OS e repõe suas peças na mesma transação. */
    public function cancelar(OrdemServico $ordem): void
    {
        DB::transaction(function () use ($ordem) {
            $ordem = OrdemServico::lockForUpdate()->findOrFail($ordem->id_ordem_servico);
            $this->validarTransicao($ordem, OrdemServico::STATUS_CANCELADA);

            if ($ordem->data_abertura->isAfter(today())) {
                throw ValidationException::withMessages([
                    'data_fechamento' => 'A data de cancelamento não pode ser anterior à data de abertura.',
                ]);
            }

            if (self::DEVOLVER_ESTOQUE_NO_CANCELAMENTO) {
                $pecas = DB::table('oficina_mecanica.ordem_servico_peca')
                    ->where('ordem_servico_id', $ordem->id_ordem_servico)
                    ->get(['peca_id', 'quantidade']);

                foreach ($pecas as $peca) {
                    DB::table('oficina_mecanica.peca')
                        ->where('id_peca', $peca->peca_id)
                        ->update(['estoque' => DB::raw('estoque + ' . (int) $peca->quantidade)]);
                }
            }

            $ordem->update([
                'status' => OrdemServico::STATUS_CANCELADA,
                'data_fechamento' => today()->toDateString(),
            ]);
        });
    }


    //Inclui um serviço na OS. Se já existir na OS, soma quantidade e desconto.
    //Tudo numa transação: se algo falhar, nada é gravado.

    public function adicionarServico(OrdemServico $ordem, int $servicoId, int $quantidade, float $desconto = 0): void
    {
        DB::transaction(function () use ($ordem, $servicoId, $quantidade, $desconto) {
            // Trava a linha da OS para ninguém fechá-la enquanto incluímos o item
            $ordem = OrdemServico::lockForUpdate()->findOrFail($ordem->id_ordem_servico);
            $this->garantirEmAndamento($ordem);

            $servico = Servico::find($servicoId);

            if (! $servico || ! $servico->ativo) {
                throw ValidationException::withMessages([
                    'servico_id' => 'Selecione um serviço ativo.',
                ]);
            }

            $desconto = round($desconto, 2);

            if ($desconto > $quantidade * (float) $servico->preco_base) {
                throw ValidationException::withMessages([
                    'desconto' => 'O desconto não pode ser maior que o valor do item.',
                ]);
            }

            $existente = $ordem->servicos()->wherePivot('servico_id', $servicoId)->first();

            if ($existente) {
                $ordem->servicos()->updateExistingPivot($servicoId, [
                    'quantidade' => $existente->pivot->quantidade + $quantidade,
                    'desconto' => round(($existente->pivot->desconto ?? 0) + $desconto, 2),
                ]);

                return;
            }

            // O valor unitário é copiado do cadastro no momento da inclusão:
            // se o preço do serviço mudar depois, esta OS não muda.
            $ordem->servicos()->attach($servicoId, [
                'quantidade' => $quantidade,
                'valor_unitario' => $servico->preco_base,
                'desconto' => $desconto,
            ]);
        });
    }


    // Inclui uma peça na OS, validando estoque e valores.
    public function adicionarPeca(
        OrdemServico $ordem,
        int $pecaId,
        int $quantidade,
        float $desconto = 0
    ): void {
        DB::transaction(function () use ($ordem, $pecaId, $quantidade, $desconto) {

            // Bloqueia a OS durante a operação.
            $ordem = OrdemServico::lockForUpdate()
                ->findOrFail($ordem->id_ordem_servico);

            $this->garantirEmAndamento($ordem);

            // Busca e bloqueia a peça para evitar concorrência no estoque.
            $peca = DB::table('oficina_mecanica.peca')
                ->where('id_peca', $pecaId)
                ->lockForUpdate()
                ->first();

            if (! $peca || ! $peca->ativo) {
                throw ValidationException::withMessages([
                    'peca_id' => 'A peça selecionada não existe ou está inativa.',
                ]);
            }

            if ($quantidade > $peca->estoque) {
                throw ValidationException::withMessages([
                    'quantidade' => 'Estoque insuficiente para essa quantidade.',
                ]);
            }

            // Não permite adicionar a mesma peça duas vezes nesta OS.
            $jaExiste = DB::table('oficina_mecanica.ordem_servico_peca')
                ->where('ordem_servico_id', $ordem->id_ordem_servico)
                ->where('peca_id', $pecaId)
                ->exists();

            if ($jaExiste) {
                throw ValidationException::withMessages([
                    'peca_id' => 'Esta peça já foi adicionada à ordem de serviço.',
                ]);
            }

            $desconto = round($desconto, 2);
            $valorTotal = $quantidade * (float) $peca->preco_venda;

            if ($desconto > $valorTotal) {
                throw ValidationException::withMessages([
                    'desconto' => 'O desconto não pode ser maior que o valor do item.',
                ]);
            }

            // A baixa de estoque é responsabilidade exclusiva da trigger do PostgreSQL.
            DB::table('oficina_mecanica.ordem_servico_peca')->insert([
                'ordem_servico_id' => $ordem->id_ordem_servico,
                'peca_id' => $pecaId,
                'quantidade' => $quantidade,
                'valor_unitario' => $peca->preco_venda,
                'desconto' => $desconto,
            ]);
        });
    }

    /**
     * Edita uma OS em andamento (veículo, data e status não mudam).
     */
    public function atualizar(OrdemServico $ordem, array $dados): OrdemServico
    {
        return DB::transaction(function () use ($ordem, $dados) {
            $ordem = OrdemServico::lockForUpdate()->findOrFail($ordem->id_ordem_servico);
            $this->garantirEmAndamento($ordem);

            $ordem->update([
                'mecanico_id' => $dados['mecanico_id'],
                'quilometragem_entrada' => $dados['quilometragem_entrada'],
                'observacoes' => $dados['observacoes'] ?? null,
                'valor_mao_obra' => $dados['valor_mao_obra'],
            ]);

            return $ordem;
        });
    }

    
    //Exclui uma OS apenas se estiver em andamento e sem itens.

    public function excluir(OrdemServico $ordem): void
    {
        DB::transaction(function () use ($ordem) {
            $ordem = OrdemServico::lockForUpdate()->findOrFail($ordem->id_ordem_servico);
            $this->garantirEmAndamento($ordem);

            $temItens = DB::table('oficina_mecanica.ordem_servico_servico')
                ->where('ordem_servico_id', $ordem->id_ordem_servico)->exists()
                || DB::table('oficina_mecanica.ordem_servico_peca')
                ->where('ordem_servico_id', $ordem->id_ordem_servico)->exists();

            if ($temItens) {
                throw ValidationException::withMessages([
                    'status' => 'Esta OS já tem serviços ou peças. Para encerrá-la, use "Cancelar OS".',
                ]);
            }

            $ordem->delete();
        });
    }


    private function somarItens(string $tabela, int $ordemId): float
    {
        $total = DB::table($tabela)
            ->where('ordem_servico_id', $ordemId)
            ->selectRaw('COALESCE(SUM(quantidade * valor_unitario - COALESCE(desconto, 0)), 0) AS total')
            ->value('total');

        return round((float) $total, 2);
    }

    private function mensagemDoBanco(QueryException $exception): string
    {
        return $exception->errorInfo[2] ?? 'Não foi possível finalizar a ordem de serviço.';
    }
}
