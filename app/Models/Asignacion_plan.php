<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion_plan extends Model
{
    use HasFactory;

    protected $table = 'asignacion_plan';

    protected $primaryKey = 'id_asip';

    protected $fillable = [
        'planificacion_id_asip',
        'participante_id_asip'
    ];

    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class, 'planificacion_id_asip');
    }

    public function participante()
    {
        return $this->belongsTo(Residente::class, 'participante_id_asip');
    }
}
