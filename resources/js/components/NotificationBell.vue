<template>
    <v-menu offset-y max-width="400">
        <template v-slot:activator="{ props }">
            <v-btn icon v-bind="props">
                <v-badge
                    :content="unreadCount"
                    :model-value="unreadCount > 0"
                    color="error"
                    overlap
                >
                    <v-icon>mdi-bell</v-icon>
                </v-badge>
            </v-btn>
        </template>

        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <span>Notifications</span>
                <v-btn
                    v-if="unreadCount > 0"
                    size="small"
                    variant="text"
                    @click="markAllAsRead"
                >
                    Mark all read
                </v-btn>
            </v-card-title>

            <v-divider></v-divider>

            <v-list
                v-if="notifications.length > 0"
                max-height="400"
                class="overflow-y-auto"
            >
                <v-list-item
                    v-for="notification in notifications"
                    :key="notification.id"
                    :class="{ 'bg-blue-lighten-5': !notification.read_at }"
                    @click="handleNotificationClick(notification)"
                >
                    <template v-slot:prepend>
                        <v-icon :color="getNotificationColor(notification)">
                            {{ getNotificationIcon(notification) }}
                        </v-icon>
                    </template>

                    <v-list-item-title>
                        {{ notification.data.message }}
                    </v-list-item-title>
                    <v-list-item-subtitle>
                        {{ formatDate(notification.created_at) }}
                    </v-list-item-subtitle>

                    <template v-slot:append>
                        <v-btn
                            icon="mdi-close"
                            size="x-small"
                            variant="text"
                            @click.stop="deleteNotification(notification.id)"
                        ></v-btn>
                    </template>
                </v-list-item>
            </v-list>

            <v-card-text v-else class="text-center text-grey">
                No notifications
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-btn
                    block
                    variant="text"
                    @click="$router.push('/notifications')"
                >
                    View All
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-menu>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "@/plugins/axios";
import { useSnackbarStore } from "@/store/snackbar";

const router = useRouter();
const snackbar = useSnackbarStore();

const notifications = ref([]);
const unreadCount = ref(0);

const fetchNotifications = async () => {
    try {
        const { data } = await api.get("/notifications/unread");
        notifications.value = data;
        unreadCount.value = data.filter((n) => !n.read_at).length;
    } catch (error) {
        console.error("Failed to fetch notifications", error);
    }
};

const markAllAsRead = async () => {
    try {
        await api.post("/notifications/mark-all-read");
        await fetchNotifications();
        snackbar.show("All notifications marked as read", "success");
    } catch (error) {
        snackbar.show("Failed to mark notifications as read", "error");
    }
};

const deleteNotification = async (id) => {
    try {
        await api.delete(`/notifications/${id}`);
        await fetchNotifications();
        snackbar.show("Notification deleted", "success");
    } catch (error) {
        snackbar.show("Failed to delete notification", "error");
    }
};

const handleNotificationClick = async (notification) => {
    // Mark as read
    if (!notification.read_at) {
        await api.post(`/notifications/${notification.id}/read`);
    }

    // Navigate based on notification type
    if (notification.data.type === "driver_document_expiring") {
        router.push(`/drivers`);
    }

    await fetchNotifications();
};

const getNotificationColor = (notification) => {
    if (notification.data.status === "expired") return "error";
    if (notification.data.status === "expiring") return "warning";
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

const formatDate = (date) => {
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

    return notifDate.toLocaleDateString();
};

// Poll for new notifications every 60 seconds
let pollInterval;

onMounted(() => {
    fetchNotifications();
    pollInterval = setInterval(fetchNotifications, 60000);
});

// Cleanup interval on unmount
import { onUnmounted } from "vue";
onUnmounted(() => {
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});
</script>
