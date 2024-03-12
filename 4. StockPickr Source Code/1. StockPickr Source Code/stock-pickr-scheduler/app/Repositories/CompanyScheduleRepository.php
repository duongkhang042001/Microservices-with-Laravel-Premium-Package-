<?php

namespace App\Repositories;

use App\Models\CompanySchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\MarketData\MarketDataContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer;

class CompanyScheduleRepository
{
    public const STATE_IN_PROGRESS = 'in-progress';
    public const STATE_SUCCEEDED = 'succeeded';
    public const STATE_FAILED = 'failed';

    public const EVENT_CREATE_COMPANY = 'create-company';
    public const EVENT_UPDATE_COMPANY = 'update-company';
    public const EVENT_CREATE_MARKET_DATA = 'create-market-data';
    public const EVENT_UPDATE_MARKET_DATA = 'update-market-data';
    public const EVENT_SCORE_COMPANIES = 'score-companies';

    public const ALL_EVENTS = ['create-company', 'update-company', 'create-market-data', 'update-market-data', 'score-companies'];
    public const ALL_STATES = ['in-progress', 'succeeded', 'failed'];

    public function createForCompanyCreate(UpsertCompanyContainer $company): CompanySchedule
    {
        return $this->create($company->ticker, static::EVENT_CREATE_COMPANY, $company->toArray());
    }

    public function createForCompanyUpdate(UpsertCompanyContainer $company): CompanySchedule
    {
        return $this->create($company->ticker, static::EVENT_UPDATE_COMPANY, $company->toArray());
    }

    public function createForMarketDataCreate(string $ticker, MarketDataContainer $marketData): CompanySchedule
    {
        return $this->create($ticker, static::EVENT_CREATE_MARKET_DATA, $marketData->toArray());
    }

    public function createForMarketDataUpdate(string $ticker, MarketDataContainer $marketData): CompanySchedule
    {
        return $this->create($ticker, static::EVENT_UPDATE_MARKET_DATA, $marketData->getShareData()->toArray());
    }

    public function createForScoreCompanies(): CompanySchedule
    {
        return CompanySchedule::create([
            'event'         => static::EVENT_SCORE_COMPANIES,
            'started_at'    => now()
        ]);
    }

    public function companyCreateSucceeded(string $scheduleId, CompanyUpsertedContainer $company): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_SUCCEEDED, $company->toArray());
    }

    public function companyCreateFailed(string $scheduleId, string $message): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_FAILED, $message);
    }

    public function companyUpdateSucceeded(string $scheduleId, CompanyUpsertedContainer $company): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_SUCCEEDED, $company->toArray());
    }

    public function companyUpdateFailed(string $scheduleId, string $message): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_FAILED, $message);
    }

    public function marketDataCreated(string $scheduleId, MarketDataContainer $marketData): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_SUCCEEDED, $marketData->toArray());
    }

    public function marketDataCreateFailed(string $scheduleId, string $message): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_FAILED, $message);
    }

    public function marketDataUpdated(string $scheduleId, MarketDataContainer $marketData): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_SUCCEEDED, $marketData->toArray());
    }

    public function marketDataUpdateFailed(string $scheduleId, string $message): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_FAILED, $message);
    }

    public function scoreCompaniesSucceeded(string $scheduleId): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_SUCCEEDED);
    }

    public function scoreCompaniesFailed(string $scheduleId, string $message): CompanySchedule
    {
        $schedule = $this->getById($scheduleId);
        return $this->finish($schedule, now(), static::STATE_FAILED, $message);
    }

    public function metricsCreateFailed(string $ticker, string $message): CompanySchedule
    {
        $schedule = $this->getCreateCompanySchedule($ticker);
        return $this->finish($schedule, now(), static::STATE_FAILED, 'Metrics create failed ' . $message);
    }

    public function chartsCreateFailed(string $ticker, string $message): CompanySchedule
    {
        $schedule = $this->getCreateCompanySchedule($ticker);
        return $this->finish($schedule, now(), static::STATE_FAILED, 'Charts create failed ' . $message);
    }

    public function finish(CompanySchedule $schedule, Carbon $finishedAt, string $state, array | string $payload = null): CompanySchedule
    {
        $schedule->finished_at = $finishedAt;
        $schedule->state = $state;
        $schedule->payload = $payload;

        $schedule->save();
        return $schedule;
    }

    public function hasInProgressSchedule(): bool
    {
        return !CompanySchedule::where('state', static::STATE_IN_PROGRESS)
            ->count();
    }

    public function getStuckedSchedules(Carbon $treshold): Collection
    {
        return CompanySchedule::where('state', static::STATE_IN_PROGRESS)
            ->where('started_at', '<=', $treshold)
            ->get();
    }

    private function create(string $ticker, string $event, array $payload): CompanySchedule
    {
        return CompanySchedule::create([
            'ticker'        => $ticker,
            'event'         => $event,
            'payload'       => $payload,
            'started_at'    => now()
        ]);
    }

    private function getById(string $id): CompanySchedule
    {
        return CompanySchedule::where('id', $id)
            ->firstOrFail();
    }

    private function getCreateCompanySchedule(string $ticker): CompanySchedule
    {
        return CompanySchedule::where('ticker', $ticker)
            ->where('event', self::EVENT_CREATE_COMPANY)
            ->firstOrFail();
    }
}
