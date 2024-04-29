<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Residente']);
        $role3 = Role::create(['name' => 'Personal de Seguridad']);
        $role4 = Role::create(['name' => 'Personal de Mantenimiento']);
        $role5 = Role::create(['name' => 'Personal de Limpieza']);
        $role6 = Role::create(['name' => 'Visitante']);
        /* Permission::create(['name' => 'home'])->syncRoles([$role1, $role2]); */

    }
}
