<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            GlassTypeSeeder::class,
            ConsortiumSeeder::class,
            CompanySeeder::class,
            FurnaceSeeder::class,
            InitialUserSeeder::class,
            ModuleSeeder::class,
        ]);
    }
}
