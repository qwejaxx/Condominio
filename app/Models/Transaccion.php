<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    protected $primaryKey = 'id_tr';

    protected $fillable = [
        'plan_id_tr',
        'residente_id_tr',
        'tipo_tr',
        'monto_tr',
        'fecha_tr',
    ];

    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class, 'plan_id_tr');
    }

    public function residente()
    {
        return $this->belongsTo(Residente::class, 'residente_id_tr');
    }
}
