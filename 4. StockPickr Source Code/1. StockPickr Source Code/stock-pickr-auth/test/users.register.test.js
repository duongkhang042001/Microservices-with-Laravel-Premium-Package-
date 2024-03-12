const chai = require('chai');
const chaiHttp = require('chai-http');
const app = require('../app');
const User = require('../src/models/user.model');
const should = chai.should();
const config = require('../config');

chai.use(chaiHttp);

describe('Register Users', () => {
    beforeEach(async() => {
        await User.destroy({
            where: {},
            truncate: true
        });
    });

    describe('POST /users', () => {
        it('should create a user', (done) => {
            const user = {
                name: 'User User',
                email: 'user@gmail.com',
                password: 'password',
                passwordConfirm: 'password',
            };

            chai.request(app)
                .post('/api/v1/users')
                .send(user)
                .end((err, res) => {
                    res.should.have.status(201);
                    res.body.data.should.be.a('object');
                    res.body.data.email.should.be.equal('user@gmail.com');
                    res.body.data.should.have.property('token');
                    done();
                });
        });

        it('should return 422 if email is missing', (done) => {
            const user = {
                name: 'User User',
                email: '',
                password: 'password',
                passwordConfirm: 'password',
            };

            chai.request(app)
                .post('/api/v1/users')
                .send(user)
                .end((err, res) => {
                    res.should.have.status(422);
                    done();
                });
        });

        it('should return 422 if password is missing', (done) => {
            const user = {
                name: 'User User',
                email: 'user@gmail.com',
                password: '',
                passwordConfirm: 'password',
            };

            chai.request(app)
                .post('/api/v1/users')
                .send(user)
                .end((err, res) => {
                    res.should.have.status(422);
                    done();
                });
        });

        it('should return 422 if password confirm is different', (done) => {
            const user = {
                name: 'User User',
                email: 'user@gmail.com',
                password: 'password',
                passwordConfirm: 'not-matching',
            };

            chai.request(app)
                .post('/api/v1/users')
                .send(user)
                .end((err, res) => {
                    res.should.have.status(422);
                    done();
                });
        });
    });
});