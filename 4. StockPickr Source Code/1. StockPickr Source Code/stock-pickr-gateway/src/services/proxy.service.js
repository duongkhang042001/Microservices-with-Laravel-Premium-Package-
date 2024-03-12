const qs = require('qs');

class ProxyService {
    constructor(config) {
        this.config = config;
    }

    mapService(path) {
        if (path.includes('company-provider')) {
            return this.config.services['company-provider'].host;
        }

        if (path.includes('companies')) {
            return this.config.services.companies.host;
        }

        if (path.includes('share-data') || path.includes('analyst')) {
            return this.config.services['market-data'].host;
        }

        if (path.includes('charts')) {
            return this.config.services.charts.host;
        }

        if (path.includes('queries')) {
            return this.config.services.queries.host;
        }

        if (path.includes('auth') || path.includes('users')) {
            return this.config.services.auth.host;
        }

        if (path.includes('metrics') || path.includes('scores')) {
            return this.config.services.metrics.host;
        }

        throw Error('No service found for path: ' + path);
    }

    getFullUrl(host, path) {
        if (host.endsWith('/') && path.startsWith('/')) {
            return host + (path.slice(1));
        }

        return host + path;
    }

    isGuestRoute(method, path) {
        if (path.includes('company-provider')) {
            return true;
        }

        return !!this
            .getGuestRoutes()
            .find(r =>
                r.method === method &&
                (r.path === path || r.path === '*')
            );
    }

    getGuestRoutes() {
        return [
            { method: 'POST', path: '/api/v1/auth/login' },
            { method: 'POST', path: '/api/v1/users' },
            { method: 'OPTIONS', path: '*' }
        ];
    }

    /**
     * Load balancertől érkező http://host:port/ típusú healtcheck
     * @param {string} path
     * @returns bool
     */
    isHealthCheck(path) {
        return path === '/';
    }
}

module.exports = ProxyService;
