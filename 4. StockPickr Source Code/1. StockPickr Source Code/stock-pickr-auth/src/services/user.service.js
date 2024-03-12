const jwt = require('jsonwebtoken');
const bcrypt = require('bcryptjs');
const config = require('../../config');
const ValidationError = require('../errors/validation.error');
const UserRepository = require('../repositories/user.repository');

class UserService {
    constructor() {
        this.users = new UserRepository();
    }

    async register(name, email, password, passwordConfirm) {
        await this.validateRegistration(name, email, password, passwordConfirm);
        const user = await this.users.create(name, email, password);
        await this.updateToken(user, config.tokenKey);

        return user;
    }

    async update(user, name, password, passwordConfirm) {
        if (!name) {
            throw new ValidationError('All fields are required');
        }

        this.validatePasswordConfirm(password, passwordConfirm);

        return await this.users.update(user, name, password);
    }

    async login(email, password) {
        const user = await this.users.findByEmail(email);
        await this.validateLogin(user, password);

        const token = await this.updateToken(user, config.tokenKey);

        return {
            user,
            token
        };
    }

    async logout(token) {
        await this.users.removeToken(token);
    }

    async checkToken(token) {
        await this.users.findByTokenOrFail(token);
    }

    async delete(user, password) {
        await this.validateLogin(user, password);
        await this.users.delete(user);
    }

    async updateToken(user, tokenKey) {
        const token = jwt.sign({ user_id: user.id, email: user.email }, tokenKey, { expiresIn: "2h" });
        await this.users.updateToken(user, token);

        return token;
    }

    async validateLogin(user, password) {
        if (!user || !await bcrypt.compare(password, user.password)) {
            throw new ValidationError('Incorrect credentials');
        }
    }

    async validateRegistration(name, email, password, passwordConfirm) {
        if (!(name && email && password)) {
            throw new ValidationError('All fields are required');
        }

        this.validatePasswordConfirm(password, passwordConfirm);

        const existingUser = await this.users.findByEmail(email);
        if (existingUser) {
            throw new ValidationError('This e-mail is already registered');
        }
    }

    validatePasswordConfirm(password, passwordConfirm) {
        if (password && password !== passwordConfirm) {
            throw new ValidationError('Password and password confirmation are different');
        }
    }
}

module.exports = UserService;