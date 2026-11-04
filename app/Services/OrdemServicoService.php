<?php

namespace App\Services;

use App\Models\Mecanico;
use App\Models\OrdemServico;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

    private function somarItens(string $tabela, int $ordemId): float
    {
        $total = DB::table($tabela)
            ->where('ordem_servico_id', $ordemId)
            ->selectRaw('COALESCE(SUM(quantidade * valor_unitario - COALESCE(desconto, 0)), 0) AS total')
            ->value('total');

        return round((float) $total, 2);
    }
}
