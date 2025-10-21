<script setup>
import { ref, onMounted, computed, watch } from "vue";
import api from "@/plugins/axios";
import { usePermissions } from "@/composables/usePermissions";

const { hasPermission } = usePermissions();
const logs = ref([]);
const loading = ref(false);
const totalLogs = ref(0);
const page = ref(1);
const itemsPerPage = ref(50);
const showDetailModal = ref(false);
const selectedLog = ref(null);

// Filters
const filterUser = ref(null);
const filterModel = ref("");
const filterAction = ref("");
const filterStartDate = ref("");
const filterEndDate = ref("");
const users = ref([]);

const modelTypes = ["Role", "Permission", "User", "Setting"];
const actionTypes = ["created", "updated", "deleted"];

const fetchLogs = async () => {
    loading.value = true;
    try {
        const params = {
            page: page.value,
            per_page: itemsPerPage.value,
        };

        if (filterUser.value) params.user_id = filterUser.value;
        if (filterModel.value) params.model = filterModel.value;
        if (filterAction.value) params.action = filterAction.value;
        if (filterStartDate.value) params.start_date = filterStartDate.value;
        if (filterEndDate.value) params.end_date = filterEndDate.value;

        const { data } = await api.get("/user-logs", { params });
        logs.value = data.data;
        totalLogs.value = data.total;
    } catch (error) {
        console.error("Error fetching logs:", error);
    } finally {
        loading.value = false;
    }
};

const fetchUsers = async () => {
    try {
        const { data } = await api.get("/users");
        users.value = data;
    } catch (error) {
        console.error("Error fetching users:", error);
    }
};

const viewDetails = (log) => {
    selectedLog.value = log;
    showDetailModal.value = true;
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
    return new Date(date).toLocaleString();
};

const clearFilters = () => {
    filterUser.value = null;
    filterModel.value = "";
    filterAction.value = "";
    filterStartDate.value = "";
    filterEndDate.value = "";
    page.value = 1;
    fetchLogs();
};

// Watch filters
watch(
    [filterUser, filterModel, filterAction, filterStartDate, filterEndDate],
    () => {
        page.value = 1;
        fetchLogs();
    }
);

// Watch pagination
watch([page, itemsPerPage], () => {
    fetchLogs();
});

onMounted(() => {
    if (hasPermission("view logs")) {
        fetchLogs();
        fetchUsers();
    }
});
</script>

<template>
    <v-container fluid v-if="hasPermission('view logs')">
        <v-row class="justify-space-between align-center mb-4">
            <v-col cols="auto">
                <h2 class="text-h5 font-weight-bold text-primary">
                    User Activity Logs
                </h2>
            </v-col>
        </v-row>

        <!-- Filters -->
        <v-card elevation="2" class="mb-4">
            <v-card-text>
                <v-row>
                    <v-col cols="12" md="3">
                        <v-select
                            v-model="filterUser"
                            :items="users"
                            item-title="full_name"
                            item-value="id"
                            label="Filter by User"
                            variant="outlined"
                            density="comfortable"
                            clearable
                            prepend-inner-icon="mdi-account"
                        />
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-select
                            v-model="filterModel"
                            :items="modelTypes"
                            label="Filter by Module"
                            variant="outlined"
                            density="comfortable"
                            clearable
                            prepend-inner-icon="mdi-shape"
                        />
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-select
                            v-model="filterAction"
                            :items="actionTypes"
                            label="Filter by Action"
                            variant="outlined"
                            density="comfortable"
                            clearable
                            prepend-inner-icon="mdi-flash"
                        />
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-text-field
                            v-model="filterStartDate"
                            label="Start Date"
                            type="date"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-calendar-start"
                        />
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-text-field
                            v-model="filterEndDate"
                            label="End Date"
                            type="date"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-calendar-end"
                        />
                    </v-col>
                    <v-col cols="12" md="1" class="d-flex align-center">
                        <v-btn
                            icon="mdi-filter-remove"
                            variant="text"
                            color="error"
                            @click="clearFilters"
                            title="Clear Filters"
                        />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!-- Logs Table -->
        <v-data-table
            :items="logs"
            :loading="loading"
            :headers="[
                { title: 'Date & Time', key: 'created_at', width: '180px' },
                { title: 'User', key: 'user' },
                { title: 'Action', key: 'action', width: '120px' },
                { title: 'Module', key: 'model', width: '120px' },
                { title: 'Description', key: 'description' },
                { title: 'IP Address', key: 'ip_address', width: '140px' },
                {
                    title: 'Details',
                    key: 'actions',
                    sortable: false,
                    width: '100px',
                    align: 'center',
                },
            ]"
            :items-per-page="itemsPerPage"
            hide-default-footer
            class="elevation-2"
        >
            <template v-slot:[`item.created_at`]="{ item }">
                <span class="text-caption">{{
                    formatDate(item.created_at)
                }}</span>
            </template>

            <template v-slot:[`item.user`]="{ item }">
                <div class="d-flex align-center">
                    <v-avatar size="32" color="primary" class="mr-2">
                        <v-img
                            v-if="item.user?.profile_image_url"
                            :src="item.user.profile_image_url"
                            cover
                        />
                        <span v-else class="text-white text-caption">
                            {{ item.user?.first_name?.[0] || "S"
                            }}{{ item.user?.last_name?.[0] || "Y" }}
                        </span>
                    </v-avatar>
                    <span>{{ item.user?.full_name || "System" }}</span>
                </div>
            </template>

            <template v-slot:[`item.action`]="{ item }">
                <v-chip
                    :color="getActionColor(item.action)"
                    :prepend-icon="getActionIcon(item.action)"
                    size="small"
                    variant="tonal"
                >
                    {{ item.action }}
                </v-chip>
            </template>

            <template v-slot:[`item.model`]="{ item }">
                <v-chip size="small" variant="outlined">
                    {{ item.model }}
                </v-chip>
            </template>

            <template v-slot:[`item.description`]="{ item }">
                <span class="text-body-2">{{ item.description }}</span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <v-btn
                    icon="mdi-eye"
                    size="small"
                    variant="text"
                    color="primary"
                    @click="viewDetails(item)"
                />
            </template>
        </v-data-table>

        <!-- Pagination -->
        <div class="d-flex justify-space-between align-center mt-4">
            <div class="text-caption">
                Showing {{ (page - 1) * itemsPerPage + 1 }} to
                {{ Math.min(page * itemsPerPage, totalLogs) }} of
                {{ totalLogs }} logs
            </div>
            <v-pagination
                v-model="page"
                :length="Math.ceil(totalLogs / itemsPerPage)"
                :total-visible="7"
            />
        </div>

        <!-- Detail Modal -->
        <v-dialog v-model="showDetailModal" max-width="800px">
            <v-card v-if="selectedLog">
                <v-card-title class="bg-primary text-white">
                    <span class="text-h6">Activity Log Details</span>
                </v-card-title>

                <v-card-text class="pt-6">
                    <v-row>
                        <v-col cols="12" md="6">
                            <div class="mb-4">
                                <div class="text-caption text-medium-emphasis">
                                    User
                                </div>
                                <div class="text-body-1 font-weight-medium">
                                    {{
                                        selectedLog.user?.full_name || "System"
                                    }}
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="12" md="6">
                            <div class="mb-4">
                                <div class="text-caption text-medium-emphasis">
                                    Date & Time
                                </div>
                                <div class="text-body-1">
                                    {{ formatDate(selectedLog.created_at) }}
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="12" md="6">
                            <div class="mb-4">
                                <div class="text-caption text-medium-emphasis">
                                    Action
                                </div>
                                <v-chip
                                    :color="getActionColor(selectedLog.action)"
                                    :prepend-icon="
                                        getActionIcon(selectedLog.action)
                                    "
                                    size="small"
                                    variant="tonal"
                                >
                                    {{ selectedLog.action }}
                                </v-chip>
                            </div>
                        </v-col>
                        <v-col cols="12" md="6">
                            <div class="mb-4">
                                <div class="text-caption text-medium-emphasis">
                                    Module
                                </div>
                                <div class="text-body-1">
                                    {{ selectedLog.model }}
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="12">
                            <div class="mb-4">
                                <div class="text-caption text-medium-emphasis">
                                    Description
                                </div>
                                <div class="text-body-1">
                                    {{ selectedLog.description }}
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="12" md="6">
                            <div class="mb-4">
                                <div class="text-caption text-medium-emphasis">
                                    IP Address
                                </div>
                                <div class="text-body-1">
                                    {{ selectedLog.ip_address }}
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="12" md="6">
                            <div class="mb-4">
                                <div class="text-caption text-medium-emphasis">
                                    User Agent
                                </div>
                                <div class="text-caption text-truncate">
                                    {{ selectedLog.user_agent }}
                                </div>
                            </div>
                        </v-col>
                    </v-row>

                    <v-divider class="my-4" />

                    <v-row
                        v-if="selectedLog.old_values || selectedLog.new_values"
                    >
                        <v-col cols="12" md="6" v-if="selectedLog.old_values">
                            <div class="mb-2">
                                <div
                                    class="text-subtitle-2 font-weight-bold mb-2"
                                >
                                    Old Values
                                </div>
                                <v-card variant="outlined">
                                    <v-card-text>
                                        <pre class="text-caption">{{
                                            JSON.stringify(
                                                selectedLog.old_values,
                                                null,
                                                2
                                            )
                                        }}</pre>
                                    </v-card-text>
                                </v-card>
                            </div>
                        </v-col>
                        <v-col cols="12" md="6" v-if="selectedLog.new_values">
                            <div class="mb-2">
                                <div
                                    class="text-subtitle-2 font-weight-bold mb-2"
                                >
                                    New Values
                                </div>
                                <v-card variant="outlined">
                                    <v-card-text>
                                        <pre class="text-caption">{{
                                            JSON.stringify(
                                                selectedLog.new_values,
                                                null,
                                                2
                                            )
                                        }}</pre>
                                    </v-card-text>
                                </v-card>
                            </div>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-divider />

                <v-card-actions class="pa-4">
                    <v-spacer />
                    <v-btn
                        color="primary"
                        variant="elevated"
                        @click="showDetailModal = false"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>

    <v-container v-else fluid>
        <v-alert type="warning" variant="tonal">
            You don't have permission to view logs.
        </v-alert>
    </v-container>
</template>

<style scoped>
.v-btn {
    text-transform: none;
}

pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
