<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residente extends Model
{
    use HasFactory;

    protected $table = 'residente';

    protected $primaryKey = 'id_rsdt';

    protected $fillable = [
        'ci_rsdt',
        'nombre_rsdt',
        'apellidop_rsdt',
        'apellidom_rsdt',
        'fechanac_rsdt',
        'telefono_rsdt',
        'usuario_id_rsdt',
        'rep_fam_id_rsdt',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id_rsdt');
    }

    public function representante()
    {
        return $this->belongsTo(Residente::class, 'rep_fam_id_rsdt');
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'residente_id_dpto');
    }

    public function adquisiciones()
    {
        return $this->hasMany(Adquisicion::class, 'residente_id_reg');
    }

    public function visitasRecibidas()
    {
        return $this->hasMany(Visita::class, 'visitado_id_vis');
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'propietario_id_mas');
    }

    public function participaciones()
    {
        return $this->hasMany(Asignacion_plan::class, 'participante_id_asip');
    }
}
