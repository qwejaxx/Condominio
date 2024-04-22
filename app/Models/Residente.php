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

    public function representanteFamiliar()
    {
        return $this->belongsTo(Residente::class, 'rep_fam_id_rsdt');
    }
}
