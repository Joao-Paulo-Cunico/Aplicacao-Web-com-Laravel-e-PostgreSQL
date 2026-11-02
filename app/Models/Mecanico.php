<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function ordensServico(): HasMany
    {
        return $this->hasMany(OrdemServico::class, 'mecanico_id', 'id_mecanico');
    }
}
