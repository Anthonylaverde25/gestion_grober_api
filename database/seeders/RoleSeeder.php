<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'SuperAdmin', 'slug' => 'super-admin', 'description' => 'Infraestructura y sistema'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrador Global de la APP'],
            ['name' => 'Owner', 'slug' => 'owner', 'description' => 'Dueño del Consorcio (Monitoreo Total)'],
            ['name' => 'CompanyManager', 'slug' => 'company-manager', 'description' => 'Administrador Local de Planta'],
            ['name' => 'Operator', 'slug' => 'operator', 'description' => 'Carga de datos de producción'],
            ['name' => 'Viewer', 'slug' => 'viewer', 'description' => 'Acceso de consulta limitado'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
