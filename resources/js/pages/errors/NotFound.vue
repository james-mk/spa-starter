<template>
    <v-container
        class="fill-height d-flex align-center justify-center text-center"
    >
        <div>
            <v-icon size="96" color="primary">mdi-alert-circle-outline</v-icon>
            <h1 class="text-h3 mt-4">404 - Page Not Found</h1>
            <p class="text-subtitle-1 mb-6">
                The page you’re looking for doesn’t exist or has been moved.
            </p>
            <v-btn color="primary" @click="goHome">
                <v-icon left>mdi-home</v-icon> Go to Dashboard
            </v-btn>
        </div>
    </v-container>
</template>

<script setup>
import { useRouter } from "vue-router";
const router = useRouter();

function goHome() {
    router.push("/");
}
</script>

<style scoped>
.fill-height {
    min-height: 100vh;
}
</style>
