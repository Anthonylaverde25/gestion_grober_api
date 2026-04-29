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

        // 1. Grober Laverde - Admin Global
        $grober = User::create([
            'name' => 'Grober Laverde',
            'email' => 'grober@admin.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('company_user')->insert([
            'user_id' => $grober->id,
            'company_id' => null,
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
            'company_id' => null,
            'role_id' => $ownerRole->id,
        ]);

        // 3. Usuarios Operativos (Factory)
        $companies = Company::all();
        User::factory(10)->create()->each(function ($user) use ($companies, $operatorRole) {
            DB::table('company_user')->insert([
                'user_id' => $user->id,
                'company_id' => $companies->random()->id,
                'role_id' => $operatorRole->id,
            ]);
        });
    }
}
