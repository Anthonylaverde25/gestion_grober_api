<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $ownerRole = Role::where('slug', 'owner')->first();
        $operatorRole = Role::where('slug', 'operator')->first();
        $supervisorRole = Role::where('slug', 'supervisor')->first();

        // 1. Grober Laverde - Admin Global
        $grober = User::create([
            'name' => 'Grober Laverde',
            'email' => 'grober@admin.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('company_user')->insert([
            'user_id' => $grober->id,
            'role_id' => $adminRole->id,
        ]);

        // 2. Sergio - Owner Consorcio
        $sergio = User::create([
            'name' => 'Sergio',
            'email' => 'sergio@owner.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('company_user')->insert([
            'user_id' => $sergio->id,
            'role_id' => $ownerRole->id,
        ]);

        $companies = Company::all();

        // 3. Supervisores con Alias
        $supervisors = [
            [
                'name' => 'Supervisor Norte', 
                'email' => 'sup.norte@grober.com',
                'aliases' => [
                    ['name' => 'Ricardo Gómez', 'legajo' => 'L1000'],
                    ['name' => 'Andrés Martínez', 'legajo' => 'L1001']
                ]
            ],
            [
                'name' => 'Supervisor Sur', 
                'email' => 'sup.sur@grober.com',
                'aliases' => [
                    ['name' => 'Luis Rodríguez', 'legajo' => 'L2000'],
                    ['name' => 'Juan Pérez', 'legajo' => 'L2001']
                ]
            ],
        ];

        foreach ($supervisors as $supData) {
            $user = User::create([
                'name' => $supData['name'],
                'email' => $supData['email'],
                'password' => Hash::make('password'),
            ]);

            DB::table('company_user')->insert([
                'user_id' => $user->id,
                'company_id' => $companies->first()->id,
                'role_id' => $supervisorRole->id,
            ]);

            // Crear múltiples Alias para este supervisor
            foreach ($supData['aliases'] as $aliasData) {
                \App\Models\UserAlias::create([
                    'user_id' => $user->id,
                    'name' => $aliasData['name'],
                    'legajo' => $aliasData['legajo'],
                    'is_active' => true,
                ]);
            }
        }

        // 4. Usuarios Operativos (Factory)
        User::factory(10)->create()->each(function ($user) use ($companies, $operatorRole) {
            DB::table('company_user')->insert([
                'user_id' => $user->id,
                'company_id' => $companies->random()->id,
                'role_id' => $operatorRole->id,
            ]);
        });
    }
}
