import { defineStore } from 'pinia';
import api from '../plugins/axios';

export const useAuthStore = defineStore('auth', {
state: () => ({
  user: JSON.parse(localStorage.getItem('user')) || null,
  token: localStorage.getItem('token') || null,
}),
getters: {
  isLoggedIn: (state) => !!state.token,
roles: (state) => state.user?.roles?.map(r => r.name) || [],
hasRole: (state) => (role) => state.user?.roles?.some(r => r.name === role) || false,
},
  actions: {
    async login(email, password) {
      const { data } = await api.post('/login', { email, password });
      this.user = data.user;
      this.token = data.token;
      localStorage.setItem('token', data.token);
      localStorage.setItem('user', JSON.stringify(data.user));
    },
 async logout(silent = false) {
  try {
    if (!silent && this.token) {
      await api.post('/logout');
    }
  } catch (e) {
    // Ignore any logout errors (like 401)
  } finally {
    this.token = null;
    this.user = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');

    // Only redirect if this isn’t a silent logout
    if (!silent) {
      window.location.href = '/login';
    }
  }
},
    async fetchUser() {
      if (!this.token) return;
      const { data } = await api.get('/me');
      this.user = data;
      localStorage.setItem('user', JSON.stringify(data));
    },
  },
});
