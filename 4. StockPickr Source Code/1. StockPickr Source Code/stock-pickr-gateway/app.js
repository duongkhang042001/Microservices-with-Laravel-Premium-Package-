const axios = require('axios');
const express = require('express');
const cors = require('cors');
const compression = require('compression');

const ProxyService = require('./src/services/proxy.service');
const AuthService = require('./src/services/auth.service');
const RedisService = require('./src/services/redis.service');
const RemoveTokensSchedule = require('./src/schedules/remove-tokens.schedule');
const config = require('./config');

const app = express();
app.use(express.json());
app.use(cors());
app.use(compression({
    threshold: 0
}));

const proxyService = new ProxyService(config);
const authService = new AuthService(config);
const redisService = new RedisService(config);
const removeTokensSchedule = new RemoveTokensSchedule(redisService);

removeTokensSchedule.run();

app.all('/*', async(req, res) => {
    try {
        if (proxyService.isHealthCheck(req.path)) {
            res.status(200).send({ status: 'ok' });
            return;
        }

        const path = req.path;
        const host = proxyService.mapService(path);
        const fullUrl = proxyService.getFullUrl(host, path);

        if (!proxyService.isGuestRoute(req.method, path)) {
            const token = req.headers['authorization'];

            if (token === undefined || token === null || token === '') {
                throw Error(`No token set when accessing ${req.method} ${path}`);
            }

            if (!(await redisService.isTokenExists(token))) {
                await authService.check(token);
                await redisService.setToken(token);
            }
        }

        const proxiedResponse = await axios({
            method: req.method,
            url: fullUrl,
            headers: req.headers,
            params: req.query,
            data: req.body
        });

        res
            .status(proxiedResponse.status)
            .send(proxiedResponse.data);
    } catch (err) {
        res.header('Content-Type', 'application/json');

        if (err.response) {
            res.status(err.response.status);
            return res.send(err.response.data);
        }

        res.status(500);
        res.send({
            errors: ['Something went wrong in gateway']
        });

        console.log(err);
    }
});

app.listen(config.port, () => console.log('Listening on port ' + config.port));
