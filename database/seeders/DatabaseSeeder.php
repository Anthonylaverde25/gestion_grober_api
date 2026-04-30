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
            ArticleSeeder::class,
            MachineSeeder::class,
            UserSeeder::class,
            ExtractionSeeder::class,
        ]);
    }
}
