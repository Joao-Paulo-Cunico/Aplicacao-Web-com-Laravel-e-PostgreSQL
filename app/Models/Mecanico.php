<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mecanico extends Model
{
    protected $table = 'oficina_mecanica.mecanico';

    protected $primaryKey = 'id_mecanico';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'especilidade',
        'data_admissao',
        'ativo',
    ];

    protected $casts = [
        'data_admissao' => 'date',
        'ativo' => 'boolean',
    ];
}