<template>
    <v-app>
        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>

<script setup>
// no logic needed here — this is a wrapper
</script>

<style scoped>
v-main {
    background-color: #f9fafb;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
