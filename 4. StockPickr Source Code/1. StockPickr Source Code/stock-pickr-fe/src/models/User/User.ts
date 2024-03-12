export class User {
    public id: string;
    public name: string;
    public email: string;
    public token: string;

    constructor (params: User = {} as User, token: string) {
        const {
            id,
            name,
            email
        } = params;

        this.id = id;
        this.name = name;
        this.email = email;
        this.token = token;
    }

    setName(name: string) {
        this.name = name;
    }

    setToken(token: string) {
        this.token = token;
    }
}
