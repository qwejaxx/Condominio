<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adquisicion extends Model
{
    use HasFactory;

    protected $table = 'adquisicion';

    protected $primaryKey = 'id_reg';

    protected $fillable = [
        'departamento_id_reg',
        'residente_id_reg',
        'tipoadq_reg',
        'inicio_reg',
        'fin_reg',
        'pago_reg',
    ];

    public function residente()
    {
        return $this->belongsTo(Residente::class, 'residente_id_reg');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id_reg');
    }
}
