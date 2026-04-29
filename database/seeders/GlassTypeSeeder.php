<?php

namespace Database\Seeders;

use App\Models\GlassType;
use Illuminate\Database\Seeder;

class GlassTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Sodo-cálcico', 'Borosilicato', 'Plomo', 'Aluminosilicato'];

        foreach ($types as $type) {
            GlassType::create(['name' => $type]);
        }
    }
}
