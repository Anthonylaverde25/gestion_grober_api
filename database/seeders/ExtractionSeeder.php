<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExtractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machines = Machine::all();
        $articles = Article::all();

        if ($machines->isEmpty() || $articles->isEmpty()) {
            $this->command->warn('No hay máquinas o artículos disponibles. Abortando ExtractionSeeder.');
            return;
        }

        // Definimos el rango: 2 meses atrás hasta hoy
        $startDate = Carbon::now()->subMonths(2)->startOfDay();
        $endDate = Carbon::now();

        $this->command->info('Generando histórico de extracciones (2 meses, hora a hora)...');

        foreach ($machines as $machine) {
            $this->command->info("Procesando máquina: {$machine->name}");
            
            $batchData = [];
            $currentDate = $startDate->copy();
            
            // Filtramos artículos que pertenecen SOLO a la empresa de la máquina
            $availableArticles = $articles->where('company_id', $machine->company_id);

            if ($availableArticles->isEmpty()) {
                $this->command->warn("La máquina {$machine->name} no tiene artículos disponibles en su empresa. Saltando.");
                continue;
            }

            $articleId = $availableArticles->random()->id;

            while ($currentDate <= $endDate) {
                for ($hour = 0; $hour < 24; $hour++) {
                    $measuredAt = $currentDate->copy()->hour($hour);
                    
                    // No generar datos en el futuro
                    if ($measuredAt > $endDate) {
                        break;
                    }

                    $batchData[] = [
                        'id' => Str::uuid()->toString(),
                        'machine_id' => $machine->id,
                        'article_id' => $articleId,
                        'percentage' => fake()->randomFloat(2, 72, 98), // Simulación de eficiencia productiva
                        'measured_at' => $measuredAt,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Inserción en bloques de 500 para mayor eficiencia
                    if (count($batchData) >= 500) {
                        DB::table('extractions')->insert($batchData);
                        $batchData = [];
                    }
                }
                $currentDate->addDay();
            }

            // Insertar remanentes
            if (count($batchData) > 0) {
                DB::table('extractions')->insert($batchData);
            }
        }

        $this->command->info('ExtractionSeeder finalizado con éxito.');
    }
}
