<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Veiculo extends Model
{
    protected $table = 'oficina_mecanica.veiculo';

    protected $primaryKey = 'id_veiculo';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'modelo_id',
        'placa',
        'ano',
        'cor',
        'quilometragem',
        'data_cadastro',
        'ativo',
    ];

    protected $casts = [
        'ano' => 'date',
        'data_cadastro' => 'date',
        'quilometragem' => 'integer',
        'ativo' => 'boolean',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(
            Cliente::class,
            'cliente_id',
            'id_cliente'
        );
    }

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(
            Modelo::class,
            'modelo_id',
            'id_modelo'
        );
    }
}
