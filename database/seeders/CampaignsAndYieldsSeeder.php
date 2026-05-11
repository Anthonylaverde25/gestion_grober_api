<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Campaign;
use App\Models\LineYield;
use App\Models\Company;
use App\Models\Machine;
use App\Models\Article;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CampaignsAndYieldsSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            $this->command->warn('No companies found. Run the main DatabaseSeeder first.');
            return;
        }

        // 1. Crear Clientes con campos correctos
        $clientA = Client::firstOrCreate(
            ['tax_id' => '30-11111111-1'],
            [
                'id' => Str::uuid()->toString(),
                'company_id' => $company->id,
                'commercial_name' => 'Glass Corp',
                'business_name' => 'Glass Corporation S.A.',
                'technical_contact' => 'Juan Perez',
                'email' => 'juan@glasscorp.com'
            ]
        );

        $clientB = Client::firstOrCreate(
            ['tax_id' => '30-22222222-2'],
            [
                'id' => Str::uuid()->toString(),
                'company_id' => $company->id,
                'commercial_name' => 'Vidrios del Sur',
                'business_name' => 'Vidrios del Sur SRL',
                'technical_contact' => 'Maria Gonzalez',
                'email' => 'maria@vidriosur.com'
            ]
        );

        // 2. Obtener recursos para las campañas
        $machines = Machine::where('company_id', $company->id)->get();
        $articles = Article::where('company_id', $company->id)->get();

        if ($machines->isEmpty() || $articles->isEmpty()) {
            $this->command->warn('No machines or articles found to create campaigns.');
            return;
        }

        // Limpiar campañas previas para evitar conflictos de maquina bloqueada
        Campaign::where('company_id', $company->id)->delete();
        Machine::where('company_id', $company->id)->update(['current_campaign_id' => null]);

        // 3. Crear Campañas para diferentes máquinas
        foreach ($machines as $index => $machine) {
            // Alternamos clientes y artículos
            $client = ($index % 2 === 0) ? $clientA : $clientB;
            $article = $articles[$index % $articles->count()];
            
            // Iniciamos la campaña hace 91 días para tener 3 meses de datos
            $startedAt = Carbon::now()->subDays(91);

            $campaign = Campaign::create([
                'id' => Str::uuid()->toString(),
                'codigo' => 'CAMP-00' . ($index + 1),
                'company_id' => $company->id,
                'machine_id' => $machine->id,
                'article_id' => $article->id,
                'client_id' => $client->id,
                'status' => 'ACTIVE',
                'started_at' => $startedAt,
                'total_yield_records' => 0,
            ]);

            // Vincular campaña a la máquina
            $machine->update(['current_campaign_id' => $campaign->id]);

            // Generar 3 meses de datos (cada hora)
            $totalRecords = 0;
            for ($day = 90; $day >= 0; $day--) {
                for ($hour = 0; $hour < 24; $hour++) {
                    // Evitar registros en el futuro
                    $recordedAt = Carbon::now()->subDays($day)->startOfDay()->addHours($hour);
                    if ($recordedAt->isFuture()) continue;

                    $forming = rand(88, 98) + (rand(0, 100) / 100); // 88.00 - 99.00
                    $packing = $forming - rand(2, 8) - (rand(0, 100) / 100); // Packing siempre menor a forming
                    
                    LineYield::create([
                        'id' => Str::uuid()->toString(),
                        'campaign_id' => $campaign->id,
                        'company_id' => $company->id,
                        'forming_yield' => $forming,
                        'packing_yield' => max(0, $packing),
                        'notes' => 'Registro automático por hora',
                        'recorded_at' => $recordedAt,
                    ]);
                    $totalRecords++;
                }
            }
            
            $campaign->update(['total_yield_records' => $totalRecords]);
        }

        $this->command->info("Seeded {$totalRecords} hourly records for each of the " . $machines->count() . " machines!");
    }
}
