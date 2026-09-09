const API_BASE = '/api';
const TOKEN_KEY = 'laravel_task_token';

export function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

export function setToken(token) {
    localStorage.setItem(TOKEN_KEY, token);
}

export function clearToken() {
    localStorage.removeItem(TOKEN_KEY);
}

async function request(endpoint, options = {}) {
    const token = getToken();

    const config = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
            ...options.headers,
        },
        ...options,
    };

    if (config.body && typeof config.body === 'object') {
        config.body = JSON.stringify(config.body);
    }

    const response = await fetch(`${API_BASE}${endpoint}`, config);

    if (!response.ok) {
        if (response.status === 401) {
            clearToken();
        }
        const error = await response.json().catch(() => ({}));
        throw new Error(error.message || `HTTP error! status: ${response.status}`);
    }

    return response.json();
}

export const api = {
    users: {
        list: () => request('/users'),
        create: (data) => request('/users', { method: 'POST', body: data }),
        login: (data) => request('/login', { method: 'POST', body: data }),
        me: () => request('/user'),
        logout: () => request('/logout', { method: 'POST' }),
    },
    tasks: {
        list: (userId, params = {}) => {
            const query = new URLSearchParams(params).toString();
            return request(`/users/${userId}/tasks${query ? `?${query}` : ''}`);
        },
        create: (userId, data) => request(`/users/${userId}/tasks`, { method: 'POST', body: data }),
        update: (userId, taskId, data) => request(`/users/${userId}/tasks/${taskId}`, { method: 'PUT', body: data }),
        complete: (userId, taskId) => request(`/users/${userId}/tasks/${taskId}/complete`, { method: 'PATCH' }),
        delete: (userId, taskId) => request(`/users/${userId}/tasks/${taskId}`, { method: 'DELETE' }),
    },
};