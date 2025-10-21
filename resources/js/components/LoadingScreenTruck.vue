<template>
    <v-overlay
        :model-value="loadingStore.isLoading"
        class="loading-overlay"
        persistent
        scrim="black"
        :opacity="0.9"
    >
        <div class="loading-content">
            <div class="spinner-container">
                <v-icon size="80" color="primary" class="truck-icon">
                    mdi-truck-fast
                </v-icon>
                <v-progress-circular
                    indeterminate
                    size="120"
                    width="4"
                    color="primary"
                    class="spinner-circle"
                ></v-progress-circular>
            </div>
            <h3 class="text-h6 text-white mt-6">Loading...</h3>
        </div>
    </v-overlay>
</template>

<script setup>
import { useLoadingStore } from "@/store/loading";

const loadingStore = useLoadingStore();
</script>

<style scoped>
.loading-overlay {
    z-index: 9999;
}

.loading-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.spinner-container {
    position: relative;
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.truck-icon {
    position: absolute;
    animation: bounce 1s infinite ease-in-out;
    z-index: 1;
}

.spinner-circle {
    position: absolute;
}

@keyframes bounce {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style>
