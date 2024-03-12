const chai = require('chai');
const chaiHttp = require('chai-http');
const app = require('../app');
const User = require('../src/models/user.model');
const UserRepository = require('../src/repositories/user.repository');
const should = chai.should();
const bcrypt = require('bcryptjs');

chai.use(chaiHttp);

describe('Update Users', () => {
    const users = new UserRepository();
    beforeEach(async() => {
        await User.destroy({
            where: {},
            truncate: true
        });
    });

    describe('PATCH /users', () => {
        it('should return 404 if user not found', (done) => {
            const user = {
                name: 'User User',
                email: 'user@gmail.com',
                password: 'password',
                passwordConfirm: 'password',
            };

            chai.request(app)
                .patch('/api/v1/users/100')
                .send(user)
                .end((err, res) => {
                    res.should.have.status(404);
                    res.body.should.have.property('errors');
                    done();
                });
        });
        it('should return 422 if name is missing', async() => {
            const user = await users.create('User Name', 'user@gmail.com', 'password');
            const res = await chai.request(app)
                .patch(`/api/v1/users/${user.id}`)
                .send({
                    name: '',
                    email: 'user@gmail.com',
                    password: 'password',
                    passwordConfirm: 'password',
                });

            res.should.have.status(422);
            res.body.should.have.property('errors');
        });
        it('should return 422 if password confirm different', async() => {
            const user = await users.create('User Name', 'user@gmail.com', 'password');
            const res = await chai.request(app)
                .patch(`/api/v1/users/${user.id}`)
                .send({
                    name: 'User Name',
                    email: 'user@gmail.com',
                    password: 'password',
                    passwordConfirm: 'not-matching',
                });

            res.should.have.status(422);
            res.body.should.have.property('errors');
        });
        it('should return 204', async() => {
            const user = await users.create('User Name', 'user@gmail.com', 'password');
            const res = await chai.request(app)
                .patch(`/api/v1/users/${user.id}`)
                .send({
                    name: 'User Name',
                    email: 'user@gmail.com',
                    password: 'password',
                    passwordConfirm: 'password',
                });

            res.should.have.status(204);
        });
        it('should only update name if no password given', async() => {
            const user = await users.create('User Name', 'user@gmail.com', 'password');
            const res = await chai.request(app)
                .patch(`/api/v1/users/${user.id}`)
                .send({
                    name: 'User Name Updated',
                    email: 'user@gmail.com',
                });

            res.should.have.status(204);
            const updatedUser = await User.findByPk(user.id);
            updatedUser.name.should.be.equal('User Name Updated');

            const isSame = await bcrypt.compare('password', updatedUser.password)
            isSame.should.be.eq(true);
        });
        it('should only update name if no password given', async() => {
            const user = await users.create('User Name', 'user@gmail.com', 'password');
            const res = await chai.request(app)
                .patch(`/api/v1/users/${user.id}`)
                .send({
                    name: 'User Name',
                    password: 'new-password',
                    passwordConfirm: 'new-password',
                });

            res.should.have.status(204);
            const updatedUser = await User.findByPk(user.id);
            const isSame = await bcrypt.compare('new-password', updatedUser.password)
            isSame.should.be.eq(true);
        });
    });
});