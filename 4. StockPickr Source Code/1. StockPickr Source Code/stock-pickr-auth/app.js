const { sequelize } = require('./src/config/mysql.connection');
const config = require('./config');
const express = require('express');
const UserResource = require('./src/resources/user.resource');
const UserService = require('./src/services/user.service');
const UserRepository = require('./src/repositories/user.repository');
const ValidationError = require('./src/errors/validation.error');
const ModelNotFoundError = require('./src/errors/model-not-found.error');

const app = express();
app.use(express.json());

const userService = new UserService();
const users = new UserRepository();

app.post('/api/v1/users', async(req, res) => {
    try {
        const { name, email, password, passwordConfirm } = req.body;
        const user = await userService.register(name, email, password, passwordConfirm);

        res.status(201).json({
            data: (new UserResource(user)).toObject()
        });
    } catch (err) {
        if (err instanceof ValidationError) {
            res.status(422).json({ errors: [err.message] });
            return;
        }

        res.status(500).json({ errors: ['Something went wrong'] });
    }
});

app.patch('/api/v1/users/:id', async(req, res) => {
    try {
        const user = await users.findByIdOrFail(req.params.id);
        const { name, password, passwordConfirm } = req.body;
        await userService.update(user, name, password, passwordConfirm);

        res.status(204).json({});
    } catch (err) {
        if (err instanceof ValidationError) {
            res.status(422).json({ errors: [err.message] });
            return;
        }

        if (err instanceof ModelNotFoundError) {
            res.status(404).json({ errors: [err.message] });
            return;
        }

        res.status(500).json({ errors: ['Something went wrong'] });
    }
});

app.delete('/api/v1/users/:id', async(req, res) => {
    try {
        const user = await users.findByIdOrFail(req.params.id);
        const { password } = req.body;
        await userService.delete(user, password);

        res.status(204).json({});
    } catch (err) {
        if (err instanceof ValidationError) {
            res.status(422).json({ errors: [err.message] });
            return;
        }

        if (err instanceof ModelNotFoundError) {
            res.status(404).json({ errors: [err.message] });
            return;
        }

        console.log(err);
        res.status(500).json({ errors: ['Something went wrong'] });
    }
});

app.post('/api/v1/auth/login', async(req, res) => {
    try {
        const { email, password } = req.body;
        const data = await userService.login(email, password);

        res.status(201).json({
            data: {
                user: (new UserResource(data.user)).toObject(),
                token: data.token
            }
        });
    } catch (err) {
        if (err instanceof ValidationError) {
            res.status(422).json({ errors: [err.message] });
            return;
        }

        res.status(500).json({ errors: ['Something went wrong'] });
    }
});

app.delete('/api/v1/auth/logout', async(req, res) => {
    try {
        await userService.logout(req.headers['authorization'].substring(7));

        res.status(204).json({});
    } catch (err) {
        res.status(500).json({ errors: ['Something went wrong'] });
    }
});

app.get('/api/v1/auth/check', async(req, res) => {
    try {
        await userService.checkToken(req.headers['authorization'].substring(7));

        res.status(204).json({});
    } catch (err) {
        res.status(404).json({ errors: ['Invalid token'] });
    }
});

app.listen(config.port, async() => {
    console.log('Listening on ' + config.port);
    await sequelize.sync();
});

module.exports = app;