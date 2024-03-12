const bcrypt = require('bcryptjs');
const ModelNotFoundError = require('../errors/model-not-found.error');
const User = require('../models/user.model');

class UserRepository {
    async create(name, email, password) {
        const encryptedPassword = await bcrypt.hash(password, 10);

        return await User.create({
            name,
            email,
            password: encryptedPassword
        });
    }

    async update(user, name, password) {
        user.name = name;

        if (password) {
            user.password = await bcrypt.hash(password, 10);
        }

        return await user.save();
    }

    async updateToken(user, token) {
        user.token = token;
        return await user.save();
    }

    async findByEmail(email) {
        return await User.findOne({ where: { email } });
    }

    async findByIdOrFail(id) {
        const user = await User.findByPk(id);
        if (!user) {
            throw new ModelNotFoundError('User not found for id ' + id);
        }

        return user;
    }

    async delete(user) {
        await user.destroy();
    }

    async removeToken(token) {
        await User.update({ token: null }, {
            where: {
                token
            }
        });
    }

    async findByTokenOrFail(token) {
        const user = await User.findOne({
            where: {
                token
            }
        });

        if (!user) {
            throw new ModelNotFoundError('User not found for token ' + token);
        }

        return token;
    }
}

module.exports = UserRepository;