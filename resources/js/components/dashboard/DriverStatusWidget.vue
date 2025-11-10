<template>
    <v-card>
        <v-card-title class="d-flex align-center">
            <v-icon left color="primary">mdi-account-hard-hat</v-icon>
            <span class="ml-2">Driver Status Overview</span>
        </v-card-title>
        <v-card-text>
            <v-row v-if="!loading">
                <v-col
                    v-for="status in statusSummary"
                    :key="status.id"
                    cols="12"
                    sm="6"
                    md="4"
                >
                    <v-card
                        variant="outlined"
                        :color="getStatusColor(status.name)"
                        class="pa-3"
                    >
                        <div class="text-h4 font-weight-bold">
                            {{ status.count }}
                        </div>
                        <div class="text-subtitle-2">
                            {{ status.name }}
                        </div>
                    </v-card>
                </v-col>
            </v-row>
            <v-progress-circular
                v-else
                indeterminate
                color="primary"
            ></v-progress-circular>
        </v-card-text>
    </v-card>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/plugins/axios";

const loading = ref(false);
const statusSummary = ref([]);

const fetchStatusSummary = async () => {
    loading.value = true;
    try {
        const { data } = await api.get("/drivers/status-summary");
        statusSummary.value = data;
    } catch (error) {
        console.error("Failed to fetch status summary", error);
    } finally {
        loading.value = false;
    }
};

const getStatusColor = (statusName) => {
    if (!statusName) return "grey";

    const status = statusName.toLowerCase();
    if (status.includes("active")) return "success";
    if (status.includes("inactive") || status.includes("off")) return "grey";
    if (status.includes("suspended") || status.includes("terminated"))
        return "error";
    if (status.includes("leave") || status.includes("vacation"))
        return "warning";

    return "primary";
};

onMounted(() => {
    fetchStatusSummary();
});
</script>
