<?php

namespace App\Repositories\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyLog;
use App\Models\Company\CompanyPeer;
use Illuminate\Support\Collection;
use StockPickr\Common\Containers\UpsertCompanyContainer;

class CompanyRepository
{
    public function __construct(
        private SectorRepository $sectors
    ) {}

    public function upsert(UpsertCompanyContainer $companyContainer): Company
    {
        $sector = $this->sectors->findByNameOrCreate($companyContainer->sector);
        $company = Company::firstOrNew([
            'ticker'    => $companyContainer->ticker
        ]);

        $company->ticker = $companyContainer->ticker;
        $company->name = $companyContainer->name;
        $company->description = $companyContainer->description;
        $company->employees = $companyContainer->employees;
        $company->ceo = $companyContainer->ceo;

        $company->sector_id = $sector->id;
        $company->industry = $companyContainer->industry;

        $company->save();
        return $company;
    }

    /**
     * @return Collection<CompanyPeer>
     */
    public function addPeers(Company $company, Collection $peers, int $count): Collection
    {
        if ($peers->isEmpty()) {
            return collect([]);
        }

        $existingPeers = $this->findAllByTickers($peers->all())
            ->take($count);

        $company->peers()->delete();

        return $existingPeers
            ->reject(fn (Company $peer) => $company->id === $peer->id)
            ->map(fn (Company $peer) => CompanyPeer::create([
                'company_id'    => $company->id,
                'peer_id'       => $peer->id,
                'ticker'        => $peer->ticker
            ]));
    }

    public function createLog(string $action, string $payload): CompanyLog
    {
        return CompanyLog::create([
            'action'    => $action,
            'payload'   => $payload
        ]);
    }

    public function firstOrFail(string $ticker): Company
    {
        return Company::where('ticker', $ticker)->firstOrFail();
    }

    public function delete(string $ticker): void
    {
        Company::where('ticker', $ticker)->delete();
    }

    private function findAllByTickers(array $tickers)
    {
        return Company::whereIn('ticker', $tickers)->get();
    }
}
