<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servico extends Model
{
    protected $table = 'oficina_mecanica.servico';

    protected $primaryKey = 'id_servico';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'preco_base',
        'tempo_estimado',
        'ativo',
    ];

    protected $casts = [
        'preco_base' => 'decimal:2',
        'tempo_estimado' => 'integer',
        'ativo' => 'boolean',
    ];

    public function ordensServico(): BelongsToMany
    {
        return $this->belongsToMany(
            OrdemServico::class,
            'oficina_mecanica.ordem_servico_servico',
            'servico_id',
            'ordem_servico_id'
        )->withPivot(['quantidade', 'valor_unitario', 'desconto']);
    }
}