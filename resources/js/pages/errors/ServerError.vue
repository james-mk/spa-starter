<template>
    <v-container
        class="fill-height d-flex align-center justify-center text-center"
    >
        <div>
            <v-icon size="96" color="error">mdi-alert-octagram</v-icon>
            <h1 class="text-h3 mt-4">500 - Server Error</h1>
            <p class="text-subtitle-1 mb-6">
                Something went wrong on our end. Please try again later.
            </p>
            <v-btn color="error" @click="reload">
                <v-icon left>mdi-refresh</v-icon> Reload Page
            </v-btn>
        </div>
    </v-container>
</template>

<script setup>
function reload() {
    window.location.reload();
}
</script>

<style scoped>
.fill-height {
    min-height: 100vh;
}
</style>
