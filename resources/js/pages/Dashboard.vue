<script setup>
import { ref, onMounted, computed } from "vue";
import api from "@/plugins/axios";
import { usePermissions } from "@/composables/usePermissions";
import { useColorUtils } from "@/composables/useColorUtils";
import { useTheme } from "vuetify";
import { useLoadingStore } from "@/store/loading";

const { hasPermission } = usePermissions();
const { getContrastColor, getContrastColorWithOpacity } = useColorUtils();
const theme = useTheme();
const loadingStore = useLoadingStore();

const stats = ref({
    totalUsers: 0,
    totalRoles: 0,
    totalPermissions: 0,
    activeUsers: 0,
});

const recentLogs = ref([]);
const pageLoading = ref(false); // Local loading state

const currentUser = computed(() => {
    const userData = localStorage.getItem("user");
    if (userData) {
        try {
            return JSON.parse(userData);
        } catch (error) {
            return null;
        }
    }
    return null;
});

const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return "Good Morning";
    if (hour < 18) return "Good Afternoon";
    return "Good Evening";
});

// Get current primary color from theme
const primaryColor = computed(() => {
    const color = theme.current.value.colors.primary;
    return color;
});

// Compute contrasting text colors based on primary color
const textOnPrimary = computed(() => {
    const color = getContrastColor(primaryColor.value);
    return color;
});

const subtitleOnPrimary = computed(() => {
    return getContrastColorWithOpacity(primaryColor.value, 0.9);
});

const fetchDashboardData = async () => {
    // Hide global loader and use local one
    loadingStore.forceHide();
    pageLoading.value = true;

    try {
        // Create array of all API calls
        const apiCalls = [];

        // Add user stats call if permitted
        if (hasPermission("view users")) {
            apiCalls.push(
                api
                    .get("/users")
                    .then(({ data: users }) => {
                        stats.value.totalUsers = users.length;
                        stats.value.activeUsers = users.filter(
                            (u) => !u.deleted_at
                        ).length;
                    })
                    .catch((error) => {
                        console.log("Cannot fetch users", error);
                    })
            );
        }

        // Add roles call if permitted
        if (hasPermission("view roles")) {
            apiCalls.push(
                api
                    .get("/roles")
                    .then(({ data: roles }) => {
                        stats.value.totalRoles = roles.length;
                    })
                    .catch((error) => {
                        console.log("Cannot fetch roles", error);
                    })
            );
        }

        // Add permissions call if permitted
        if (hasPermission("view permissions")) {
            apiCalls.push(
                api
                    .get("/permissions")
                    .then(({ data: permissions }) => {
                        stats.value.totalPermissions = permissions.length;
                    })
                    .catch((error) => {
                        console.log("Cannot fetch permissions", error);
                    })
            );
        }

        // Add logs call if permitted
        if (hasPermission("view logs")) {
            apiCalls.push(
                api
                    .get("/user-logs", {
                        params: { per_page: 10 },
                    })
                    .then(({ data: logs }) => {
                        recentLogs.value = logs.data || [];
                    })
                    .catch((error) => {
                        console.log("Cannot fetch logs", error);
                    })
            );
        }

        // Wait for ALL API calls to complete
        await Promise.allSettled(apiCalls);
    } catch (error) {
        console.error("Error fetching dashboard data:", error);
    } finally {
        pageLoading.value = false;
    }
};

const getActionColor = (action) => {
    switch (action) {
        case "created":
            return "success";
        case "updated":
            return "info";
        case "deleted":
            return "error";
        default:
            return "default";
    }
};

const getActionIcon = (action) => {
    switch (action) {
        case "created":
            return "mdi-plus-circle";
        case "updated":
            return "mdi-pencil-circle";
        case "deleted":
            return "mdi-delete-circle";
        default:
            return "mdi-information";
    }
};

const formatDate = (date) => {
    const d = new Date(date);
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    if (d.toDateString() === today.toDateString()) {
        return (
            "Today at " +
            d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
        );
    } else if (d.toDateString() === yesterday.toDateString()) {
        return (
            "Yesterday at " +
            d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
        );
    } else {
        return (
            d.toLocaleDateString() +
            " at " +
            d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
        );
    }
};

onMounted(() => {
    fetchDashboardData();
});
</script>

<template>
    <v-container fluid>
        <!-- Local Loading Overlay for Dashboard -->
        <v-overlay
            v-model="pageLoading"
            class="align-center justify-center"
            contained
            persistent
        >
            <!-- <div class="text-center">
                <v-progress-circular
                    color="primary"
                    indeterminate
                    size="64"
                    width="6"
                ></v-progress-circular>
                <p class="text-white mt-4 text-h6">Loading Dashboard...</p>
            </div> -->
        </v-overlay>

        <!-- Welcome Section -->
        <v-row>
            <v-col cols="12">
                <v-card elevation="2" color="primary" class="welcome-card">
                    <v-card-text class="pa-6">
                        <v-row align="center">
                            <v-col cols="12" md="8">
                                <h1
                                    class="text-h4 font-weight-bold mb-2"
                                    :style="{
                                        color: textOnPrimary + ' !important',
                                    }"
                                >
                                    {{ greeting }},
                                    {{ currentUser?.first_name }}! 👋
                                </h1>
                                <p
                                    class="text-subtitle-1 mb-0"
                                    :style="{
                                        color:
                                            subtitleOnPrimary + ' !important',
                                    }"
                                >
                                    Welcome back to your Dashboard
                                </p>
                            </v-col>
                            <v-col
                                cols="12"
                                md="4"
                                class="text-md-right text-left"
                            >
                                <v-chip
                                    color="white"
                                    variant="flat"
                                    size="large"
                                    prepend-icon="mdi-calendar-today"
                                    class="date-chip"
                                >
                                    {{
                                        new Date().toLocaleDateString("en-US", {
                                            weekday: "long",
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                        })
                                    }}
                                </v-chip>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Stats Cards -->
        <v-row class="mt-4">
            <v-col cols="12" sm="6" md="3" v-if="hasPermission('view users')">
                <v-card elevation="2" class="stat-card">
                    <v-card-text>
                        <div class="d-flex justify-space-between align-center">
                            <div>
                                <p
                                    class="text-caption text-medium-emphasis mb-1"
                                >
                                    Total Users
                                </p>
                                <h2
                                    class="text-h4 font-weight-bold text-primary"
                                >
                                    {{ stats.totalUsers }}
                                </h2>
                                <p class="text-caption text-success mt-1">
                                    <v-icon size="small">mdi-arrow-up</v-icon>
                                    {{ stats.activeUsers }} Active
                                </p>
                            </div>
                            <v-avatar size="56" color="primary" variant="tonal">
                                <v-icon size="32" color="primary"
                                    >mdi-account-group</v-icon
                                >
                            </v-avatar>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3" v-if="hasPermission('view roles')">
                <v-card elevation="2" class="stat-card">
                    <v-card-text>
                        <div class="d-flex justify-space-between align-center">
                            <div>
                                <p
                                    class="text-caption text-medium-emphasis mb-1"
                                >
                                    Total Roles
                                </p>
                                <h2
                                    class="text-h4 font-weight-bold text-success"
                                >
                                    {{ stats.totalRoles }}
                                </h2>
                                <p
                                    class="text-caption text-medium-emphasis mt-1"
                                >
                                    User Roles
                                </p>
                            </div>
                            <v-avatar size="56" color="success" variant="tonal">
                                <v-icon size="32" color="success"
                                    >mdi-shield-account</v-icon
                                >
                            </v-avatar>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                cols="12"
                sm="6"
                md="3"
                v-if="hasPermission('view permissions')"
            >
                <v-card elevation="2" class="stat-card">
                    <v-card-text>
                        <div class="d-flex justify-space-between align-center">
                            <div>
                                <p
                                    class="text-caption text-medium-emphasis mb-1"
                                >
                                    Permissions
                                </p>
                                <h2
                                    class="text-h4 font-weight-bold text-warning"
                                >
                                    {{ stats.totalPermissions }}
                                </h2>
                                <p
                                    class="text-caption text-medium-emphasis mt-1"
                                >
                                    Access Controls
                                </p>
                            </div>
                            <v-avatar size="56" color="warning" variant="tonal">
                                <v-icon size="32" color="warning"
                                    >mdi-shield-lock</v-icon
                                >
                            </v-avatar>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3" v-if="hasPermission('view logs')">
                <v-card elevation="2" class="stat-card">
                    <v-card-text>
                        <div class="d-flex justify-space-between align-center">
                            <div>
                                <p
                                    class="text-caption text-medium-emphasis mb-1"
                                >
                                    Activity Logs
                                </p>
                                <h2 class="text-h4 font-weight-bold text-info">
                                    {{ recentLogs.length }}
                                </h2>
                                <p
                                    class="text-caption text-medium-emphasis mt-1"
                                >
                                    Recent Actions
                                </p>
                            </div>
                            <v-avatar size="56" color="info" variant="tonal">
                                <v-icon size="32" color="info"
                                    >mdi-history</v-icon
                                >
                            </v-avatar>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Quick Actions & Recent Activity -->
        <v-row class="mt-4">
            <!-- Quick Actions -->
            <v-col cols="12" md="4">
                <v-card elevation="2" class="h-100">
                    <v-card-title class="bg-primary pa-4">
                        <span :style="{ color: textOnPrimary }">
                            <v-icon start :style="{ color: textOnPrimary }"
                                >mdi-lightning-bolt</v-icon
                            >
                            Quick Actions
                        </span>
                    </v-card-title>
                    <v-card-text class="pa-4">
                        <v-list density="compact" class="py-0">
                            <v-list-item
                                v-if="hasPermission('create users')"
                                prepend-icon="mdi-account-plus"
                                title="Add New User"
                                subtitle="Create a new user account"
                                @click="$router.push('/users')"
                                class="quick-action-item"
                            />
                            <v-divider
                                v-if="hasPermission('create users')"
                                class="my-2"
                            />

                            <v-list-item
                                v-if="hasPermission('create roles')"
                                prepend-icon="mdi-shield-plus"
                                title="Create Role"
                                subtitle="Define a new user role"
                                @click="$router.push('/roles')"
                                class="quick-action-item"
                            />
                            <v-divider
                                v-if="hasPermission('create roles')"
                                class="my-2"
                            />

                            <v-list-item
                                v-if="hasPermission('view logs')"
                                prepend-icon="mdi-text-box-multiple"
                                title="View Activity Logs"
                                subtitle="Check recent system activity"
                                @click="$router.push('/logs')"
                                class="quick-action-item"
                            />
                            <v-divider
                                v-if="hasPermission('view logs')"
                                class="my-2"
                            />

                            <v-list-item
                                v-if="hasPermission('view settings')"
                                prepend-icon="mdi-cog"
                                title="System Settings"
                                subtitle="Configure system preferences"
                                @click="$router.push('/settings')"
                                class="quick-action-item"
                            />
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- Recent Activity -->
            <v-col cols="12" md="8">
                <v-card elevation="2" class="h-100">
                    <v-card-title class="bg-primary pa-4">
                        <span :style="{ color: textOnPrimary }">
                            <v-icon start :style="{ color: textOnPrimary }"
                                >mdi-history</v-icon
                            >
                            Recent Activity
                        </span>
                    </v-card-title>
                    <v-card-text class="pa-0">
                        <v-list v-if="recentLogs.length > 0" class="py-0">
                            <template
                                v-for="(log, index) in recentLogs"
                                :key="log.id"
                            >
                                <v-list-item class="py-3">
                                    <template v-slot:prepend>
                                        <v-avatar
                                            :color="getActionColor(log.action)"
                                            variant="tonal"
                                            size="40"
                                        >
                                            <v-icon
                                                :color="
                                                    getActionColor(log.action)
                                                "
                                                size="20"
                                            >
                                                {{ getActionIcon(log.action) }}
                                            </v-icon>
                                        </v-avatar>
                                    </template>

                                    <v-list-item-title
                                        class="font-weight-medium"
                                    >
                                        {{ log.description }}
                                    </v-list-item-title>
                                    <v-list-item-subtitle class="text-caption">
                                        <span class="text-medium-emphasis">
                                            {{
                                                log.user?.full_name || "System"
                                            }}
                                        </span>
                                        <span class="mx-1">•</span>
                                        <span>{{
                                            formatDate(log.created_at)
                                        }}</span>
                                    </v-list-item-subtitle>

                                    <template v-slot:append>
                                        <v-chip
                                            size="x-small"
                                            variant="outlined"
                                        >
                                            {{ log.model }}
                                        </v-chip>
                                    </template>
                                </v-list-item>
                                <v-divider
                                    v-if="index < recentLogs.length - 1"
                                />
                            </template>
                        </v-list>

                        <div v-else class="pa-8 text-center">
                            <v-icon size="64" color="grey-lighten-1"
                                >mdi-history</v-icon
                            >
                            <p class="text-body-1 text-medium-emphasis mt-4">
                                No recent activity
                            </p>
                        </div>
                    </v-card-text>

                    <v-divider />

                    <v-card-actions v-if="hasPermission('view logs')">
                        <v-spacer />
                        <v-btn
                            color="primary"
                            variant="text"
                            @click="$router.push('/logs')"
                            append-icon="mdi-arrow-right"
                        >
                            View All Logs
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <!-- System Overview -->
        <v-row class="mt-4">
            <v-col cols="12">
                <v-card elevation="2">
                    <v-card-title class="bg-primary pa-4">
                        <span :style="{ color: textOnPrimary }">
                            <v-icon start :style="{ color: textOnPrimary }"
                                >mdi-information</v-icon
                            >
                            System Overview
                        </span>
                    </v-card-title>
                    <v-card-text class="pa-6">
                        <v-row>
                            <v-col cols="12" md="4">
                                <div class="text-center pa-4">
                                    <v-icon size="48" color="primary"
                                        >mdi-shield-check</v-icon
                                    >
                                    <h3 class="text-h6 mt-2">Security</h3>
                                    <p class="text-body-2 text-medium-emphasis">
                                        Role-based access control with
                                        {{ stats.totalRoles }} roles and
                                        {{ stats.totalPermissions }} permissions
                                    </p>
                                </div>
                            </v-col>
                            <v-col cols="12" md="4">
                                <div class="text-center pa-4">
                                    <v-icon size="48" color="success"
                                        >mdi-account-check</v-icon
                                    >
                                    <h3 class="text-h6 mt-2">
                                        User Management
                                    </h3>
                                    <p class="text-body-2 text-medium-emphasis">
                                        {{ stats.activeUsers }} active users
                                        managing your fleet operations
                                    </p>
                                </div>
                            </v-col>
                            <v-col cols="12" md="4">
                                <div class="text-center pa-4">
                                    <v-icon size="48" color="info"
                                        >mdi-chart-line</v-icon
                                    >
                                    <h3 class="text-h6 mt-2">
                                        Activity Tracking
                                    </h3>
                                    <p class="text-body-2 text-medium-emphasis">
                                        Complete audit trail of all system
                                        activities and changes
                                    </p>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.welcome-card {
    position: relative;
    overflow: hidden;
}

.date-chip {
    color: rgb(var(--v-theme-primary)) !important;
}

.stat-card {
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

.quick-action-item {
    cursor: pointer;
    border-radius: 8px;
    margin: 4px 0;
}

.quick-action-item:hover {
    background-color: rgba(var(--v-theme-primary), 0.08);
}

.v-list-item {
    border-radius: 0;
}

.h-100 {
    height: 100%;
}
</style>
