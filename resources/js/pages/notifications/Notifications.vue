<template>
    <v-container fluid>
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <div class="d-flex align-center">
                    <v-icon left color="primary" size="32">mdi-bell</v-icon>
                    <span class="text-h5 ml-2">Notifications</span>
                </div>
                <div class="d-flex gap-2">
                    <v-chip
                        :color="unreadCount > 0 ? 'error' : 'success'"
                        variant="flat"
                    >
                        {{ unreadCount }} Unread
                    </v-chip>
                    <v-btn
                        v-if="unreadCount > 0"
                        color="primary"
                        variant="outlined"
                        prepend-icon="mdi-check-all"
                        @click="markAllAsRead"
                    >
                        Mark All Read
                    </v-btn>
                    <v-btn
                        color="error"
                        variant="outlined"
                        prepend-icon="mdi-delete"
                        @click="confirmDeleteAll"
                        :disabled="notifications.length === 0"
                    >
                        Delete All
                    </v-btn>
                </div>
            </v-card-title>

            <v-card-text>
                <!-- Filters and Stats -->
                <v-row class="mb-4">
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="search"
                            prepend-inner-icon="mdi-magnify"
                            label="Search notifications..."
                            variant="outlined"
                            density="comfortable"
                            clearable
                            @input="filterNotifications"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="3">
                        <v-select
                            v-model="filterStatus"
                            :items="statusFilters"
                            label="Filter by Status"
                            variant="outlined"
                            density="comfortable"
                            clearable
                            @update:model-value="filterNotifications"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" md="3">
                        <v-select
                            v-model="filterType"
                            :items="typeFilters"
                            label="Filter by Type"
                            variant="outlined"
                            density="comfortable"
                            clearable
                            @update:model-value="filterNotifications"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-btn
                            block
                            color="primary"
                            variant="outlined"
                            @click="refreshNotifications"
                            :loading="loading"
                        >
                            <v-icon>mdi-refresh</v-icon>
                            Refresh
                        </v-btn>
                    </v-col>
                </v-row>

                <!-- Statistics Cards -->
                <v-row class="mb-4">
                    <v-col cols="12" sm="6" md="3">
                        <v-card color="primary" variant="outlined">
                            <v-card-text class="text-center">
                                <div class="text-h4 font-weight-bold">
                                    {{ stats.total }}
                                </div>
                                <div class="text-subtitle-2">
                                    Total Notifications
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <v-card color="error" variant="outlined">
                            <v-card-text class="text-center">
                                <div class="text-h4 font-weight-bold">
                                    {{ stats.unread }}
                                </div>
                                <div class="text-subtitle-2">Unread</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <v-card color="success" variant="outlined">
                            <v-card-text class="text-center">
                                <div class="text-h4 font-weight-bold">
                                    {{ stats.read }}
                                </div>
                                <div class="text-subtitle-2">Read</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <v-card color="info" variant="outlined">
                            <v-card-text class="text-center">
                                <div class="text-h4 font-weight-bold">
                                    {{ stats.today }}
                                </div>
                                <div class="text-subtitle-2">Today</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

                <!-- Notifications List -->
                <v-card
                    v-if="loading && notifications.length === 0"
                    class="pa-4 text-center"
                >
                    <v-progress-circular
                        indeterminate
                        color="primary"
                    ></v-progress-circular>
                    <div class="mt-2">Loading notifications...</div>
                </v-card>

                <v-card
                    v-else-if="filteredNotifications.length === 0"
                    class="pa-8 text-center"
                >
                    <v-icon size="64" color="grey-lighten-1"
                        >mdi-bell-off</v-icon
                    >
                    <div class="text-h6 mt-4 text-grey">
                        No notifications found
                    </div>
                    <div class="text-body-2 text-grey">
                        {{
                            search || filterStatus || filterType
                                ? "Try adjusting your filters"
                                : "You're all caught up!"
                        }}
                    </div>
                </v-card>

                <v-list v-else class="pa-0">
                    <v-list-item
                        v-for="notification in paginatedNotifications"
                        :key="notification.id"
                        :class="{
                            'bg-blue-lighten-5': !notification.read_at,
                            'notification-item': true,
                        }"
                        class="mb-2 rounded"
                        @click="handleNotificationClick(notification)"
                    >
                        <template v-slot:prepend>
                            <v-avatar
                                :color="getNotificationColor(notification)"
                                size="48"
                            >
                                <v-icon color="white">
                                    {{ getNotificationIcon(notification) }}
                                </v-icon>
                            </v-avatar>
                        </template>

                        <v-list-item-title class="font-weight-medium mb-1">
                            {{ notification.data.message || "Notification" }}
                        </v-list-item-title>

                        <v-list-item-subtitle>
                            <v-chip
                                size="x-small"
                                :color="getNotificationColor(notification)"
                                class="mr-2"
                            >
                                {{ getNotificationTypeName(notification) }}
                            </v-chip>
                            <span class="text-caption">
                                {{ formatDate(notification.created_at) }}
                            </span>
                        </v-list-item-subtitle>

                        <!-- Additional Info for Driver Document Notifications -->
                        <div
                            v-if="
                                notification.data.type ===
                                'driver_document_expiring'
                            "
                            class="mt-2"
                        >
                            <v-chip
                                size="small"
                                variant="outlined"
                                class="mr-2"
                            >
                                <v-icon start size="small">mdi-account</v-icon>
                                {{ notification.data.driver_name }}
                            </v-chip>
                            <v-chip
                                size="small"
                                variant="outlined"
                                class="mr-2"
                            >
                                <v-icon start size="small"
                                    >mdi-file-document</v-icon
                                >
                                {{ notification.data.document_type }}
                            </v-chip>
                            <v-chip
                                size="small"
                                :color="
                                    notification.data.status === 'expired'
                                        ? 'error'
                                        : 'warning'
                                "
                                variant="flat"
                            >
                                <v-icon start size="small">mdi-calendar</v-icon>
                                {{ formatExpiryInfo(notification.data) }}
                            </v-chip>
                        </div>

                        <template v-slot:append>
                            <div class="d-flex flex-column align-center gap-2">
                                <v-tooltip text="Mark as read" location="left">
                                    <template v-slot:activator="{ props }">
                                        <v-btn
                                            v-if="!notification.read_at"
                                            v-bind="props"
                                            icon="mdi-check"
                                            size="small"
                                            variant="text"
                                            color="success"
                                            @click.stop="
                                                markAsRead(notification.id)
                                            "
                                        ></v-btn>
                                    </template>
                                </v-tooltip>

                                <v-tooltip
                                    text="Mark as unread"
                                    location="left"
                                >
                                    <template v-slot:activator="{ props }">
                                        <v-btn
                                            v-if="notification.read_at"
                                            v-bind="props"
                                            icon="mdi-email-mark-as-unread"
                                            size="small"
                                            variant="text"
                                            color="primary"
                                            @click.stop="
                                                markAsUnread(notification.id)
                                            "
                                        ></v-btn>
                                    </template>
                                </v-tooltip>

                                <v-tooltip text="Delete" location="left">
                                    <template v-slot:activator="{ props }">
                                        <v-btn
                                            v-bind="props"
                                            icon="mdi-delete"
                                            size="small"
                                            variant="text"
                                            color="error"
                                            @click.stop="
                                                deleteNotification(
                                                    notification.id
                                                )
                                            "
                                        ></v-btn>
                                    </template>
                                </v-tooltip>
                            </div>
                        </template>
                    </v-list-item>
                </v-list>

                <!-- Pagination -->
                <v-pagination
                    v-if="totalPages > 1"
                    v-model="currentPage"
                    :length="totalPages"
                    :total-visible="7"
                    class="mt-4"
                ></v-pagination>
            </v-card-text>
        </v-card>

        <!-- Confirmation Dialog -->
        <v-dialog v-model="deleteAllDialog" max-width="500">
            <v-card>
                <v-card-title class="text-h5">
                    Delete All Notifications?
                </v-card-title>
                <v-card-text>
                    Are you sure you want to delete all
                    {{ notifications.length }} notifications? This action cannot
                    be undone.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn variant="text" @click="deleteAllDialog = false">
                        Cancel
                    </v-btn>
                    <v-btn color="error" variant="flat" @click="deleteAll">
                        Delete All
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "@/plugins/axios";
import { useSnackbarStore } from "@/store/snackbar";

const router = useRouter();
const snackbar = useSnackbarStore();

// Data
const loading = ref(false);
const notifications = ref([]);
const stats = ref({
    total: 0,
    unread: 0,
    read: 0,
    today: 0,
    this_week: 0,
    this_month: 0,
});

const search = ref("");
const filterStatus = ref(null);
const filterType = ref(null);
const currentPage = ref(1);
const itemsPerPage = ref(20);
const deleteAllDialog = ref(false);

// Filter Options
const statusFilters = [
    { title: "All", value: null },
    { title: "Unread", value: "unread" },
    { title: "Read", value: "read" },
];

const typeFilters = computed(() => {
    const types = new Set();
    notifications.value.forEach((n) => {
        if (n.data.type) {
            types.add(n.data.type);
        }
    });
    return [
        { title: "All Types", value: null },
        ...Array.from(types).map((type) => ({
            title: formatTypeName(type),
            value: type,
        })),
    ];
});

// Computed
const unreadCount = computed(() => {
    return notifications.value.filter((n) => !n.read_at).length;
});

const filteredNotifications = computed(() => {
    let filtered = [...notifications.value];

    // Filter by search
    if (search.value) {
        const searchLower = search.value.toLowerCase();
        filtered = filtered.filter(
            (n) =>
                (n.data.message || "").toLowerCase().includes(searchLower) ||
                (n.data.driver_name || "")
                    .toLowerCase()
                    .includes(searchLower) ||
                (n.data.document_type || "").toLowerCase().includes(searchLower)
        );
    }

    // Filter by status
    if (filterStatus.value === "unread") {
        filtered = filtered.filter((n) => !n.read_at);
    } else if (filterStatus.value === "read") {
        filtered = filtered.filter((n) => n.read_at);
    }

    // Filter by type
    if (filterType.value) {
        filtered = filtered.filter((n) => n.data.type === filterType.value);
    }

    return filtered;
});

const totalPages = computed(() => {
    return Math.ceil(filteredNotifications.value.length / itemsPerPage.value);
});

const paginatedNotifications = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredNotifications.value.slice(start, end);
});

// Methods
const fetchNotifications = async () => {
    loading.value = true;
    try {
        const { data } = await api.get("/notifications");
        notifications.value = data.data || data;
    } catch (error) {
        snackbar.error("Failed to load notifications");
        console.error("Failed to fetch notifications", error);
    } finally {
        loading.value = false;
    }
};

const fetchStats = async () => {
    try {
        const { data } = await api.get("/notifications/stats");
        stats.value = data;
    } catch (error) {
        console.error("Failed to fetch stats", error);
    }
};

const refreshNotifications = async () => {
    await Promise.all([fetchNotifications(), fetchStats()]);
    snackbar.success("Notifications refreshed");
};

const filterNotifications = () => {
    currentPage.value = 1; // Reset to first page when filtering
};

const markAsRead = async (id) => {
    try {
        await api.post(`/notifications/${id}/read`);
        const notification = notifications.value.find((n) => n.id === id);
        if (notification) {
            notification.read_at = new Date().toISOString();
        }
        await fetchStats();
        snackbar.success("Notification marked as read");
    } catch (error) {
        snackbar.error("Failed to mark notification as read");
    }
};

const markAsUnread = async (id) => {
    try {
        await api.post(`/notifications/${id}/unread`);
        const notification = notifications.value.find((n) => n.id === id);
        if (notification) {
            notification.read_at = null;
        }
        await fetchStats();
        snackbar.success("Notification marked as unread");
    } catch (error) {
        snackbar.error("Failed to mark notification as unread");
    }
};

const markAllAsRead = async () => {
    try {
        await api.post("/notifications/mark-all-read");
        notifications.value.forEach((n) => {
            n.read_at = new Date().toISOString();
        });
        await fetchStats();
        snackbar.success("All notifications marked as read");
    } catch (error) {
        snackbar.error("Failed to mark all notifications as read");
    }
};

const deleteNotification = async (id) => {
    if (!confirm("Are you sure you want to delete this notification?")) {
        return;
    }

    try {
        await api.delete(`/notifications/${id}`);
        const index = notifications.value.findIndex((n) => n.id === id);
        if (index > -1) {
            notifications.value.splice(index, 1);
        }
        await fetchStats();
        snackbar.success("Notification deleted");
    } catch (error) {
        snackbar.error("Failed to delete notification");
    }
};

const confirmDeleteAll = () => {
    if (notifications.value.length === 0) {
        snackbar.warning("No notifications to delete");
        return;
    }
    deleteAllDialog.value = true;
};

const deleteAll = async () => {
    try {
        await api.delete("/notifications");
        notifications.value = [];
        await fetchStats();
        deleteAllDialog.value = false;
        snackbar.success("All notifications deleted");
    } catch (error) {
        snackbar.error("Failed to delete all notifications");
    }
};

const handleNotificationClick = async (notification) => {
    // Mark as read if unread
    if (!notification.read_at) {
        await markAsRead(notification.id);
    }

    // Navigate based on notification type
    if (notification.data.type === "driver_document_expiring") {
        router.push("/drivers");
    }
};

const getNotificationColor = (notification) => {
    if (notification.data.status === "expired") return "error";
    if (notification.data.status === "expiring") return "warning";
    if (notification.data.priority === "high") return "error";
    if (notification.data.priority === "medium") return "warning";
    return "info";
};

const getNotificationIcon = (notification) => {
    if (notification.data.type === "driver_document_expiring") {
        return notification.data.status === "expired"
            ? "mdi-file-alert"
            : "mdi-file-clock";
    }
    return "mdi-information";
};

const getNotificationTypeName = (notification) => {
    if (notification.data.type) {
        return formatTypeName(notification.data.type);
    }
    return "Notification";
};

const formatTypeName = (type) => {
    return type
        .replace(/_/g, " ")
        .replace(/\b\w/g, (char) => char.toUpperCase());
};

const formatDate = (date) => {
    if (!date) return "";
    const now = new Date();
    const notifDate = new Date(date);
    const diffMs = now - notifDate;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return "Just now";
    if (diffMins < 60) return `${diffMins} min ago`;
    if (diffHours < 24)
        return `${diffHours} hour${diffHours > 1 ? "s" : ""} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? "s" : ""} ago`;

    return notifDate.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year:
            notifDate.getFullYear() !== now.getFullYear()
                ? "numeric"
                : undefined,
    });
};

const formatExpiryInfo = (data) => {
    if (data.status === "expired") {
        const days = Math.abs(data.days_until_expiry);
        return `Expired ${days} day${days !== 1 ? "s" : ""} ago`;
    } else {
        const days = data.days_until_expiry;
        return `Expires in ${days} day${days !== 1 ? "s" : ""}`;
    }
};

// Lifecycle
onMounted(async () => {
    await Promise.all([fetchNotifications(), fetchStats()]);
});
</script>

<style scoped>
.notification-item {
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
    transform: translateX(4px);
}

:deep(.v-list-item__prepend) {
    margin-right: 16px;
}

.gap-2 {
    gap: 8px;
}
</style>
