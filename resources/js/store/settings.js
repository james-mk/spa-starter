import { defineStore } from 'pinia';
import api from '@/plugins/axios';

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        settings: null,
        loading: false,
    }),

    getters: {
        companyName: (state) => state.settings?.company_name || 'Fleet Management',
        companyLogo: (state) => state.settings?.company_logo_url || null,
        companyInitials: (state) => state.settings?.company_initials || 'FM',
        theme: (state) => state.settings?.theme || 'light',
        primaryColor: (state) => state.settings?.primary_color || '#828282',
        secondaryColor: (state) => state.settings?.secondary_color || '#1e448f',
    },

    actions: {
        async fetchSettings() {
            this.loading = true;
            try {
                const { data } = await api.get('/settings');
                this.settings = data;

                // Cache settings in localStorage for faster next load
                localStorage.setItem('app_settings', JSON.stringify({
                    theme: data.theme,
                    primary_color: data.primary_color,
                    secondary_color: data.secondary_color,
                    company_name: data.company_name,
                }));

                return data;
            } catch (error) {
                console.error('Error fetching settings:', error);
                return {
                    company_name: 'Fleet Management',
                    theme: 'light',
                    primary_color: '#828282',
                    secondary_color: '#1e448f',
                };
            } finally {
                this.loading = false;
            }
        },

        async updateSettings(settingsData) {
            this.loading = true;
            try {
                const { data } = await api.post('/settings', settingsData);
                this.settings = data;

                // Update localStorage cache
                localStorage.setItem('app_settings', JSON.stringify({
                    theme: data.theme,
                    primary_color: data.primary_color,
                    secondary_color: data.secondary_color,
                    company_name: data.company_name,
                }));

                return data;
            } catch (error) {
                console.error('Error updating settings:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
    },
});
