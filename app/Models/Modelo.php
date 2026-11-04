<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modelo extends Model
{
    protected $table = 'oficina_mecanica.modelo';

    protected $primaryKey = 'id_modelo';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'marca_id',
        'nome',
        'ano_inicio',
        'ano_fim',
    ];

    protected $casts = [
        'ano_inicio' => 'date',
        'ano_fim' => 'date',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(
            Marca::class,
            'marca_id',
            'id_marca'
        );
    }
}
