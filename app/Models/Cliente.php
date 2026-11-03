<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'oficina_mecanica.cliente';

    protected $primaryKey = 'id_cliente';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'data_cadastro',
        'ativo',
        'create_at',
        'updated_at',
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'create_at' => 'date',
        'updated_at' => 'date',
        'ativo' => 'boolean',
    ];
}
