import ExceptionFactory from '@/exceptions/ExceptionFactory';
import { User } from '@/models/User';
import store from '@/store/Store';
import HttpService from './HttpService';

export default class UserService {
    async updateUser(id: string, name: string, password: string, passwordConfirm: string): Promise<void> {
        try {
            const data = password
                ? { name, password, passwordConfirm }
                : { name };

            await HttpService.patch(`users/${id}`, data);
            store.commit('setUserName', name);
        } catch (err) {
            throw ExceptionFactory.createFromResponse(err?.response, 'Failed to edit profile');
        }
    }

    async deleteUser(password: string): Promise<void> {
        const user: User = store.getters.getUser;

        try {
            await HttpService.delete(`users/${user.id}`, {
                password
            });

            store.commit('setUser', null);
            store.commit('setToken', null);
        } catch (err) {
            throw ExceptionFactory.createFromResponse(err?.response, 'Account deletion failed');
        }
    }
}
