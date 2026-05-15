<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Machine\ListMachinesByCompany;
use App\Core\Domain\Repositories\LineYieldRepositoryInterface;
use App\Http\Resources\V1\MachineResource;
use App\Http\Resources\V1\LineYieldResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private ListMachinesByCompany $listMachinesByCompany,
        private LineYieldRepositoryInterface $lineYieldRepository
    ) {}

    /**
     * Get the active production overview for the desktop dashboard.
     */
    public function overview(Request $request): JsonResponse
    {
        $companyId = $request->query('company_id') ?? $this->activeCompanyId();

        if (!$companyId) {
            return response()->json([
                'message' => 'Debe enviar company_id o tener un contexto de empresa activo',
            ], 400);
        }

        // 1. Get all machines for the company
        $allMachines = $this->listMachinesByCompany->execute($companyId);

        // 2. Filter machines with active campaign and prepare data
        $activeProduction = [];
        $yieldSeries = [];

        foreach ($allMachines as $machine) {
            // Only include machines with an active campaign
            if ($machine->getCurrentCampaignId()) {
                
                // Get the latest 20 yields for this machine to build the area chart
                $history = $this->lineYieldRepository->findByMachine($machine->getId(), 20);
                
                // Structure for "Producción en Curso" (Latest snapshot)
                $latestYield = !empty($history) ? $history[0] : null;
                
                $activeProduction[] = [
                    'id' => $machine->getId(),
                    'name' => $machine->getName(),
                    'status' => $machine->getStatus(),
                    'current_article_name' => $machine->getCurrentArticleName(),
                    'current_client_name' => $machine->getCurrentClientName(),
                    'current_campaign_id' => $machine->getCurrentCampaignId(),
                    'latest_yield' => $latestYield ? [
                        'percentage' => $latestYield->getPackingYield(),
                        'recorded_at' => $latestYield->getRecordedAt()->format('Y-m-d H:i:s'),
                    ] : null,
                ];

                // Structure for the comparative area chart series
                if (!empty($history)) {
                    // We reverse it to have chronological order (oldest to newest)
                    $historyData = array_reverse($history);
                    
                    $yieldSeries[] = [
                        'machine_id' => $machine->getId(),
                        'machine_name' => $machine->getName(),
                        'data' => array_map(function($y) {
                            return [
                                'value' => $y->getPackingYield(),
                                'time' => $y->getRecordedAt()->format('H:i'),
                                'timestamp' => $y->getRecordedAt()->format('Y-m-d H:i:s'),
                            ];
                        }, $historyData)
                    ];
                }
            }
        }

        return response()->json([
            'data' => [
                'active_production' => $activeProduction,
                'yield_series' => $yieldSeries,
                'summary' => [
                    'total_active_lines' => count($activeProduction),
                    'average_yield' => count($activeProduction) > 0 
                        ? array_sum(array_map(fn($m) => $m['latest_yield']['percentage'] ?? 0, $activeProduction)) / count($activeProduction)
                        : 0
                ]
            ]
        ]);
    }

    public function linesPerformanceSummary(Request $request): JsonResponse
    {
        // Calculamos los KPIs globales basándonos en los LineYields de las campañas ACTIVAS.
        // El global scope de company_id actuará automáticamente.
        $kpis = \App\Models\LineYield::whereHas('campaign', function($q) {
                $q->where('status', 'ACTIVE');
            })
            ->selectRaw("
                ROUND(AVG(forming_yield), 1) as avg_forming,
                ROUND(AVG(packing_yield), 1) as avg_packing
            ")
            ->first();

        return response()->json([
            'data' => [
                'avg_forming' => (float) ($kpis->avg_forming ?? 0),
                'avg_packing' => (float) ($kpis->avg_packing ?? 0),
            ]
        ]);
    }
}
