<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Get the current server time formatted for Latin America.
     *
     * @return JsonResponse
     */
    public function getServerTime(): JsonResponse
    {
        $now = Carbon::now('America/Argentina/Buenos_Aires');

        return response()->json([
            'status' => 'success',
            'data' => [
                'iso' => $now->toIso8601String(),
                'formatted' => $now->format('d/m/Y H:i:s'),
                'timezone' => 'America/Argentina/Buenos_Aires',
                'timestamp' => $now->timestamp,
            ]
        ]);
    }
}
