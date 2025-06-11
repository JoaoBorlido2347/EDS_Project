<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'telefone' => '123456789',
            'role' => 'administrador',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Test Manager',
            'email' => 'gestor@example.com',
            'password' => Hash::make('password123'),
            'telefone' => '987654321',
            'role' => 'gestor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Test Employee',
            'email' => 'funcionario@example.com',
            'password' => Hash::make('password123'),
            'telefone' => '555555555',
            'role' => 'funcionario',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}