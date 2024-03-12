<?php

namespace App\Services\Company;

use App\Exceptions\InvalidCompanyException;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use StockPickr\Common\Containers\UpsertCompanyContainer;

class CompanyValidatorService
{
    /**
     * @throws InvalidCompanyException
     */
    public function validate(UpsertCompanyContainer $company)
    {
        $companyValidator = Validator::make($company->toArray(), [
            'ticker' => ['required', 'string', 'between:1,6'],
            'name' => ['required', 'string'],
            'sector' => ['required', 'string'],
        ]);

        if ($companyValidator->fails()) {
            throw new InvalidCompanyException(
                "$company->ticker cannot be installed: " . $companyValidator->errors()->toJson()
            );
        }

        if (Str::contains($company->ticker, '.')) {
            throw new InvalidCompanyException($company->ticker . ' is probably not a US company');
        }

        $this->validateStatements($company);
    }

    private function validateStatements(UpsertCompanyContainer $company)
    {
        $this->rejectEmptyStatement($company);
        $this->validateIncomeStatements($company);
        $this->validateBalanceSheets($company);
        $this->validateCashFlows($company);
    }

    /**
     * @throws InvalidCompanyException
     */
    private function rejectEmptyStatement(UpsertCompanyContainer $company): void
    {
        if (empty($company->getIncomeStatements())
            || empty($company->getBalanceSheets())
            || empty($company->getCashFlows())) {
            throw new InvalidCompanyException(
                "$company->ticker cannot be installed. Missing financial statement"
            );
        }
    }

    /**
     * @throws InvalidCompanyException
     */
    private function validateIncomeStatements(UpsertCompanyContainer $company): void
    {
        foreach ($company->getIncomeStatements() as $year => $incomeStatement) {
            $incomeValidator = Validator::make($incomeStatement, [
                'totalRevenue' => ['required', 'numeric', Rule::notIn([0, '0'])],
                'grossProfit' => ['required', 'numeric', Rule::notIn([0, '0'])],
                'ebit' => ['required', 'numeric', Rule::notIn([0, '0'])],
                'operatingIncome' => ['required', 'numeric', Rule::notIn([0, '0'])],
                'netIncome' => ['required', 'numeric', Rule::notIn([0, '0'])],
            ]);

            $this->throwStatementException($company, $year, $incomeValidator);
        }
    }
    /**
     * @throws InvalidCompanyException
     */
    private function validateBalanceSheets(UpsertCompanyContainer $company): void
    {
        foreach ($company->getBalanceSheets() as $year => $balanceSheet) {
            $balanceValidator = Validator::make($balanceSheet, [
                'totalAssets' => ['required', 'numeric', Rule::notIn([0, '0'])],
                'totalEquity' => ['required', 'numeric', Rule::notIn([0, '0'])],
                'cash' => ['required', 'numeric', Rule::notIn([0, '0'])],
            ]);

            $this->throwStatementException($company, $year, $balanceValidator);
        }
    }

    /**
     * @throws InvalidCompanyException
     */
    private function validateCashFlows(UpsertCompanyContainer $company): void
    {
        foreach ($company->getCashFlows() as $year => $cashFlow) {
            $cashFlowValidator = Validator::make($cashFlow, [
                'operatingCashFlow' => ['required', 'numeric'],
                'freeCashFlow' => ['required', 'numeric'],
                'capex' => ['required', 'numeric'],
            ]);

            $this->throwStatementException($company, $year, $cashFlowValidator);
        }
    }

    /**
     * @throws InvalidCompanyException
     */
    private function throwStatementException(
        UpsertCompanyContainer $company,
        int $year,
        ValidatorContract $validator
    ): void {
        if ($validator->fails()) {
            throw new InvalidCompanyException(
                "$company->ticker cannot be installed. Lack of data in income statement for $year: " . $validator->errors()->toJson()
            );
        }
    }
}
