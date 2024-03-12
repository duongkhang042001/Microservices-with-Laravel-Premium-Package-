import axios from "axios";
import { User } from "@/models/User";
import store from '@/store/Store';
import { Nullable } from "@/global";
import ExceptionFactory from "@/exceptions/ExceptionFactory";
import { ExceptionTypes } from "@/exceptions/ExceptionTypes";
import HttpService from "./HttpService";

export default class AuthService {
    async signIn(email: string, password: string): Promise<Nullable<User>> {
        if (email === '' || password === '') {
            throw ExceptionFactory.create(ExceptionTypes.Validation, 'Sign in failed', { email: ['Please provide an e-mail and password'] });
        }

        try {
            const data = (await axios.post('auth/login', {
                email, password
            })).data.data;

            const user = new User(data.user, data.token);
            store.commit('setUser', user);
            store.commit('setToken', data.token);

            return user;
        } catch (err) {
            throw ExceptionFactory.createFromResponse(err?.response, 'Sign in failed');
        }
    }

    async signUp(name: string, email: string, password: string, passwordConfirm: string): Promise<Nullable<User>> {
        try {
            (await axios.post('users', {
                name, email, password, passwordConfirm
            })).data.data;

            return await this.signIn(email, password);
        } catch (err) {
            throw ExceptionFactory.createFromResponse(err?.response, 'Sign up failed');
        }
    }

    async signOut() {
        await HttpService.delete('auth/logout');

        store.commit('setUser', null);
        store.commit('setToken', null);
    }
}
