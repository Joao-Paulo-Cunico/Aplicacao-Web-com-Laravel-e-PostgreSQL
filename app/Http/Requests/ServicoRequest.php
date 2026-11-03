<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'descricao' => ['required', 'string', 'max:500'],
            'preco_base' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'tempo_estimado' => ['required', 'integer', 'min:1'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do serviço.',
            'descricao.required' => 'Informe a descrição do serviço.',
            'preco_base.required' => 'Informe o preço base.',
            'preco_base.numeric' => 'O preço base deve ser um número.',
            'preco_base.min' => 'O preço base não pode ser negativo.',
            'tempo_estimado.required' => 'Informe o tempo estimado.',
            'tempo_estimado.integer' => 'O tempo estimado deve ser um número inteiro.',
            'tempo_estimado.min' => 'O tempo estimado deve ser de pelo menos 1.',
        ];
    }
}