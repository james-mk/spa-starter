<template>
    <v-app>
        <SideBar :drawer="drawer" @update:drawer="drawer = $event" />

        <v-app-bar app color="primary" elevation="12">
            <v-btn icon @click="drawer = !drawer">
                <v-icon>mdi-menu</v-icon>
            </v-btn>
            <v-toolbar-title>{{ settingsStore.companyName }}</v-toolbar-title>
            <v-spacer />
            <NotificationMenu />

            <!-- Theme Toggle -->
            <v-btn icon @click="toggleTheme" class="mr-2">
                <v-icon>{{
                    currentTheme === "dark"
                        ? "mdi-weather-night"
                        : "mdi-weather-sunny"
                }}</v-icon>
            </v-btn>

            <v-menu>
                <template #activator="{ props }">
                    <v-btn icon v-bind="props">
                        <v-avatar size="32">
                            <v-img
                                v-if="currentUser?.profile_image_url"
                                :src="currentUser.profile_image_url"
                                cover
                            />
                            <span v-else class="text-white">
                                {{ userInitials }}
                            </span>
                        </v-avatar>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item class="px-4 py-2">
                        <v-list-item-title class="font-weight-bold">
                            {{ currentUser?.full_name || "User" }}
                        </v-list-item-title>
                        <v-list-item-subtitle class="text-caption">
                            {{ currentUser?.email }}
                        </v-list-item-subtitle>
                    </v-list-item>
                    <v-divider />
                    <v-list-item
                        @click="$router.push('/profile')"
                        prepend-icon="mdi-account"
                    >
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="logout" prepend-icon="mdi-logout">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <v-main class="main-content">
            <v-container class="py-6" fluid>
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref, computed } from "vue";
import SideBar from "../components/SideBar.vue";
import NotificationMenu from "../components/NotificationMenu.vue";
import { useAuthStore } from "../store/auth";
import { useSettingsStore } from "../store/settings";
import { useRouter } from "vue-router";
import { useTheme } from "vuetify";

const drawer = ref(true);
const auth = useAuthStore();
const settingsStore = useSettingsStore();
const router = useRouter();
const theme = useTheme();

const currentTheme = computed(() => theme.global.name.value);

const currentUser = computed(() => {
    const userData = localStorage.getItem("user");
    if (userData) {
        try {
            return JSON.parse(userData);
        } catch (error) {
            console.error("Error parsing user data:", error);
            return null;
        }
    }
    return null;
});

const userInitials = computed(() => {
    if (currentUser.value) {
        const firstInitial = currentUser.value.first_name?.[0] || "";
        const lastInitial = currentUser.value.last_name?.[0] || "";
        return `${firstInitial}${lastInitial}`.toUpperCase();
    }
    return "U";
});

function toggleTheme() {
    const newTheme = theme.global.name.value === "light" ? "dark" : "light";
    theme.global.name.value = newTheme;
}

async function logout() {
    await auth.logout();
    router.push("/login");
}
</script>

<style scoped>
.main-content {
    background-color: rgb(var(--v-theme-surface));
}
</style>
