<?php

namespace App\Services;

use App\Models\Mecanico;
use App\Models\OrdemServico;
use Illuminate\Validation\ValidationException;

class OrdemServicoService
{

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
}