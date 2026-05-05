<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Configuración', 'slug' => 'settings', 'icon' => 'heroicons-outline:cog-6-tooth'],
            ['name' => 'Producción', 'slug' => 'production', 'icon' => 'lucide:layout-dashboard'],
        ];

        foreach ($modules as $moduleData) {
            \App\Models\Module::updateOrCreate(['slug' => $moduleData['slug']], $moduleData);
        }

        // Vincular módulos al rol admin
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $allowedModules = \App\Models\Module::whereIn('slug', ['settings', 'production'])->get();
            $adminRole->modules()->sync($allowedModules->pluck('id'));
        }

        // Vincular solo producción al rol supervisor
        $supervisorRole = \App\Models\Role::where('slug', 'supervisor')->first();
        if ($supervisorRole) {
            $productionModule = \App\Models\Module::where('slug', 'production')->first();
            if ($productionModule) {
                $supervisorRole->modules()->sync([$productionModule->id]);
            }
        }
    }
}
