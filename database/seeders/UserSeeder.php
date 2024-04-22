<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ivan',
            'email' => 'ivanrosales395@gmail.com',
            'password' => Hash::make('asdf1234'),
            'estado' => 1
        ])->assignRole('Administrador');

        User::create([
            'name' => 'El pepe',
            'email' => 'pepe@gmail.com',
            'password' => Hash::make('asdf1234'),
            'estado' => 1
        ])->assignRole('Residente');
    }
}
