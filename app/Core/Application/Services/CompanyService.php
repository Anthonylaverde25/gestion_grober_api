<?php

namespace App\Core\Application\Services;

use App\Core\Application\UseCases\Company\ListCompanies;
use App\Core\Application\UseCases\Company\GetCompanyById;

class CompanyService
{
    public function __construct(
        private ListCompanies $listCompanies,
        private GetCompanyById $getCompanyById
    ) {}

    /**
     * Obtener el listado global de empresas.
     */
    public function getAllCompanies(): array
    {
        return $this->listCompanies->execute();
    }

    /**
     * Obtener el detalle de una empresa por su ID.
     */
    public function getCompany(string $id)
    {
        return $this->getCompanyById->execute($id);
    }
}
