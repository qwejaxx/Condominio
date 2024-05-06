<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visita';

    protected $primaryKey = 'id_vis';

    protected $fillable = [
        'visitante_id_vis',
        'visitado_id_vis',
        'fecha_vis',
    ];

    public function visitante()
    {
        return $this->belongsTo(Residente::class, 'visitante_id_vis');
    }

    public function visitado()
    {
        return $this->belongsTo(Residente::class, 'visitado_id_vis');
    }
}
