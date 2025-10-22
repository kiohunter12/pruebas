<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos para Workflows
        $workflowPermissions = [
            'gestionar_workflows',
            'ver_workflows',
            'crear_workflows',
            'editar_workflows',
            'eliminar_workflows'
        ];

        foreach ($workflowPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear rol de administrador si no existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Asignar todos los permisos al rol de administrador
        $adminRole->givePermissionTo($workflowPermissions);

        // Crear un usuario de prueba con el rol de administrador
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password'),
            ]
        );

        $user->assignRole($adminRole);
    }
}