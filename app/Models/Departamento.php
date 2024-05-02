<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamento';

    protected $primaryKey = 'id_dpto';

    protected $fillable = [
        'codigo_dpto',
        'precio_dpto',
        'precioa_dpto',
        'residente_id_dpto',
        'parqueo_id_dpto',
    ];

    public function parqueo()
    {
        return $this->belongsTo(Parqueo::class, 'parqueo_id_dpto');
    }

    public function adquisiciones()
    {
        return $this->hasMany(Adquisicion::class, 'departamento_id_reg', 'id_dpto');
    }
}
