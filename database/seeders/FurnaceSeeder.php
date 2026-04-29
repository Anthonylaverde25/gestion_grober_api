<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Furnace;
use App\Models\GlassType;
use Illuminate\Database\Seeder;

class FurnaceSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::query()->get()->keyBy('name');
        $glassTypes = GlassType::query()->get()->keyBy('name');

        $furnaces = [
            [
                'company_name' => 'Cristalerías Cattorini Hnos',
                'glass_type_name' => 'Sodo-cálcico',
                'name' => 'Horno A1',
                'max_capacity_tons' => 120.50,
                'current_status' => 'operational',
            ],
            [
                'company_name' => 'Cristalerías Cattorini Hnos',
                'glass_type_name' => 'Borosilicato',
                'name' => 'Horno B2',
                'max_capacity_tons' => 85.00,
                'current_status' => 'maintenance',
            ],
            [
                'company_name' => 'Cristalerías Cattorini Hnos',
                'glass_type_name' => 'Aluminosilicato',
                'name' => 'Horno C3',
                'max_capacity_tons' => 98.75,
                'current_status' => 'operational',
            ],
            [
                'company_name' => 'Rigolleau',
                'glass_type_name' => 'Sodo-cálcico',
                'name' => 'Horno R1',
                'max_capacity_tons' => 110.00,
                'current_status' => 'operational',
            ],
            [
                'company_name' => 'Rigolleau',
                'glass_type_name' => 'Plomo',
                'name' => 'Horno R2',
                'max_capacity_tons' => 72.40,
                'current_status' => 'shutdown',
            ],
            [
                'company_name' => 'Rigolleau',
                'glass_type_name' => 'Borosilicato',
                'name' => 'Horno R3',
                'max_capacity_tons' => 90.30,
                'current_status' => 'maintenance',
            ],
        ];

        foreach ($furnaces as $furnaceData) {
            $company = $companies->get($furnaceData['company_name']);
            $glassType = $glassTypes->get($furnaceData['glass_type_name']);

            if (!$company || !$glassType) {
                throw new \RuntimeException('No se pudieron resolver las relaciones para FurnaceSeeder.');
            }

            Furnace::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'name' => $furnaceData['name'],
                ],
                [
                    'glass_type_id' => $glassType->id,
                    'max_capacity_tons' => $furnaceData['max_capacity_tons'],
                    'current_status' => $furnaceData['current_status'],
                ]
            );
        }
    }
}
