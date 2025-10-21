<template>
    <v-overlay
        :model-value="loadingStore.isLoading"
        class="align-center justify-center"
        persistent
        contained
    >
        <div class="text-center">
            <v-progress-circular
                color="primary"
                indeterminate
                size="64"
                width="6"
            ></v-progress-circular>
            <p class="text-white mt-4 text-h6">Loading...</p>
        </div>
    </v-overlay>
</template>

<script setup>
import { useLoadingStore } from "@/store/loading";

const loadingStore = useLoadingStore();
</script>

<style scoped>
.v-overlay {
    z-index: 9999;
}
</style>
