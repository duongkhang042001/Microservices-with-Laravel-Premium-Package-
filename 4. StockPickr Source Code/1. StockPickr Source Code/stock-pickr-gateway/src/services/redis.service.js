const { promisify } = require('util');
const redis = require('redis');

class RedisService {
    constructor(config) {
        this.client = redis.createClient({
            host: config.redis.host,
            port: config.redis.port,
            db: config.redis.database
        });
    }

    async setToken(token) {
        const hset = promisify(this.client.hset).bind(this.client);
        await hset('tokens', token, token);
    }

    async isTokenExists(token) {
        const hexists = promisify(this.client.hexists).bind(this.client);
        return !!(await hexists('tokens', token));
    }

    async removeTokens() {
        const del = promisify(this.client.del).bind(this.client);
        await del('tokens');
    }

    createConnectionString(config) {
        return `rediss://${config.redis.username}:${config.redis.password}@${config.redis.host}:${config.redis.port}`;
    }
}

module.exports = RedisService;