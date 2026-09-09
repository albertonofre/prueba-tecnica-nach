import { defineStore } from 'pinia';
import { api } from '../api/api';

export const useDashboardStore = defineStore('dashboard', {
    state: () => ({
        users: [],
        selectedUser: null,
        tasks: [],
        loading: false,
        error: null,
        requestSeq: 0,
        filters: {
            completed: null,
            orderBy: 'created_at',
            orderDirection: 'desc',
        },
    }),

    getters: {
        hasUsers: (state) => state.users.length > 0,
        selectedUserId: (state) => state.selectedUser?.id ?? null,
    },

    actions: {
        async fetchUsers() {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.users.list();
                this.users = response.data;
            } catch (error) {
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },

        async createUser(userData) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.users.create(userData);
                return response.data.user;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async selectUser(user) {
            this.selectedUser = user;
            if (user) {
                await this.fetchTasks();
            } else {
                this.tasks = [];
            }
        },

        async fetchTasks() {
            if (!this.selectedUser) return;

            const seq = ++this.requestSeq;
            this.loading = true;
            this.error = null;
            try {
                const params = {
                    ...(this.filters.completed !== null && { completed: this.filters.completed }),
                    order_by: this.filters.orderBy,
                    order_direction: this.filters.orderDirection,
                };
                const response = await api.tasks.list(this.selectedUser.id, params);
                if (seq !== this.requestSeq) return;
                this.tasks = response.data;
            } catch (error) {
                if (seq !== this.requestSeq) return;
                this.error = error.message;
            } finally {
                if (seq === this.requestSeq) {
                    this.loading = false;
                }
            }
        },

        async createTask(taskData) {
            if (!this.selectedUser) return;

            this.loading = true;
            this.error = null;
            try {
                const response = await api.tasks.create(this.selectedUser.id, taskData);
                this.tasks.unshift(response.data);
                return response.data;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateTask(taskId, taskData) {
            if (!this.selectedUser) return;

            this.loading = true;
            this.error = null;
            try {
                const response = await api.tasks.update(this.selectedUser.id, taskId, taskData);
                const updated = response.data;
                const index = this.tasks.findIndex((t) => t.id === taskId);
                if (index !== -1) {
                    if (this.matchesFilter(updated)) {
                        this.tasks[index] = updated;
                    } else {
                        this.tasks.splice(index, 1);
                    }
                }
                return updated;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async completeTask(taskId) {
            if (!this.selectedUser) return;

            this.loading = true;
            this.error = null;
            try {
                const response = await api.tasks.complete(this.selectedUser.id, taskId);
                const updated = response.data;
                const index = this.tasks.findIndex((t) => t.id === taskId);
                if (index !== -1) {
                    if (this.matchesFilter(updated)) {
                        this.tasks[index] = updated;
                    } else {
                        this.tasks.splice(index, 1);
                    }
                }
                return updated;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteTask(taskId) {
            if (!this.selectedUser) return;

            this.loading = true;
            this.error = null;
            try {
                await api.tasks.delete(this.selectedUser.id, taskId);
                this.tasks = this.tasks.filter((t) => t.id !== taskId);
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        setFilter(key, value) {
            this.filters[key] = value === 'all' ? null : value;
            this.fetchTasks();
        },

        matchesFilter(task) {
            return this.filters.completed === null || task.completed === this.filters.completed;
        },
    },
});