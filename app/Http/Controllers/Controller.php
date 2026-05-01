<?php

namespace App\Http\Controllers;

use App\Core\Context\TenantContext;

abstract class Controller
{
    protected function activeCompanyId(): ?string
    {
        return TenantContext::getCompanyId();
    }
}
