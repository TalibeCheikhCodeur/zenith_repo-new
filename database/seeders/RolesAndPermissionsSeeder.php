<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'create users']);

        Permission::create(['name' => 'view modules']);
        Permission::create(['name' => 'edit modules']);
        Permission::create(['name' => 'delete modules']);
        Permission::create(['name' => 'create modules']);


        // "DG", "COT", "DPT", "client"
        // Créer les rôles et assigner les permissions
        $roleAdmin = Role::create(['name' => 'DG']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleAdmin = Role::create(['name' => 'COT']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleAdmin = Role::create(['name' => 'DPT']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleUser = Role::create(['name' => 'consultant']);
        $roleUser->givePermissionTo(['view users']);

        $roleUser = Role::create(['name' => 'client']);
        $roleUser->givePermissionTo(['view users']);
    }
}
