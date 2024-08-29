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
        // User::firstOrCreate([
        //     'nom' => "LY",
        //     'prenom' => "Cheikh",
        //     'email' => "kha@gmail.com",
        //     'password' => Hash::make('12345678'),
        //     'telephone' => '762943535',
        //     'role' => "DG",
        //     'created_at' => now()
        // ]);


        User::firstOrCreate([
            'nom' => "Ndiaye",
            'prenom' => "ISEC",
            'email' => "isec@gmail.com",
            'password' => Hash::make('12345678'),
            'telephone' => '762443535',
            'role' => "client",
            'created_at' => now()
        ]);


    }
}
