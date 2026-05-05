<?php

namespace App\Http\Controllers\V1\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\LineYield;
use App\Http\Resources\V1\CampaignResource;
use App\Http\Resources\V1\LineYieldResource;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\CampaignMapper;
use App\Core\Infrastructure\Persistence\Eloquent\Mappers\LineYieldMapper;
use Illuminate\Http\JsonResponse;

class MobileDashboardController extends Controller
{
    /**
     * Get all active campaigns across all companies.
     */
    public function activeCampaigns(): JsonResponse
    {
        $campaigns = Campaign::withoutGlobalScope('company_context')
            ->with([
                'client' => fn($q) => $q->withoutGlobalScope('company_context'),
                'article' => fn($q) => $q->withoutGlobalScope('company_context'),
                'machine' => fn($q) => $q->withoutGlobalScope('company_context'),
                'company' => fn($q) => $q->withoutGlobalScope('company_context')
            ])
            ->where('status', 'ACTIVE')
            ->orderBy('started_at', 'desc')
            ->get()
            ->map(fn($item) => CampaignMapper::toDomain($item));

        return response()->json([
            'data' => CampaignResource::collection($campaigns)
        ]);
    }

    /**
     * Get details for a specific campaign globally.
     */
    public function campaignDetail(string $id): JsonResponse
    {
        $eloquent = Campaign::withoutGlobalScope('company_context')
            ->with([
                'client' => fn($q) => $q->withoutGlobalScope('company_context'),
                'article' => fn($q) => $q->withoutGlobalScope('company_context'),
                'machine' => fn($q) => $q->withoutGlobalScope('company_context'),
                'company' => fn($q) => $q->withoutGlobalScope('company_context')
            ])
            ->find($id);

        if (!$eloquent) {
            return response()->json(['message' => 'Campaña no encontrada.'], 404);
        }

        $campaign = CampaignMapper::toDomain($eloquent);

        return response()->json([
            'data' => new CampaignResource($campaign)
        ]);
    }

    /**
     * Get yield history for a specific campaign globally.
     */
    public function campaignYields(string $id): JsonResponse
    {
        // LineYield model might also have the trait, so we use withoutGlobalScope
        $yields = LineYield::withoutGlobalScope('company_context')
            ->with(['userAlias' => fn($q) => $q->withoutGlobalScope('company_context')])
            ->where('campaign_id', $id)
            ->orderBy('recorded_at', 'desc')
            ->get()
            ->map(fn($item) => LineYieldMapper::toDomain($item));

        return response()->json([
            'data' => LineYieldResource::collection($yields)
        ]);
    }
}
