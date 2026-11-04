<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrdemServico extends Model
{
    protected $table = 'oficina_mecanica.ordem_servico';

    protected $primaryKey = 'id_ordem_servico';

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'veiculo_id',
        'mecanico_id',
        'data_abertura',
        'data_fechamento',
        'status',
        'quilometragem_entrada',
        'observacoes',
        'valor_mao_obra',
    ];

    protected $casts = [
        'data_abertura' => 'date',
        'data_fechamento' => 'date',
        'quilometragem_entrada' => 'integer',
        'valor_mao_obra' => 'decimal:2',
    ];

    public function mecanico(): BelongsTo
    {
        return $this->belongsTo(Mecanico::class, 'mecanico_id', 'id_mecanico');
    }

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id', 'id_veiculo');
    }

    public function servicos(): BelongsToMany
    {
        return $this->belongsToMany(
            Servico::class,
            'oficina_mecanica.ordem_servico_servico',
            'ordem_servico_id',
            'servico_id'
        )->withPivot(['quantidade', 'valor_unitario', 'desconto']);
    }

    public function pecas(): BelongsToMany
    {
        return $this->belongsToMany(
            Peca::class,
            'oficina_mecanica.ordem_servico_peca',
            'ordem_servico_id',
            'peca_id'
        )->withPivot(['quantidade', 'valor_unitario', 'desconto']);
    }
}