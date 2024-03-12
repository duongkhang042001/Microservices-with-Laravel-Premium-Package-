<?php

namespace StockPickr\Common\Events;

abstract class Events
{
    const CREATE_COMPANY = 'company:create:v1';
    const UPDATE_COMPANY = 'company:update:v1';
    const REMOVE_COMPANY = 'company:remove:v1';
    const COMPANY_CREATED = 'company:created:v1';
    const COMPANY_UPDATED = 'company:updated:v1';
    const COMPANY_CREATE_FAILED = 'company:create:failed:v1';
    const COMPANY_UPDATE_FAILED = 'company:update:failed:v1';
    const COMPANY_LOG_CREATED = 'company:log:created:v1';

    const SCORE_COMPANIES = 'company:score:v1';
    // Ez a két esemény a teljes pontozás folyamatára vonatkozik
    const COMPANY_SCORE_SUCCEEDED = 'company:score:succeeded:v1';
    const COMPANY_SCORE_FAILED = 'company:score:failed:v1';
    // Ez az esemény, csak egy adott cég pontozására vonatkozik
    const COMPANY_SCORED = 'company:scored:v1';

    const CREATE_MARKET_DATA = 'market-data:create:v1';
    const MARKET_DATA_CREATED = 'market-data:created:v1';
    const MARKET_DATA_CREATE_FAILED = 'market-data:create:failed:v1';

    const UPDATE_MARKET_DATA = 'market-data:update:v1';
    const MARKET_DATA_UPDATED = 'market-data:updated:v1';
    const MARKET_DATA_UPDATE_FAILED = 'market-data:update:failed:v1';

    const METRICS_CREATED = 'metrics:created:v1';
    const METRICS_UPDATED = 'metrics:updated:v1';
    const METRICS_CREATE_FAILED = 'metrics:create:failed:v1';
    const METRICS_UPDATE_FAILED = 'metrics:update:failed:v1';

    const USER_CREATED = 'user:created:v1';
    const USER_UPDATED = 'user:updated:v1';
    const USER_DELETED = 'user:deleted:v1';

    const CHARTS_CREATE_FAILED = 'charts:create:failed:v1';
}