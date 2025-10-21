<template>
    <v-app :theme="currentTheme">
        <div class="login-wrapper">
            <!-- Left Side - Branding Section -->
            <div class="branding-section" :style="brandingSectionStyle">
                <div class="branding-overlay" :style="overlayStyle">
                    <div class="branding-content">
                        <!-- Company Logo -->
                        <div class="logo-container mb-8">
                            <v-img
                                v-if="companyLogo"
                                :src="companyLogo"
                                max-height="80"
                                max-width="250"
                                contain
                                class="company-logo"
                            />
                            <!-- <div v-else class="logo-fallback">
                                <v-avatar size="80" color="white" class="mb-4">
                                    <span
                                        class="text-h4 font-weight-bold"
                                        style="color: #1976d2"
                                    >
                                        {{ companyInitials }}
                                    </span>
                                </v-avatar>
                                <h1 class="text-h4 font-weight-bold white-text">
                                    {{ companyName }}
                                </h1>
                            </div> -->
                        </div>

                        <!-- Welcome Text -->
                        <h2
                            class="text-h3 font-weight-bold white-text mb-4"
                            style="color: white !important"
                        >
                            Welcome to {{ companyName || "MFL IFMS" }}
                        </h2>
                        <p
                            class="text-h6 white-text mb-8 subtitle-text"
                            style="color: white !important"
                        >
                            Fleet Management System
                        </p>

                        <!-- Features List -->
                        <div class="features-list">
                            <div class="feature-item">
                                <v-icon color="white" size="24" class="mr-3"
                                    >mdi-check-circle</v-icon
                                >
                                <span
                                    class="white-text"
                                    style="color: white !important"
                                    >Complete driver profile & document
                                    management</span
                                >
                            </div>
                            <div class="feature-item">
                                <v-icon color="white" size="24" class="mr-3"
                                    >mdi-check-circle</v-icon
                                >
                                <span
                                    class="white-text"
                                    style="color: white !important"
                                    >Transparent payment & deduction
                                    system</span
                                >
                            </div>
                            <div class="feature-item">
                                <v-icon color="white" size="24" class="mr-3"
                                    >mdi-check-circle</v-icon
                                >
                                <span
                                    class="white-text"
                                    style="color: white !important"
                                    >Evidence-based incident recording</span
                                >
                            </div>
                            <div class="feature-item">
                                <v-icon color="white" size="24" class="mr-3"
                                    >mdi-check-circle</v-icon
                                >
                                <span
                                    class="white-text"
                                    style="color: white !important"
                                    >Automatic, powerful reporting powered by
                                    exhaustive predefined taxonomy</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-section">
                <div class="login-content">
                    <v-card elevation="0" class="login-card">
                        <div class="text-center mb-8">
                            <h3 class="text-h4 font-weight-bold mb-2">
                                Sign In
                            </h3>
                            <p class="text-subtitle-1 text-medium-emphasis">
                                Access your file management dashboard
                            </p>
                        </div>

                        <v-form @submit.prevent="submit">
                            <v-text-field
                                v-model="email"
                                label="Username"
                                type="email"
                                prepend-inner-icon="mdi-account"
                                variant="outlined"
                                density="comfortable"
                                required
                                class="mb-4"
                                color="primary"
                            />

                            <v-text-field
                                v-model="password"
                                label="Password"
                                type="password"
                                prepend-inner-icon="mdi-lock"
                                variant="outlined"
                                density="comfortable"
                                required
                                class="mb-4"
                                color="primary"
                            />

                            <v-btn
                                type="submit"
                                color="primary"
                                size="x-large"
                                block
                                :loading="loading"
                                class="login-btn mb-4"
                                elevation="0"
                            >
                                Sign In
                            </v-btn>

                            <div class="text-center">
                                <v-btn
                                    variant="text"
                                    color="primary"
                                    @click="
                                        $router.push({
                                            name: 'forgot-password',
                                        })
                                    "
                                >
                                    Forgot your password?
                                </v-btn>
                            </div>
                        </v-form>

                        <v-alert
                            v-if="error"
                            type="error"
                            variant="tonal"
                            class="mt-4"
                        >
                            {{ error }}
                        </v-alert>
                    </v-card>

                    <!-- Footer -->
                    <div class="login-footer">
                        <p class="text-caption text-medium-emphasis">
                            © {{ new Date().getFullYear() }}
                            {{ companyName || "MURANGA FORWARDERS LTD" }} | All
                            rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-app>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeMount } from "vue";
import { useRouter } from "vue-router";
import api from "../../plugins/axios";
import { useColorUtils } from "@/composables/useColorUtils";
import { useTheme } from "vuetify";
import { useToast } from "@/composables/useToast";

const toast = useToast();

const router = useRouter();
const { getContrastColor } = useColorUtils();
const theme = useTheme();

const email = ref("");
const password = ref("");
const loading = ref(false);
const error = ref("");
const settings = ref(null);
const currentTheme = ref("light");

const companyName = computed(() => settings.value?.company_name || "");
const companyLogo = computed(() => settings.value?.company_logo_url || null);
const companyInitials = computed(() => settings.value?.company_initials || "");
const primaryColor = computed(() => settings.value?.primary_color || "#1976d2");
const secondaryColor = computed(
    () => settings.value?.secondary_color || "#1e448f"
);

const textColor = computed(() => {
    return getContrastColor(primaryColor.value);
});

// Background image with fleet
const brandingSectionStyle = computed(() => ({
    backgroundImage:
        'url("https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=1200&q=80")',
    backgroundSize: "cover",
    backgroundPosition: "center",
}));

// Overlay style with primary color or blue
const overlayStyle = computed(() => ({
    backgroundColor: primaryColor.value
        ? `${primaryColor.value}ee`
        : "#1976d2ee",
}));

// Load cached settings immediately before component mounts
const loadCachedSettings = () => {
    try {
        const cachedSettings = localStorage.getItem("app_settings");
        if (cachedSettings) {
            const cached = JSON.parse(cachedSettings);

            // Apply theme immediately
            if (cached.theme) {
                currentTheme.value = cached.theme;
                theme.global.name.value = cached.theme;
            }

            // Apply colors immediately
            if (cached.primary_color) {
                theme.themes.value.light.colors.primary = cached.primary_color;
                theme.themes.value.dark.colors.primary = cached.primary_color;
            }

            if (cached.secondary_color) {
                theme.themes.value.light.colors.secondary =
                    cached.secondary_color;
                theme.themes.value.dark.colors.secondary =
                    cached.secondary_color;
            }

            // Set temporary settings for immediate display
            settings.value = {
                company_name: cached.company_name || "",
                company_logo_url: cached.company_logo_url || null,
                company_initials: cached.company_initials || "",
                primary_color: cached.primary_color || "#1976d2",
                secondary_color: cached.secondary_color || "#1e448f",
                theme: cached.theme || "light",
            };
        }
    } catch (error) {
        console.error("Error loading cached settings:", error);
    }
};

// Fetch public settings
const fetchPublicSettings = async () => {
    try {
        const { data } = await api.get("/settings/public");
        settings.value = data;

        // Apply theme
        if (data.theme) {
            currentTheme.value = data.theme;
            theme.global.name.value = data.theme;
        }

        // Apply primary color
        if (data.primary_color) {
            theme.themes.value.light.colors.primary = data.primary_color;
            theme.themes.value.dark.colors.primary = data.primary_color;
        }

        //Apply secondary color
        if (data.secondary_color) {
            theme.themes.value.light.colors.secondary = data.secondary_color;
            theme.themes.value.dark.colors.secondary = data.secondary_color;
        }

        // Update cache with fresh data
        localStorage.setItem(
            "app_settings",
            JSON.stringify({
                theme: data.theme,
                primary_color: data.primary_color,
                secondary_color: data.secondary_color,
                company_name: data.company_name,
                company_logo_url: data.company_logo_url,
                company_initials: data.company_initials,
            })
        );
    } catch (err) {
        console.error("Error fetching settings:", err);
        // Use defaults if settings fetch fails
    }
};

async function submit() {
    loading.value = true;
    error.value = "";

    try {
        const response = await api.post("/login", {
            email: email.value,
            password: password.value,
        });

        const token = response.data.token;
        const user = response.data.user;

        if (!token) {
            throw new Error("No token received from server");
        }

        localStorage.setItem("token", token);
        localStorage.setItem("user", JSON.stringify(user));

        try {
            const settingsResponse = await api.get("/settings");
            localStorage.setItem(
                "app_settings",
                JSON.stringify({
                    theme: settingsResponse.data.theme,
                    primary_color: settingsResponse.data.primary_color,
                    secondary_color: settingsResponse.data.secondary_color,
                    company_name: settingsResponse.data.company_name,
                    company_logo_url: settingsResponse.data.company_logo_url,
                    company_initials: settingsResponse.data.company_initials,
                })
            );
        } catch (e) {
            console.error("Failed to cache settings:", e);
        }

        email.value = "";
        password.value = "";

        toast.success("Login successful! Redirecting...");

        window.location.href = "/";
    } catch (err) {
        console.error("Login error:", err);
        const errorMsg =
            err.response?.data?.message || err.message || "Invalid credentials";
        error.value = errorMsg;
        toast.error(errorMsg);
    } finally {
        loading.value = false;
    }
}

// Load cached settings BEFORE mount (synchronous)
onBeforeMount(() => {
    loadCachedSettings();
});

// Fetch fresh settings AFTER mount
onMounted(() => {
    fetchPublicSettings();
});
</script>

<style scoped>
.login-wrapper {
    display: flex;
    min-height: 100vh;
    height: 100vh;
    width: 100vw;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
}

/* Left Branding Section */
.branding-section {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.branding-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px;
}

.branding-content {
    max-width: 600px;
    z-index: 1;
}

.logo-container {
    text-align: center;
}

.logo-fallback {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.white-text {
    color: white !important;
}

.subtitle-text {
    opacity: 0.95;
    font-weight: 400;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 40px;
}

.feature-item {
    display: flex;
    align-items: center;
    font-size: 1.1rem;
}

/* Right Login Section */
.login-section {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgb(var(--v-theme-background));
    padding: 40px;
    overflow-y: auto;
}

.login-content {
    width: 100%;
    max-width: 480px;
}

.login-card {
    background: transparent !important;
    padding: 0;
}

.login-btn {
    text-transform: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 8px;
    background-color: rgb(var(--v-primary));
    color: white;
}

.login-footer {
    text-align: center;
    margin-top: 32px;
}

/* Responsive Design */
@media (max-width: 960px) {
    .login-wrapper {
        flex-direction: column;
    }

    .branding-section {
        min-height: 300px;
    }

    .branding-overlay {
        padding: 40px 20px;
    }

    .branding-content {
        max-width: 100%;
    }

    .branding-content h2 {
        font-size: 1.75rem;
    }

    .branding-content .text-h6 {
        font-size: 1rem;
    }

    .features-list {
        margin-top: 24px;
        gap: 16px;
    }

    .feature-item {
        font-size: 0.95rem;
    }

    .login-section {
        padding: 32px 20px;
    }
}

@media (max-width: 600px) {
    .branding-section {
        min-height: 250px;
    }

    .branding-overlay {
        padding: 30px 16px;
    }

    .features-list {
        display: none;
    }

    .login-section {
        padding: 24px 16px;
    }
}
</style>
