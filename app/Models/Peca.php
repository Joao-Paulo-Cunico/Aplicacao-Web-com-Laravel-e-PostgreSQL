<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Peca extends Model
{
    protected $table = 'peca';

    protected $primaryKey = 'id_peca';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'preco_custo',
        'preco_venda',
        'estoque',
        'ativo',
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'estoque' => 'integer',
        'ativo' => 'boolean',
    ];

   
    }

