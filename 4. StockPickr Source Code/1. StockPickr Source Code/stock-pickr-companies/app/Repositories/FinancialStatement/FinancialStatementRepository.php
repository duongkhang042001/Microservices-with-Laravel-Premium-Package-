<?php

namespace App\Repositories\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\FinancialStatement;

abstract class FinancialStatementRepository
{
    abstract public function save(Company $company, int $year, array $data): FinancialStatement;
    abstract public function getAttributes(): array;
}
