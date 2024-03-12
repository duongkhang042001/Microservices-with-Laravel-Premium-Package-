import axios from "axios";
import { Nullable } from '@/global';
import { User } from '@/models/User';
import { createStore } from 'vuex';
import createPersistedState from 'vuex-persistedstate';

interface State {
    user: Nullable<User>;
}

const store = createStore({
    state(): State {
        return {
            user: null
        };
    },
    mutations: {
        setUser(state: State, user: User): void {
            state.user = user;
        },
        setUserName(state: State, name: string): void {
            state.user?.setName(name);
        },
        setToken(state: State, token: string): void {
            state.user?.setToken(token);
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        }
    },
    getters: {
        getUser(state: State): Nullable<User> {
            return state.user;
        },
        isSignedIn(state): boolean {
            return state.user !== null;
        },
        getToken(state: State): Nullable<string> {
            return state.user?.token;
        }
    },
    plugins: [createPersistedState({
        storage: window.sessionStorage
    })]
});

export default store;
