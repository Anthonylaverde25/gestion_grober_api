<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Core\Application\Services\CompanyService;
use App\Http\Resources\V1\CompanyResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    public function __construct(
        private CompanyService $companyService
    ) {}

    /**
     * Listado global de empresas.
     */
    public function index(): AnonymousResourceCollection
    {
        $companies = $this->companyService->getAllCompanies();
        
        return CompanyResource::collection($companies);
    }

    /**
     * Detalle de una empresa específica.
     */
    public function show(string $id): CompanyResource
    {
        $company = $this->companyService->getCompany($id);
        
        return new CompanyResource($company);
    }
}
