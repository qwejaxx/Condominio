<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planificacion extends Model
{
    use HasFactory;

    protected $table = 'planificacion';

    protected $primaryKey = 'id_plan';

    protected $fillable = [
        'motivo_plan',
        'descripcion_plan',
        'area_plan',
        'pago_plan',
        'inicio_plan',
        'fin_plan',
    ];

    public function asignaciones()
    {
        return $this->hasMany(Asignacion_Plan::class, 'planificacion_id_asip');
    }
}
