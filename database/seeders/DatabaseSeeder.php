<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
        [
            'nom' => "LY",
            'prenom' => "Cheikh",
            'email' => "kha@gmail.com",
            'password' => Hash::make('12345678'),
            'telephone' => "762525522",
            'role' => "DG",
            'created_at' => now()
        ]);

        $this->call(RolesAndPermissionsSeeder::class);
    }
}
