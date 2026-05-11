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
            UserSeeder::class,
            MachineSeeder::class,
            CampaignsAndYieldsSeeder::class,
            ExtractionSeeder::class,
            ModuleSeeder::class,
        ]);
    }
}
