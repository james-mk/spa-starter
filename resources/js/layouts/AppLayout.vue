<template>
    <v-app>
        <TopBar @toggle-sidebar="drawer = !drawer" />
        <SideBar v-model="drawer" />

        <v-main>
            <v-container fluid class="pa-4">
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref } from "vue";
import TopBar from "@/components/TopBar.vue";
import SideBar from "@/components/SideBar.vue";

const drawer = ref(true);
</script>
