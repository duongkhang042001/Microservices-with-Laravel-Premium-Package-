const config = {
    port: process.env.PORT,
    redis: {
        host: process.env.REDIS_HOST,
        port: process.env.REDIS_PORT,
        username: process.env.REDIS_USERNAME,
        password: process.env.REDIS_PASSWORD,
        database: process.env.REDIS_DATABASE,
    },
    services: {
        auth: {
            host: process.env.AUTH_SERVICE_HOST
        },
        companies: {
            host: process.env.COMPANIES_SERVICE_HOST
        },
        'market-data': {
            host: process.env.MARKET_DATA_SERVICE_HOST
        },
        charts: {
            host: process.env.CHARTS_SERVICE_HOST
        },
        metrics: {
            host: process.env.METRICS_SERVICE_HOST
        },
        queries: {
            host: process.env.QUERIES_SERVICE_HOST
        },
        'company-provider': {
            host: process.env.COMPANY_PROVIDER_SERVICE_HOST
        },
    }
};

module.exports = config;
