<?php

namespace App\Http\Requests;

use App\Models\Mecanico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrdemServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'veiculo_id' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $existe = DB::table('oficina_mecanica.veiculo')
                        ->where('id_veiculo', $value)
                        ->where('ativo', true)
                        ->exists();

                    if (! $existe) {
                        $fail('Selecione um veículo válido e ativo.');
                    }
                },
            ],
            'mecanico_id' => [
                'required',
                'integer',
                Rule::exists(Mecanico::class, 'id_mecanico')->where('ativo', true),
            ],
            'data_abertura' => ['required', 'date', 'before_or_equal:today'],
            'quilometragem_entrada' => ['required', 'integer', 'min:0'],
            'observacoes' => ['nullable', 'string', 'max:300'],
            'valor_mao_obra' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'veiculo_id.required' => 'Selecione o veículo.',
            'mecanico_id.required' => 'Selecione o mecânico.',
            'mecanico_id.exists' => 'Selecione um mecânico ativo.',
            'data_abertura.required' => 'Informe a data de abertura.',
            'data_abertura.before_or_equal' => 'A data de abertura não pode ser futura.',
            'quilometragem_entrada.required' => 'Informe a quilometragem de entrada.',
            'valor_mao_obra.required' => 'Informe o valor da mão de obra.',
        ];
    }
}