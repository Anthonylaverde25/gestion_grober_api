<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\Consortium;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        $consortium = Consortium::where('name', 'Cattorini')->first();
        $company = Company::where('name', 'Cristalerías Cattorini Hnos')->first();
        
        $adminRole = Role::where('slug', 'admin')->first();
        $supervisorRole = Role::where('slug', 'supervisor')->first();

        if (!$adminRole || !$supervisorRole || !$company || !$consortium) {
            $this->command->error('Faltan datos base (Roles, Empresas o Consorcios). Corra los otros seeders primero.');
            return;
        }

        // Crear Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@grober.com'],
            [
                'name' => 'Administrador Grober',
                'password' => Hash::make('password'),
                'last_active_company_id' => $company->id,
            ]
        );
        
        // Vincular con la empresa y el rol
        $admin->companies()->sync([
            $company->id => ['role_id' => $adminRole->id]
        ]);

        // Crear Supervisor
        $supervisor = User::updateOrCreate(
            ['email' => 'supervisor@grober.com'],
            [
                'name' => 'Supervisor de Planta',
                'password' => Hash::make('password'),
                'last_active_company_id' => $company->id,
            ]
        );

        // Vincular con la empresa y el rol
        $supervisor->companies()->sync([
            $company->id => ['role_id' => $supervisorRole->id]
        ]);

        $this->command->info('Usuarios iniciales creados con éxito (Pass: password)');
    }
}
