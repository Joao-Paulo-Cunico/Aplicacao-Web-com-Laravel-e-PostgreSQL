<?php

namespace App\Services;

use App\Models\Mecanico;
use App\Models\OrdemServico;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Servico;

class OrdemServicoService
{
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

            // Insere a peça na OS.
            // A trigger do PostgreSQL deverá realizar a baixa do estoque.
            $ordem->pecas()->attach($pecaId, [
                'quantidade' => $quantidade,
                'valor_unitario' => $peca->preco_venda,
                'desconto' => $desconto,
            ]);
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
}
