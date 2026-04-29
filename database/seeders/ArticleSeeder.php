<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Article;
use App\Models\Company;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        if ($companies->isEmpty()) {
            return;
        }

        $articleTemplates = [
            ['name' => 'Botella Vino 750ml Verde'],
            ['name' => 'Envase Cerveza 330ml Ámbar'],
            ['name' => 'Frasco Mermelada 400g Transparente'],
            ['name' => 'Botella Agua 500ml Azul'],
        ];

        foreach ($companies as $company) {
            foreach ($articleTemplates as $template) {
                Article::create([
                    'company_id' => $company->id,
                    'name' => $template['name'] . ' (' . $company->name . ')',
                ]);
            }
        }
    }
}
