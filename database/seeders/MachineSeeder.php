<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Furnace;
use App\Models\Machine;
use App\Models\Article;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::query()->get()->keyBy('name');
        $furnaces = Furnace::query()->get()->keyBy(fn (Furnace $furnace) => $furnace->company_id . '|' . $furnace->name);
        $articles = Article::query()->get()->keyBy(fn (Article $article) => $article->company_id . '|' . $article->name);

        $machines = [
            [
                'company_name' => 'Cristalerías Cattorini Hnos',
                'furnace_name' => 'Horno A1',
                'name' => 'IS-01',
                'current_status' => 'operational',
                'article_name' => 'Botella Vino 750ml Verde (Cristalerías Cattorini Hnos)',
            ],
            [
                'company_name' => 'Cristalerías Cattorini Hnos',
                'furnace_name' => 'Horno A1',
                'name' => 'IS-02',
                'current_status' => 'maintenance',
                'article_name' => null,
            ],
            [
                'company_name' => 'Cristalerías Cattorini Hnos',
                'furnace_name' => 'Horno B2',
                'name' => 'IS-03',
                'current_status' => 'operational',
                'article_name' => 'Frasco Mermelada 400g Transparente (Cristalerías Cattorini Hnos)',
            ],
            [
                'company_name' => 'Rigolleau',
                'furnace_name' => 'Horno R1',
                'name' => 'IS-11',
                'current_status' => 'operational',
                'article_name' => 'Envase Cerveza 330ml Ámbar (Rigolleau)',
            ],
            [
                'company_name' => 'Rigolleau',
                'furnace_name' => 'Horno R1',
                'name' => 'IS-12',
                'current_status' => 'shutdown',
                'article_name' => null,
            ],
            [
                'company_name' => 'Rigolleau',
                'furnace_name' => 'Horno R3',
                'name' => 'IS-13',
                'current_status' => 'maintenance',
                'article_name' => 'Botella Agua 500ml Azul (Rigolleau)',
            ],
        ];

        foreach ($machines as $machineData) {
            $company = $companies->get($machineData['company_name']);
            $furnace = $company
                ? $furnaces->get($company->id . '|' . $machineData['furnace_name'])
                : null;
            $article = $company && $machineData['article_name']
                ? $articles->get($company->id . '|' . $machineData['article_name'])
                : null;

            if (!$company || !$furnace) {
                throw new \RuntimeException('No se pudieron resolver las relaciones para MachineSeeder.');
            }

            if ($machineData['article_name'] && !$article) {
                throw new \RuntimeException('No se pudo resolver el artículo actual para MachineSeeder.');
            }

            Machine::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'furnace_id' => $furnace->id,
                    'name' => $machineData['name'],
                ],
                [
                    'current_article_id' => $article?->id,
                    'current_status' => $machineData['current_status'],
                ]
            );
        }
    }
}
