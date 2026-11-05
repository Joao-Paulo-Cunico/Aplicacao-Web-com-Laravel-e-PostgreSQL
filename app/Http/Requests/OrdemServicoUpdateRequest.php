<?php

namespace App\Http\Requests;

use App\Models\Mecanico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrdemServicoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mecanico_id' => ['required', 'integer', Rule::exists(Mecanico::class, 'id_mecanico')->where('ativo', true)],
            'quilometragem_entrada' => ['required', 'integer', 'min:0'],
            'observacoes' => ['nullable', 'string', 'max:300'],
            'valor_mao_obra' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'mecanico_id.required' => 'Selecione o mecânico.',
            'mecanico_id.exists' => 'Selecione um mecânico ativo.',
            'quilometragem_entrada.required' => 'Informe a quilometragem de entrada.',
            'valor_mao_obra.required' => 'Informe o valor da mão de obra.',
        ];
    }
}