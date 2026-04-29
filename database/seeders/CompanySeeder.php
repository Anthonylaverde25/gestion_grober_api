<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Consortium;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $consortium = Consortium::where('name', 'Cattorini')->first();

        $companies = [
            ['name' => 'Cristalerías Cattorini Hnos', 'consortium_id' => $consortium->id],
            ['name' => 'Rigolleau', 'consortium_id' => $consortium->id],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
