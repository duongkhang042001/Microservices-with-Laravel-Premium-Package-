class UserResource {
    constructor(user) {
        this.user = user;
    }

    toObject() {
        return {
            id: this.user.id,
            name: this.user.name,
            email: this.user.email,
            token: this.user.token
        };
    }
}

module.exports = UserResource;