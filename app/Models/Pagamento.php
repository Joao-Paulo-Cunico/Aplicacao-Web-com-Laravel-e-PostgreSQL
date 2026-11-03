<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{

    protected $table = 'pagamento';


    protected $primaryKey = 'id_pagamento';

    public $timestamps = false;

    protected $fillable = [
        'ordem_servico_id',
        'data_pagamento',
        'valor',
        'forma_pagamento',
        'status',
    ];

    protected $casts = [
        'data_pagamento' => 'date',
        'valor' => 'decimal:2',
    ];
}
