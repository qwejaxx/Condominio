<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascotas';

    protected $primaryKey = 'id_mas';

    protected $fillable = [
        'tipo_mas',
        'cantidad_mas',
        'propietario_id_mas',
    ];

    public function propietario()
    {
        return $this->belongsTo(Residente::class, 'propietario_id_mas');
    }
}
