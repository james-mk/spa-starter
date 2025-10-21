<template>
    <v-navigation-drawer
        :model-value="drawer"
        @update:model-value="updateDrawer"
        app
        color="primary"
        :style="sidebarStyles"
    >
        <!-- Sidebar Header - Logo Only -->
        <div class="pa-4 d-flex align-center justify-center sidebar-header">
            <div class="logo-container">
                <v-img
                    v-if="settingsStore.companyLogo"
                    :src="settingsStore.companyLogo"
                    max-height="50"
                    max-width="180"
                    contain
                    class="logo-image"
                />
                <div v-else class="logo-fallback" :style="logoFallbackStyles">
                    <span
                        class="text-h5 font-weight-bold"
                        :style="{ color: textColor }"
                    >
                        {{ settingsStore.companyInitials }}
                    </span>
                </div>
            </div>
        </div>

        <v-divider
            class="mx-3 mb-2"
            :style="{ borderColor: dividerColor }"
        ></v-divider>

        <v-list nav class="py-2">
            <!-- Dashboard -->
            <v-list-item
                v-if="hasPermission('view dashboard')"
                link
                @click="$router.push('/')"
                prepend-icon="mdi-speedometer"
                title="Dashboard"
                :active="$route.path === '/'"
                class="mb-1 sidebar-item"
            >
            </v-list-item>

            <!-- User Access Control Group -->
            <v-list-group
                v-if="
                    hasAnyPermission([
                        'view users',
                        'view roles',
                        'view permissions',
                    ])
                "
                value="access-control"
            >
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-shield-account"
                        title="User Access Control"
                        class="mb-1 sidebar-item"
                    >
                    </v-list-item>
                </template>

                <v-list-item
                    v-if="hasPermission('view users')"
                    link
                    @click="$router.push('/users')"
                    prepend-icon="mdi-account-multiple"
                    title="Users"
                    :active="$route.path === '/users'"
                    class="ml-4 mb-1 sidebar-item"
                >
                </v-list-item>

                <v-list-item
                    v-if="hasPermission('view roles')"
                    link
                    @click="$router.push('/roles')"
                    prepend-icon="mdi-account-cog"
                    title="Roles"
                    :active="$route.path === '/roles'"
                    class="ml-4 mb-1 sidebar-item"
                >
                </v-list-item>

                <!-- <v-list-item
                    v-if="hasPermission('view permissions')"
                    link
                    @click="$router.push('/permissions')"
                    prepend-icon="mdi-shield-lock"
                    title="Permissions"
                    :active="$route.path === '/permissions'"
                    class="ml-4 mb-1 sidebar-item"
                >
                </v-list-item> -->
            </v-list-group>

            <!-- Settings -->
            <v-list-item
                v-if="hasPermission('view settings')"
                link
                @click="$router.push('/settings')"
                prepend-icon="mdi-cog"
                title="App Settings"
                :active="$route.path === '/settings'"
                class="mb-1 sidebar-item"
            >
            </v-list-item>
            <!-- User Logs -->
            <v-list-item
                v-if="hasPermission('view logs')"
                link
                @click="$router.push('/logs')"
                prepend-icon="mdi-text-box-multiple"
                title="Activity Logs"
                :active="$route.path === '/logs'"
                class="mb-1 sidebar-item"
            >
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { usePermissions } from "@/composables/usePermissions";
import { useSettingsStore } from "@/store/settings";
import { useColorUtils } from "@/composables/useColorUtils";
import { useTheme } from "vuetify";

const route = useRoute();
const { hasPermission, hasAnyPermission } = usePermissions();
const settingsStore = useSettingsStore();
const { getContrastColor, isLightColor } = useColorUtils();
const theme = useTheme();

const props = defineProps({
    drawer: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["update:drawer"]);

function updateDrawer(val) {
    emit("update:drawer", val);
}

const primaryColor = computed(() => {
    return settingsStore.primaryColor || "#14539A";
});

const textColor = computed(() => {
    return getContrastColor(primaryColor.value);
});

const hoverBackgroundColor = computed(() => {
    const isLight = isLightColor(primaryColor.value);
    return isLight ? "rgba(0, 0, 0, 0.08)" : "rgba(255, 255, 255, 0.08)";
});

const activeBackgroundColor = computed(() => {
    const isLight = isLightColor(primaryColor.value);
    return isLight ? "rgba(0, 0, 0, 0.16)" : "rgba(255, 255, 255, 0.16)";
});

const dividerColor = computed(() => {
    const isLight = isLightColor(primaryColor.value);
    return isLight ? "rgba(0, 0, 0, 0.12)" : "rgba(255, 255, 255, 0.12)";
});

const sidebarStyles = computed(() => ({
    "--text-color": textColor.value,
    "--hover-bg": hoverBackgroundColor.value,
    "--active-bg": activeBackgroundColor.value,
    "--divider-color": dividerColor.value,
}));

const logoFallbackStyles = computed(() => {
    const isLight = isLightColor(primaryColor.value);
    return {
        backgroundColor: isLight
            ? "rgba(0, 0, 0, 0.1)"
            : "rgba(255, 255, 255, 0.1)",
    };
});

onMounted(() => {
    // Fetch settings when sidebar mounts (if not already loaded)
    if (!settingsStore.settings) {
        settingsStore.fetchSettings();
    }
});
</script>

<style scoped>
.sidebar-header {
    border-bottom: 1px solid var(--divider-color);
    min-height: 80px;
}

.logo-container {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-image {
    border-radius: 4px;
}

.logo-fallback {
    width: 100%;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

/* List Items - Use CSS variables for dynamic colors */
:deep(.sidebar-item) {
    border-radius: 8px;
    margin: 0 8px;
    transition: all 0.2s ease-in-out;
    color: var(--text-color) !important;
}

:deep(.sidebar-item .v-list-item-title) {
    color: var(--text-color) !important;
}

:deep(.sidebar-item:hover) {
    background-color: var(--hover-bg) !important;
    transform: translateX(4px);
}

/* Active Menu Item */
:deep(.sidebar-item.v-list-item--active) {
    background-color: var(--active-bg) !important;
    font-weight: 600;
}

:deep(.sidebar-item.v-list-item--active .v-list-item-title) {
    color: var(--text-color) !important;
    font-weight: 600;
}

:deep(.sidebar-item.v-list-item--active .v-list-item__prepend .v-icon) {
    color: var(--text-color) !important;
    opacity: 1 !important;
}

/* Icons */
:deep(.sidebar-item .v-list-item__prepend > .v-icon) {
    color: var(--text-color) !important;
    opacity: 0.7;
    transition: opacity 0.2s;
}

:deep(.sidebar-item:hover .v-list-item__prepend > .v-icon) {
    opacity: 1;
}

/* List Background */
:deep(.v-list) {
    background: transparent;
}

/* List Group */
:deep(.v-list-group__items) {
    background-color: var(--hover-bg);
    border-radius: 8px;
    margin: 4px 8px;
    padding: 4px 0;
}

:deep(.v-list-group__header.sidebar-item) {
    border-radius: 8px;
    margin: 0 8px 4px 8px;
}

:deep(.v-list-group__header.sidebar-item:hover) {
    background-color: var(--hover-bg) !important;
}

/* Submenu Items */
:deep(.v-list-group__items .sidebar-item) {
    margin: 2px 4px;
    padding-left: 8px;
}

/* List Group Header Icon */
:deep(.v-list-group__header .v-list-group__header__append .v-icon) {
    color: var(--text-color) !important;
    opacity: 0.7;
}
</style>
