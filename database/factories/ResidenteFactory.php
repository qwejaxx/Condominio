<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Residente;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Residente>
 */
class ResidenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Residente::class;


    public function definition(): array
    {
        return [
            'ci_rsdt' => $this->faker->numerify('########'),
            'nombre_rsdt' => $this->faker->firstName,
            'apellidop_rsdt' => $this->faker->lastName,
            'apellidom_rsdt' => $this->faker->lastName,
            'fechanac_rsdt' => $this->faker->date('Y-m-d', '1998-04-19'),
            'telefono_rsdt' => $this->faker->numerify('########'),
            'usuario_id_rsdt' => function () {
                return User::factory()->create()->id;
            },
            'rep_fam_id_rsdt' => null,
        ];
    }
}
