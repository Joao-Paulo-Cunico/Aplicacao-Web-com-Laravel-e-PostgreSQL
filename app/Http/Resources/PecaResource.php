<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PecaResource extends JsonResource
{
    // Define os dados que a API vai retornar
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_peca,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'preco_custo' => $this->preco_custo,
            'preco_venda' => $this->preco_venda,
            'estoque' => $this->estoque,
            'ativo' => $this->ativo,
        ];
    }
}
