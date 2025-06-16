<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rastreio extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_rastreio';

    protected $fillable = [
        'id_entrega',
        'codigo_rastreio',
    ];

    /**
     * Um rastreio pertence a uma entrega.
     */
    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class, 'id_entrega', 'id_entrega');
    }
}
