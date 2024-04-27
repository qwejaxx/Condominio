<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Residente;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
        // Obtener todos los IDs de los Residentes
        $residenteIds = Residente::pluck('id_rsdt')->toArray();
        $residenteIds[] = null;

        // Seleccionar un ID aleatorio de la lista
        $randomResidenteId = !empty($residenteIds) ? $residenteIds[array_rand($residenteIds)] : null;

        // Crear un nuevo usuario solo si no hay un Residente aleatorio
        $nombre = $this->faker->firstName();
        $apellidop = $this->faker->lastName();
        $apellidom = $this->faker->lastName();

        // Obtener un Rol aleatorio
        $roles = Role::pluck('id')->toArray();
        $randomRoleId = !empty($roles) ? $roles[array_rand($roles)] : null;

        $usuarioId = $randomResidenteId == null ? User::factory()->create(['name' => $nombre . ' ' . $apellidop])->assignRole($randomRoleId)->id : null;

        return [
            'ci_rsdt' => $this->faker->numerify('########'),
            'nombre_rsdt' => $nombre,
            'apellidop_rsdt' => $apellidop,
            'apellidom_rsdt' => $apellidom,
            'fechanac_rsdt' => $this->faker->date('Y-m-d', '1998-04-19'),
            'telefono_rsdt' => $this->faker->numerify('########'),
            'usuario_id_rsdt' => $usuarioId,
            'rep_fam_id_rsdt' => $randomResidenteId,
        ];
    }
}
