<?php

namespace App\Http\Middleware;

use App\Core\Context\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class InitializeTenantContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // 1. Obtener la vinculación global (donde company_id es null)
        $globalRelation = DB::table('company_user')
            ->join('roles', 'company_user.role_id', '=', 'roles.id')
            ->where('user_id', $user->id)
            ->whereNull('company_id')
            ->select('roles.name as role_name', 'roles.slug as role_slug')
            ->first();

        $globalRoles = ['admin', 'superadmin', 'owner'];
        $isGlobalAdmin = $globalRelation && in_array(strtolower($globalRelation->role_slug), $globalRoles);

        // 2. Determinar la empresa solicitada
        $companyId = $request->header('X-Company-Context') ?: $user->last_active_company_id;

        if ($isGlobalAdmin) {
            TenantContext::setGlobal(true);
            TenantContext::setRole($globalRelation->role_name);
            
            // Si un Admin Global elige una empresa, seteamos el ID para que el Trait pueda FILTRAR si es necesario
            // pero el Trait ya está programado para NO filtrar si isGlobal es true. 
            // AJUSTE: Queremos que el Admin PUEDA filtrar por empresa si la selecciona en el Switcher.
            if ($companyId) {
                TenantContext::setCompanyId($companyId);
            }
            return $next($request);
        }

        // 3. Si no hay contexto, buscar la primera vinculación válida
        if (!$companyId) {
            $firstRelation = DB::table('company_user')
                ->where('user_id', $user->id)
                ->whereNotNull('company_id')
                ->first();
            
            $companyId = $firstRelation?->company_id;
        }

        if ($companyId) {
            // Validar que el usuario tenga acceso a esa empresa
            $relation = DB::table('company_user')
                ->join('roles', 'company_user.role_id', '=', 'roles.id')
                ->where('user_id', $user->id)
                ->where('company_id', $companyId)
                ->select('roles.name as role_name')
                ->first();

            if ($relation) {
                TenantContext::setCompanyId($companyId);
                TenantContext::setRole($relation->role_name);
                
                // Actualizar last_active si cambió
                if ($user->last_active_company_id !== $companyId) {
                    $user->update(['last_active_company_id' => $companyId]);
                }
            } else {
                return response()->json(['message' => 'No tienes acceso a la empresa solicitada.'], 403);
            }
        }

        return $next($request);
    }
}
