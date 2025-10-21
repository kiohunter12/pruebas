<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Gerencia;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
           

       
        // Usuarios del sistema
        $users = [
            // Super Administrador
            [
                'name' => 'Super Administrador',
                'email' => 'superadmin@muni.gob.pe',
                'password' => Hash::make('password123'),
                'gerencia_id' => null,
                'role' => 'superadministrador'
            ],
            
          
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            // Usar firstOrCreate para evitar duplicados
            $user = User::firstOrCreate(
                ['email' => $userData['email']], // Buscar por email
                $userData // Datos para crear si no existe
            );
            
            // Asignar rol al usuario si no lo tiene
            if (!$user->hasAnyRole($role)) {
                $user->assignRole($role);
            }
        }

        $this->command->info('Usuarios creados exitosamente');
        $this->command->info('Total de usuarios: ' . User::count());
        $this->command->info('Usuarios con roles asignados');
    }
}
