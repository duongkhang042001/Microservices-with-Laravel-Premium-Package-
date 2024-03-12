import axios from 'axios';
import store from '@/store/Store';

export default class HttpService {
    static async get(url: string) {
        try {
            const response = await axios({
                method: 'GET',
                url: url,
                headers: {
                    'Authorization': 'Bearer ' + store.getters.getToken
                },
            });

            return response.data.data;
        } catch (err) {
            console.log(err);
        }
    }

    static async post(url: string, body: object) {
        const response = await axios({
            method: 'POST',
            url: url,
            data: body,
            headers: {
                'Authorization': 'Bearer ' + store.getters.getToken
            },
        });

        return response.data.data;
    }

    static async delete(url: string, body: object = {}) {
        const response = await axios({
            method: 'DELETE',
            url: url,
            data: body,
            headers: {
                'Authorization': 'Bearer ' + store.getters.getToken
            },
        });

        return response.data.data;
    }

    static async patch(url: string, body: object) {
        const response = await axios({
            method: 'PATCH',
            url: url,
            data: body,
            headers: {
                'Authorization': 'Bearer ' + store.getters.getToken
            },
        });

        return response.data.data;
    }
}
