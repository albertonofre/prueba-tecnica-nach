import { defineStore } from 'pinia';
import { api, getToken, setToken, clearToken } from '../api/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        ready: false,
        loading: false,
        error: null,
    }),

    getters: {
        isAuthenticated: (state) => state.user !== null,
        userName: (state) => state.user?.name ?? '',
        userEmail: (state) => state.user?.email ?? '',
    },

    actions: {
        async login(credentials) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.users.login(credentials);
                setToken(response.data.token);
                this.user = response.data.user;
                return response.data;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                if (getToken()) {
                    await api.users.logout();
                }
            } catch (error) {
                // La sesión se limpia localmente igualmente.
            }
            clearToken();
            this.user = null;
        },

        async restoreSession() {
            if (this.ready) return;

            try {
                if (getToken()) {
                    const response = await api.users.me();
                    this.user = response.data;
                }
            } catch (error) {
                clearToken();
                this.user = null;
            } finally {
                this.ready = true;
            }
        },
    },
});