import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';

// Fetch settings synchronously from localStorage
let savedTheme = 'light';
let savedPrimaryColor = '#1976d2';
let savedSecondaryColor = '#1e448f';

// Try to get from localStorage first (set during login or settings save)
const cachedSettings = localStorage.getItem('app_settings');
if (cachedSettings) {
    try {
        const settings = JSON.parse(cachedSettings);
        savedTheme = settings.theme || 'light';
        savedPrimaryColor = settings.primary_color || '#1976d2';
        savedSecondaryColor = settings.secondary_color || '#1e448f';
    } catch (e) {
        console.error('Error parsing cached settings:', e);
    }
}

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: savedTheme,
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: savedPrimaryColor,
                    secondary: savedSecondaryColor,
                    accent: '#82B1FF',
                    error: '#FF5252',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FFC107',
                    background: '#F5F5F5',
                    surface: '#FFFFFF',
                },
            },
            dark: {
                dark: true,
                colors: {
                    primary: savedPrimaryColor,
                    secondary: savedSecondaryColor,
                    accent: '#82B1FF',
                    error: '#FF5252',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FFC107',
                    background: '#121212',
                    surface: '#1E1E1E',
                },
            },
        },
    },
});

export default vuetify;
