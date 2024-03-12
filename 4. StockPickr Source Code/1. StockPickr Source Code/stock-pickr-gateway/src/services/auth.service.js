const axios = require('axios');

class AuthService {
    constructor(config) {
        this.config = config;
    }

    async check(token) {
        await axios({
            baseURL: `${this.config.services.auth.host}`,
            url: '/api/v1/auth/check',
            method: 'GET',
            headers: {
                'Authorization': token
            }
        });
    }
}

module.exports = AuthService;
