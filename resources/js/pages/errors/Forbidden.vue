<template>
    <div class="error-container">
        <v-container>
            <v-row justify="center" align="center" style="min-height: 100vh">
                <v-col cols="12" md="6" class="text-center">
                    <v-icon size="120" color="error" class="mb-6">
                        mdi-shield-lock-outline
                    </v-icon>
                    <h1 class="text-h2 font-weight-bold mb-4">403</h1>
                    <h2 class="text-h4 mb-4">Access Forbidden</h2>
                    <p class="text-body-1 text-grey mb-6">
                        Sorry, you don't have permission to access this page.
                        Please contact your administrator if you believe this is
                        an error.
                    </p>
                    <v-btn
                        color="primary"
                        size="large"
                        variant="elevated"
                        @click="$router.push('/')"
                        prepend-icon="mdi-home"
                    >
                        Back to Dashboard
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script setup>
// Component logic if needed
</script>

<style scoped>
.error-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
</style>
