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
use DateTimeZone;

class MobileDashboardController extends Controller
{
    private const TZ_LOCAL  = 'America/Argentina/Buenos_Aires';
    private const TZ_OFFSET = '-03:00';

    public function activeCampaigns(): JsonResponse
    {
        $campaigns = Campaign::withoutGlobalScope('company_context')
            ->with([
                'client'  => fn($q) => $q->withoutGlobalScope('company_context'),
                'article' => fn($q) => $q->withoutGlobalScope('company_context'),
                'machine' => fn($q) => $q->withoutGlobalScope('company_context'),
                'company' => fn($q) => $q->withoutGlobalScope('company_context'),
            ])
            ->where('status', 'ACTIVE')
            ->orderBy('started_at', 'desc')
            ->get()
            ->map(fn($item) => CampaignMapper::toDomain($item));

        return response()->json(['data' => CampaignResource::collection($campaigns)]);
    }

    public function campaignDetail(string $id): JsonResponse
    {
        $eloquent = Campaign::withoutGlobalScope('company_context')
            ->with([
                'client'  => fn($q) => $q->withoutGlobalScope('company_context'),
                'article' => fn($q) => $q->withoutGlobalScope('company_context'),
                'machine' => fn($q) => $q->withoutGlobalScope('company_context'),
                'company' => fn($q) => $q->withoutGlobalScope('company_context'),
            ])
            ->find($id);

        if (!$eloquent) {
            return response()->json(['message' => 'Campaña no encontrada.'], 404);
        }

        return response()->json(['data' => new CampaignResource(CampaignMapper::toDomain($eloquent))]);
    }

    /**
     * Paginated raw yield records — 30 per page, ordered desc.
     * Used by the "full history" infinite-scroll screen.
     */
    public function campaignYields(string $id): JsonResponse
    {
        $paginator = LineYield::withoutGlobalScope('company_context')
            ->with(['userAlias' => fn($q) => $q->withoutGlobalScope('company_context')])
            ->where('campaign_id', $id)
            ->orderBy('recorded_at', 'desc')
            ->paginate(perPage: 30);

        $items = collect($paginator->items())
            ->map(fn($item) => LineYieldMapper::toDomain($item));

        return response()->json([
            'data' => LineYieldResource::collection($items),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Pre-processed dashboard summary — single source of truth for the mobile detail screen.
     *
     * Runs 3 focused DB queries and returns everything the frontend needs:
     *   1. kpis          — global aggregate stats (1 row).
     *   2. chart_points  — one point per hour with pre-formatted label (no frontend math).
     *   3. recent_records — last 24 raw records, timezone-adjusted and pre-formatted.
     *
     * The frontend must NOT do any numeric calculation — it only renders what arrives here.
     */
    public function campaignYieldSummary(string $id): JsonResponse
    {
        // ── Query 1: global KPIs ─────────────────────────────────────────────
        $kpis = LineYield::withoutGlobalScope('company_context')
            ->where('campaign_id', $id)
            ->selectRaw("
                COUNT(*)                                               AS total_records,
                ROUND(AVG(forming_yield), 1)                          AS avg_forming,
                ROUND(AVG(packing_yield), 1)                          AS avg_packing,
                ROUND(AVG((forming_yield + packing_yield) / 2.0), 1) AS avg_total,
                ROUND(MAX((forming_yield + packing_yield) / 2.0), 1) AS max_total,
                ROUND(MIN((forming_yield + packing_yield) / 2.0), 1) AS min_total
            ")
            ->first();

        // ── Query 2: hourly aggregates — chart points + trend ────────────────
        // label is pre-formatted by MySQL so the frontend just passes it to gifted-charts.
        $hourlyRows = LineYield::withoutGlobalScope('company_context')
            ->where('campaign_id', $id)
            ->selectRaw("
                DATE_FORMAT(CONVERT_TZ(recorded_at, '+00:00', '" . self::TZ_OFFSET . "'), '%Y-%m-%d %H:00:00') AS bucket,
                DATE_FORMAT(CONVERT_TZ(recorded_at, '+00:00', '" . self::TZ_OFFSET . "'), '%H:%i')             AS label,
                ROUND(AVG(forming_yield), 1)                                                                    AS avg_forming,
                ROUND(AVG(packing_yield), 1)                                                                    AS avg_packing,
                COUNT(*)                                                                                        AS sample_count
            ")
            ->groupBy('bucket', 'label')
            ->orderBy('bucket', 'asc')
            ->get();

        // Derive trend from the last two hourly buckets (no extra query needed)
        $trend = 'stable';
        $count = $hourlyRows->count();
        if ($count >= 2) {
            $last = ($hourlyRows->last()->avg_forming + $hourlyRows->last()->avg_packing) / 2;
            $prev = $hourlyRows->get($count - 2);
            $prevVal = ($prev->avg_forming + $prev->avg_packing) / 2;
            if ($last > $prevVal + 1)      $trend = 'up';
            elseif ($last < $prevVal - 1)  $trend = 'down';
        }

        $chartPoints = $hourlyRows->map(fn($row) => [
            'label'        => $row->label,       // "14:00" — ready for gifted-charts
            'avg_forming'  => (float) $row->avg_forming,
            'avg_packing'  => (float) $row->avg_packing,
            'sample_count' => (int)   $row->sample_count,
        ])->values();

        // ── Query 3: last 24 records — pre-formatted for the timeline list ───
        $tz = new DateTimeZone(self::TZ_LOCAL);

        $recentRecords = LineYield::withoutGlobalScope('company_context')
            ->with(['userAlias' => fn($q) => $q->withoutGlobalScope('company_context')])
            ->where('campaign_id', $id)
            ->orderBy('recorded_at', 'desc')
            ->limit(24)
            ->get()
            ->map(function ($row) use ($tz) {
                $local = $row->recorded_at->copy()->setTimezone($tz);
                $f     = round((float) $row->forming_yield, 1);
                $p     = round((float) $row->packing_yield, 1);
                return [
                    'id'             => $row->id,
                    'forming_yield'  => $f,
                    'packing_yield'  => $p,
                    'avg_yield'      => round(($f + $p) / 2.0, 1),
                    'time_label'     => $local->format('H:i'),   // "14:00"
                    'date_label'     => $local->format('d/m'),   // "11/05"
                    'operator_alias' => $row->userAlias?->alias,
                ];
            })
            ->values();

        return response()->json([
            'data' => [
                'kpis' => [
                    'total_records' => (int)   ($kpis->total_records ?? 0),
                    'avg_forming'   => (float) ($kpis->avg_forming   ?? 0),
                    'avg_packing'   => (float) ($kpis->avg_packing   ?? 0),
                    'avg_total'     => (float) ($kpis->avg_total     ?? 0),
                    'max'           => (float) ($kpis->max_total     ?? 0),
                    'min'           => (float) ($kpis->min_total     ?? 0),
                    'trend'         => $trend,
                ],
                'chart_points'   => $chartPoints,
                'recent_records' => $recentRecords,
            ],
        ]);
    }
}
