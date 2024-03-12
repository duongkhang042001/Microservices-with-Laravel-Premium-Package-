<?php

namespace App\Services\Company;

use App\Containers\IncomingFinancialStatementContainer;
use App\Models\Company\Company;
use App\Models\Company\CompanyLog;
use App\Repositories\Company\CompanyRepository;
use App\Services\FinancialStatement\FinancialStatementContainerFactoryService;
use App\Services\FinancialStatement\FinancialStatementService;
use App\Services\RedisService;
use Exception;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer;

class CompanyService
{
    public function __construct(
        private FinancialStatementService $financialStatementService,
        private CompanyValidatorService $validator,
        private CompanyRepository $companies,
        private FinancialStatementContainerFactoryService $containerFactory,
        private RedisService $redisService
    ) {
    }

    public function upsert(UpsertCompanyContainer $companyContainer): Company
    {
        $this->validator->validate($companyContainer);

        $company = $this->companies->upsert($companyContainer);
        $this->companies->addPeers($company, $companyContainer->peers, 5);

        $this->addFinancialStatements(
            $company,
            new IncomingFinancialStatementContainer($companyContainer->getIncomeStatements()),
            new IncomingFinancialStatementContainer($companyContainer->getBalanceSheets()),
            new IncomingFinancialStatementContainer($companyContainer->getCashFlows())
        );

        $company->fresh();
        return $company;
    }

    public function addFinancialStatements(
        Company $company,
        IncomingFinancialStatementContainer $incomeStatements,
        IncomingFinancialStatementContainer $balanceSheets,
        IncomingFinancialStatementContainer $cashFlows
    ) {
        // Ezeket az éveket ki fogja hagyni. Ha már létezik a cég, és csak updateljük
        $skippedYears = $this->financialStatementService->getYearsWhereHasData($company);

        $data = [
            'income_statements' => $incomeStatements,
            'balance_sheets' => $balanceSheets,
            'cash_flows' => $cashFlows
        ];

        foreach ($data as $table => $statement) {
            $this->financialStatementService->save($company, $statement, $table, $skippedYears);
        }
    }

    public function convertToContainer(Company $company): CompanyUpsertedContainer
    {
        return CompanyUpsertedContainer::from([
            'id' => $company->id,
            'ticker' => $company->ticker,
            'name' => $company->name,
            'fullName' => $company->full_name,
            'sector' => $company->sector->toArray(),
            'description' => $company->description,
            'industry' => $company->industry,
            'ceo' => $company->ceo,
            'employees' => $company->employees_formatted,
            'peers' => $company->peers->pluck('ticker')->all(),
            'financialStatements' => [
                'incomeStatements' => $this->containerFactory->createIncomeStatementContainer($company),
                'balanceSheets' => $this->containerFactory->createBalanceSheetContainer($company),
                'cashFlows' => $this->containerFactory->createCashFlowContainer($company),
            ],
        ]);
    }

    public function createLog(string $action, string $payload): CompanyLog
    {
        try {
            $companyLog = $this->companies->createLog($action, $payload);
            $this->redisService->publishCompanyLogCreated($action, $payload);

            return $companyLog;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
