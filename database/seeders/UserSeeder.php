<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'nom' => "LY",
            'prenom' => "Cheikh",
            'email' => "kha@gmail.com",
            'password' => Hash::make('12345678'),
            'role' => "DG",
            'created_at' => now()
        ]);
    }
}
