<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parqueo extends Model
{
    use HasFactory;

    protected $table = 'parqueo';

    protected $primaryKey = 'id_park';

    protected $fillable = [
        'codigo_park',
        'slots_park',
    ];

    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'parqueo_id_dpto');
    }
}
