<template>
    <v-overlay
        :model-value="loadingStore.isLoading"
        class="loading-overlay"
        persistent
        scrim="black"
        :opacity="0.9"
    >
        <div class="loading-content">
            <div class="hourglass-container">
                <v-icon size="80" color="primary" class="hourglass-icon">
                    mdi-timer-sand
                </v-icon>
            </div>
            <h3 class="text-h6 text-white mt-4">Loading...</h3>
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

.hourglass-container {
    animation: flip 2s infinite ease-in-out;
}

@keyframes flip {
    0%,
    100% {
        transform: rotate(0deg);
    }
    50% {
        transform: rotate(180deg);
    }
}

.hourglass-icon {
    animation: pulse 1s infinite ease-in-out;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}
</style>
