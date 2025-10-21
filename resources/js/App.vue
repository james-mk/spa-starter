<template>
    <component :is="layout">
        <router-view />
        <LoadingScreen />
    </component>
</template>

<script setup>
import { onMounted, computed } from "vue";
import { useAuthStore } from "./store/auth";
import { useRoute } from "vue-router";
import DefaultLayout from "./layouts/DefaultLayout.vue";
import AuthLayout from "./layouts/AuthLayout.vue";
import { useSettingsStore } from "./store/settings";
import { useTheme } from "vuetify";
import LoadingScreen from "./components/LoadingScreen.vue";

const auth = useAuthStore();
const route = useRoute();
const settingsStore = useSettingsStore();
const theme = useTheme();

if (auth.token) {
    auth.fetchUser().catch(() => {
        auth.logout();
        window.location.href = "/login";
    });
}

const layout = computed(() => {
    return route.meta.layout === "auth" ? AuthLayout : DefaultLayout;
});

onMounted(async () => {
    // Fetch fresh settings from database
    try {
        const data = await settingsStore.fetchSettings();

        // Apply theme
        if (data.theme) {
            theme.global.name.value = data.theme;
        }

        // Apply primary and secondary colors to both themes
        if (data.primary_color) {
            theme.themes.value.light.colors.primary = data.primary_color;
            theme.themes.value.dark.colors.primary = data.primary_color;
        }

        if (data.secondary_color) {
            theme.themes.value.light.colors.secondary = data.secondary_color;
            theme.themes.value.dark.colors.secondary = data.secondary_color;
        }
    } catch (error) {
        console.error("Failed to load settings:", error);
    }
});
</script>
