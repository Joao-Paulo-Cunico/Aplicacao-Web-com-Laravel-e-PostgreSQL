<?php

namespace App\Http\Requests;

use App\Models\Mecanico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MecanicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:300'],
            'cpf' => [
                'required',
                'digits:11',
                // No update, ignora o próprio mecânico na checagem de CPF único
                Rule::unique(Mecanico::class, 'cpf')->ignore($this->route('mecanico')),
            ],
            'telefone' => ['required', 'digits_between:10,11'],
            'especilidade' => ['required', 'string', 'max:150'],
            'data_admissao' => ['required', 'date', 'before_or_equal:today'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do mecânico.',
            'cpf.required' => 'Informe o CPF.',
            'cpf.unique' => 'Já existe um mecânico com este CPF.',
            'especilidade.required' => 'Informe a especialidade.',
            'data_admissao.required' => 'Informe a data de admissão.',
            'data_admissao.before_or_equal' => 'A data de admissão não pode ser futura.',
        ];
    }
}