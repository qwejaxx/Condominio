<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Residente;
use Database\Factories\ResidenteFactory;

class ResidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Residente::create([
            'ci_rsdt' => '12525107',
            'nombre_rsdt' => 'Ivan',
            'apellidop_rsdt' => 'Rosales',
            'apellidom_rsdt' => 'Aguilar',
            'fechanac_rsdt' => '2002/05/08',
            'telefono_rsdt' => '72374591',
            'usuario_id_rsdt' => 1,
            'rep_fam_id_rsdt' => null,
        ]);

        Residente::create([
            'ci_rsdt' => '12525110',
            'nombre_rsdt' => 'Jonas',
            'apellidop_rsdt' => 'Alanes',
            'apellidom_rsdt' => 'Arrazola',
            'fechanac_rsdt' => '1998/04/19',
            'telefono_rsdt' => '70402910',
            'usuario_id_rsdt' => 2,
            'rep_fam_id_rsdt' => null,
        ]);

        Residente::create([
            'ci_rsdt' => '12525111',
            'nombre_rsdt' => 'Pepe',
            'apellidop_rsdt' => 'Quispe',
            'apellidom_rsdt' => 'Quispe',
            'fechanac_rsdt' => '1995/03/09',
            'telefono_rsdt' => '70402912',
            'usuario_id_rsdt' => null,
            'rep_fam_id_rsdt' => 2,
        ]);

        ResidenteFactory::new()->count(100)->create();
    }
}
