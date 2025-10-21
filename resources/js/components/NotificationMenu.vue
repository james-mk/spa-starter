<template>
    <v-menu>
        <template #activator="{ props }">
            <v-btn v-bind="props" icon>
                <v-icon>mdi-bell</v-icon>
            </v-btn>
        </template>

        <v-list>
            <v-list-item>
                <v-list-item-title>No new notifications</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script setup>
// This is a stub notification menu.
// Later, you can load notifications from the API.
</script>
