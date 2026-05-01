<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\UseCases\Campaign\OpenCampaign;
use App\Core\Application\UseCases\Campaign\CloseCampaign;
use App\Core\Application\DTOs\Campaign\OpenCampaignDTO;
use App\Core\Domain\Repositories\CampaignRepositoryInterface;
use App\Http\Resources\V1\CampaignResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct(
        private CampaignRepositoryInterface $campaignRepository,
        private OpenCampaign $openCampaign,
        private CloseCampaign $closeCampaign
    ) {}

    public function index(Request $request): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json([
                'data' => [],
                'message' => 'No active company context found.'
            ], 422);
        }

        $campaigns = $this->campaignRepository->findByCompany($companyId);

        return response()->json([
            'data' => CampaignResource::collection($campaigns)
        ]);
    }

    public function start(Request $request): JsonResponse
    {
        $request->validate([
            'machine_id' => 'required|uuid',
            'article_id' => 'required|uuid',
            'client_id' => 'required|uuid',
            'codigo' => 'nullable|string|max:50',
        ]);

        $data = $request->all();
        $data['company_id'] = $this->activeCompanyId();
        $data['operator_id'] = $request->user()->id;

        if (!$data['company_id']) {
            return response()->json([
                'message' => 'No active company context found.'
            ], 422);
        }
        
        if (empty($data['codigo'])) {
            $data['codigo'] = 'CAMP-' . date('YmdHis');
        }

        $campaign = $this->openCampaign->execute(
            OpenCampaignDTO::fromRequest($data)
        );

        return response()->json([
            'message' => 'Campaña iniciada correctamente',
            'data' => new CampaignResource($campaign)
        ], 201);
    }

    public function finish(Request $request, string $campaignId): JsonResponse
    {
        $companyId = $this->activeCompanyId();

        if (!$companyId) {
            return response()->json([
                'message' => 'No active company context found.'
            ], 422);
        }

        $this->closeCampaign->execute($campaignId, $companyId);

        return response()->json([
            'message' => 'Campaña finalizada correctamente'
        ]);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $campaign = $this->campaignRepository->findById($id);
        $activeCompanyId = $this->activeCompanyId();

        if (!$campaign || $campaign->getCompanyId() !== $activeCompanyId) {
            return response()->json(['message' => 'Campaña no encontrada.'], 404);
        }

        return response()->json([
            'data' => new CampaignResource($campaign)
        ]);
    }
}
