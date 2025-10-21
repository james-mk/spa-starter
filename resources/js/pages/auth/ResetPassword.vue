<template>
    <v-app :theme="currentTheme">
        <div class="reset-password-wrapper">
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

            <!-- Right Side - Reset Password Form -->
            <div class="reset-password-section">
                <div class="reset-password-content">
                    <v-card elevation="0" class="reset-password-card">
                        <div class="text-center mb-8">
                            <h3 class="text-h4 font-weight-bold mb-2">
                                Reset Password
                            </h3>
                            <p class="text-subtitle-1 text-medium-emphasis">
                                Enter your new password
                            </p>
                        </div>

                        <v-form @submit.prevent="resetPassword">
                            <v-text-field
                                v-model="password"
                                label="New Password"
                                type="password"
                                prepend-inner-icon="mdi-lock-outline"
                                variant="outlined"
                                density="comfortable"
                                required
                                class="mb-2"
                                color="primary"
                                :error-messages="passwordError"
                            />

                            <!-- Password Strength Meter -->
                            <div v-if="password" class="mb-4">
                                <div
                                    class="d-flex justify-space-between align-center mb-2"
                                >
                                    <span class="text-caption"
                                        >Password Strength:</span
                                    >
                                    <span
                                        class="text-caption font-weight-bold"
                                        :class="`text-${passwordStrength.color}`"
                                    >
                                        {{ passwordStrength.label }}
                                    </span>
                                </div>
                                <v-progress-linear
                                    :model-value="passwordStrength.strength"
                                    :color="passwordStrength.color"
                                    height="8"
                                    rounded
                                />

                                <!-- Password Requirements Checklist -->
                                <div class="mt-3">
                                    <div class="text-caption mb-1">
                                        <v-icon
                                            size="small"
                                            :color="
                                                passwordRequirements.minLength
                                                    ? 'success'
                                                    : 'grey'
                                            "
                                        >
                                            {{
                                                passwordRequirements.minLength
                                                    ? "mdi-check-circle"
                                                    : "mdi-circle-outline"
                                            }}
                                        </v-icon>
                                        At least 8 characters
                                    </div>
                                    <div class="text-caption mb-1">
                                        <v-icon
                                            size="small"
                                            :color="
                                                passwordRequirements.hasLowercase
                                                    ? 'success'
                                                    : 'grey'
                                            "
                                        >
                                            {{
                                                passwordRequirements.hasLowercase
                                                    ? "mdi-check-circle"
                                                    : "mdi-circle-outline"
                                            }}
                                        </v-icon>
                                        One lowercase letter
                                    </div>
                                    <div class="text-caption mb-1">
                                        <v-icon
                                            size="small"
                                            :color="
                                                passwordRequirements.hasUppercase
                                                    ? 'success'
                                                    : 'grey'
                                            "
                                        >
                                            {{
                                                passwordRequirements.hasUppercase
                                                    ? "mdi-check-circle"
                                                    : "mdi-circle-outline"
                                            }}
                                        </v-icon>
                                        One uppercase letter
                                    </div>
                                    <div class="text-caption mb-1">
                                        <v-icon
                                            size="small"
                                            :color="
                                                passwordRequirements.hasNumber
                                                    ? 'success'
                                                    : 'grey'
                                            "
                                        >
                                            {{
                                                passwordRequirements.hasNumber
                                                    ? "mdi-check-circle"
                                                    : "mdi-circle-outline"
                                            }}
                                        </v-icon>
                                        One number
                                    </div>
                                    <div class="text-caption">
                                        <v-icon
                                            size="small"
                                            :color="
                                                passwordRequirements.hasSpecial
                                                    ? 'success'
                                                    : 'grey'
                                            "
                                        >
                                            {{
                                                passwordRequirements.hasSpecial
                                                    ? "mdi-check-circle"
                                                    : "mdi-circle-outline"
                                            }}
                                        </v-icon>
                                        One special character (!@#$%^&*)
                                    </div>
                                </div>
                            </div>

                            <v-text-field
                                v-model="password_confirmation"
                                label="Confirm Password"
                                type="password"
                                prepend-inner-icon="mdi-lock-check-outline"
                                variant="outlined"
                                density="comfortable"
                                required
                                class="mb-4"
                                color="primary"
                                :error-messages="passwordConfirmationError"
                            />

                            <v-btn
                                type="submit"
                                color="primary"
                                size="x-large"
                                block
                                :loading="loading"
                                :disabled="!isFormValid"
                                class="reset-password-btn mb-4"
                                elevation="0"
                            >
                                <v-icon start>mdi-lock-reset</v-icon>
                                Reset Password
                            </v-btn>

                            <div class="text-center">
                                <v-btn
                                    variant="text"
                                    color="primary"
                                    @click="$router.push({ name: 'login' })"
                                >
                                    <v-icon start>mdi-arrow-left</v-icon>
                                    Back to Login
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

                        <v-alert
                            v-if="success"
                            type="success"
                            variant="tonal"
                            class="mt-4"
                        >
                            {{ success }}
                        </v-alert>
                    </v-card>

                    <!-- Footer -->
                    <div class="reset-password-footer">
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
import { useRoute, useRouter } from "vue-router";
import api from "@/plugins/axios";
import { useColorUtils } from "@/composables/useColorUtils";
import { useTheme } from "vuetify";
import { useToast } from "@/composables/useToast";

const toast = useToast();

const route = useRoute();
const router = useRouter();
const { getContrastColor } = useColorUtils();
const theme = useTheme();

const token = route.params.token;
const email = route.query.email || "";
const password = ref("");
const password_confirmation = ref("");
const error = ref("");
const success = ref("");
const loading = ref(false);
const settings = ref(null);
const currentTheme = ref("light");
const passwordError = ref("");
const passwordConfirmationError = ref("");

const companyName = computed(() => {
    const name = settings.value?.company_name;
    return name && name.trim() !== "" ? name : "";
});

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

// Password strength calculation
const passwordStrength = computed(() => {
    if (!password.value) return { strength: 0, label: "", color: "" };

    let strength = 0;
    const pass = password.value;

    // Length check
    if (pass.length >= 8) strength += 20;
    if (pass.length >= 12) strength += 20;

    // Character variety checks
    if (/[a-z]/.test(pass)) strength += 15;
    if (/[A-Z]/.test(pass)) strength += 15;
    if (/[0-9]/.test(pass)) strength += 15;
    if (/[^A-Za-z0-9]/.test(pass)) strength += 15;

    // Determine label and color
    let label = "";
    let color = "";

    if (strength <= 20) {
        label = "Very Weak";
        color = "error";
    } else if (strength <= 40) {
        label = "Weak";
        color = "warning";
    } else if (strength <= 60) {
        label = "Fair";
        color = "info";
    } else if (strength <= 80) {
        label = "Good";
        color = "success";
    } else {
        label = "Strong";
        color = "success";
    }

    return { strength, label, color };
});

// Password requirements check
const passwordRequirements = computed(() => {
    const pass = password.value || "";
    return {
        minLength: pass.length >= 8,
        hasLowercase: /[a-z]/.test(pass),
        hasUppercase: /[A-Z]/.test(pass),
        hasNumber: /[0-9]/.test(pass),
        hasSpecial: /[^A-Za-z0-9]/.test(pass),
    };
});

// Form validation
const isFormValid = computed(() => {
    if (!password.value || !password_confirmation.value) return false;

    // Check all password requirements
    const req = passwordRequirements.value;
    if (
        !req.minLength ||
        !req.hasLowercase ||
        !req.hasUppercase ||
        !req.hasNumber ||
        !req.hasSpecial
    ) {
        return false;
    }

    // Check if passwords match
    if (password.value !== password_confirmation.value) {
        return false;
    }

    return true;
});

// Validate password
const validatePassword = () => {
    passwordError.value = "";

    if (!password.value) {
        passwordError.value = "Password is required";
        return false;
    }

    if (password.value.length < 8) {
        passwordError.value = "Password must be at least 8 characters";
        return false;
    }

    if (!/[a-z]/.test(password.value)) {
        passwordError.value =
            "Password must contain at least one lowercase letter";
        return false;
    }

    if (!/[A-Z]/.test(password.value)) {
        passwordError.value =
            "Password must contain at least one uppercase letter";
        return false;
    }

    if (!/[0-9]/.test(password.value)) {
        passwordError.value = "Password must contain at least one number";
        return false;
    }

    if (!/[^A-Za-z0-9]/.test(password.value)) {
        passwordError.value =
            "Password must contain at least one special character";
        return false;
    }

    return true;
};

// Validate password confirmation
const validatePasswordConfirmation = () => {
    passwordConfirmationError.value = "";

    if (!password_confirmation.value) {
        passwordConfirmationError.value = "Password confirmation is required";
        return false;
    }

    if (password.value !== password_confirmation.value) {
        passwordConfirmationError.value = "Passwords must match";
        return false;
    }

    return true;
};

// Load cached settings immediately before component mounts
const loadCachedSettings = () => {
    try {
        const cachedSettings = localStorage.getItem("app_settings");
        if (cachedSettings) {
            const cached = JSON.parse(cachedSettings);

            if (cached.theme) {
                currentTheme.value = cached.theme;
                theme.global.name.value = cached.theme;
            }

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

        //apply secondary color
        if (data.secondary_color) {
            theme.themes.value.light.colors.secondary = data.secondary_color;
            theme.themes.value.dark.colors.secondary = data.secondary_color;
        }

        // Update cache
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
    }
};

const resetPassword = async () => {
    // Validate form
    const isPasswordValid = validatePassword();
    const isConfirmationValid = validatePasswordConfirmation();

    if (!isPasswordValid || !isConfirmationValid) {
        toast.error("Please fix the validation errors");
        return;
    }

    loading.value = true;
    error.value = "";
    success.value = "";

    try {
        const res = await api.post("/reset-password", {
            token,
            email,
            password: password.value,
            password_confirmation: password_confirmation.value,
        });

        const successMsg = res.data.message || "Password reset successfully!";
        success.value = successMsg;
        toast.success(successMsg);

        setTimeout(() => {
            router.push({ name: "login" });
        }, 2000);
    } catch (err) {
        console.error("Reset password error:", err);
        const errorMsg = err.response?.data?.message || "Something went wrong";
        error.value = errorMsg;
        toast.error(errorMsg);
    } finally {
        loading.value = false;
    }
};

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
.reset-password-wrapper {
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

/* Right Reset Password Section */
.reset-password-section {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgb(var(--v-theme-background));
    padding: 40px;
    overflow-y: auto;
}

.reset-password-content {
    width: 100%;
    max-width: 480px;
}

.reset-password-card {
    background: transparent !important;
    padding: 0;
}

.reset-password-btn {
    text-transform: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 8px;
}

.reset-password-footer {
    text-align: center;
    margin-top: 32px;
}

/* Responsive Design */
@media (max-width: 960px) {
    .reset-password-wrapper {
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

    .reset-password-section {
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

    .reset-password-section {
        padding: 24px 16px;
    }
}
</style>
