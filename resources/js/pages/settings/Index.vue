<script setup>
import { ref, onMounted } from "vue";
import api from "@/plugins/axios";
import { useSettingsStore } from "@/store/settings";
import { useToast } from "@/composables/useToast";

const toast = useToast();

const settingsStore = useSettingsStore();
const loading = ref(false);
const logoPreview = ref(null);
const logoFile = ref(null);
const successMessage = ref("");
const errorMessage = ref("");

// Form fields
const company_name = ref("");
const company_email = ref("");
const company_phone = ref("");
const company_address = ref("");
const theme = ref("light");
const primary_color = ref("#1976D2");
const secondary_color = ref("#1e448f");
const timezone = ref("UTC");
const date_format = ref("Y-m-d");
const time_format = ref("H:i");

// Validation errors
const errors = ref({});

const timezones = [
    "UTC",
    "America/New_York",
    "America/Chicago",
    "America/Denver",
    "America/Los_Angeles",
    "Europe/London",
    "Europe/Paris",
    "Asia/Dubai",
    "Asia/Tokyo",
    "Australia/Sydney",
];

const dateFormats = [
    { title: "YYYY-MM-DD (2024-01-15)", value: "Y-m-d" },
    { title: "DD/MM/YYYY (15/01/2024)", value: "d/m/Y" },
    { title: "MM/DD/YYYY (01/15/2024)", value: "m/d/Y" },
    { title: "DD-MM-YYYY (15-01-2024)", value: "d-m-Y" },
];

const timeFormats = [
    { title: "24 Hour (14:30)", value: "H:i" },
    { title: "12 Hour (2:30 PM)", value: "h:i A" },
];

const fetchSettings = async () => {
    loading.value = true;
    try {
        const data = await settingsStore.fetchSettings();

        // Populate form
        company_name.value = data.company_name || "";
        company_email.value = data.company_email || "";
        company_phone.value = data.company_phone || "";
        company_address.value = data.company_address || "";
        theme.value = data.theme || "light";
        primary_color.value = data.primary_color || "#1976D2";
        secondary_color.value = data.secondary_color || "#1e448f";
        timezone.value = data.timezone || "UTC";
        date_format.value = data.date_format || "Y-m-d";
        time_format.value = data.time_format || "H:i";
        logoPreview.value = data.company_logo_url;
    } catch (error) {
        console.error("Error fetching settings:", error);
        errorMessage.value = "Failed to load settings";
    } finally {
        loading.value = false;
    }
};

const handleLogoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        logoFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const validateForm = () => {
    errors.value = {};
    let isValid = true;

    if (!company_name.value || company_name.value.trim() === "") {
        errors.value.company_name = "Company name is required";
        isValid = false;
    }

    if (
        company_email.value &&
        !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(company_email.value)
    ) {
        errors.value.company_email = "Invalid email format";
        isValid = false;
    }

    return isValid;
};

const saveSettings = async () => {
    if (!validateForm()) {
        toast.error("Please fix the validation errors");
        errorMessage.value = "Please fix the validation errors";
        return;
    }

    loading.value = true;
    successMessage.value = "";
    errorMessage.value = "";

    try {
        const formData = new FormData();
        formData.append("company_name", company_name.value);
        formData.append("company_email", company_email.value || "");
        formData.append("company_phone", company_phone.value || "");
        formData.append("company_address", company_address.value || "");
        formData.append("theme", theme.value);
        formData.append("primary_color", primary_color.value);
        formData.append("secondary_color", secondary_color.value);
        formData.append("timezone", timezone.value);
        formData.append("date_format", date_format.value);
        formData.append("time_format", time_format.value);

        if (logoFile.value) {
            formData.append("company_logo", logoFile.value);
        }

        const data = await settingsStore.updateSettings(formData);
        logoFile.value = null;

        localStorage.setItem(
            "app_settings",
            JSON.stringify({
                theme: data.theme,
                primary_color: data.primary_color,
                secondary_color: data.secondary_color,
                company_name: data.company_name,
            })
        );

        toast.success("Settings saved successfully! Page will reload...");
        successMessage.value = "Settings saved successfully! Reloading...";

        setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (error) {
        console.error("Error updating settings:", error);
        const errorMsg =
            error.response?.data?.message || "Error updating settings";
        errorMessage.value = errorMsg;
        toast.error(errorMsg);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchSettings();
});
</script>

<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <h2 class="text-h4 font-weight-bold mb-2 text-primary">
                    System Settings
                </h2>
                <p class="text-subtitle-1 text-medium-emphasis">
                    Configure your application settings and preferences
                </p>
            </v-col>
        </v-row>

        <v-row class="mt-4">
            <v-col cols="12">
                <v-card elevation="2">
                    <v-card-text class="pa-6">
                        <v-alert
                            v-if="successMessage"
                            type="success"
                            variant="tonal"
                            class="mb-4"
                            closable
                            @click:close="successMessage = ''"
                        >
                            {{ successMessage }}
                        </v-alert>

                        <v-alert
                            v-if="errorMessage"
                            type="error"
                            variant="tonal"
                            class="mb-4"
                            closable
                            @click:close="errorMessage = ''"
                        >
                            {{ errorMessage }}
                        </v-alert>

                        <form @submit.prevent="saveSettings">
                            <!-- Company Branding -->
                            <h3 class="text-h6 font-weight-bold mb-4">
                                <v-icon color="primary" start
                                    >mdi-domain</v-icon
                                >
                                Company Branding
                            </h3>

                            <div class="mb-6">
                                <div class="d-flex align-center mb-4">
                                    <!-- Rectangular Logo Preview -->
                                    <div class="logo-preview-container mr-4">
                                        <v-img
                                            v-if="logoPreview"
                                            :src="logoPreview"
                                            contain
                                            height="80"
                                            width="200"
                                            class="logo-preview"
                                        />
                                        <div v-else class="logo-placeholder">
                                            <v-icon
                                                size="40"
                                                color="grey-lighten-1"
                                            >
                                                mdi-office-building
                                            </v-icon>
                                        </div>
                                    </div>
                                    <div>
                                        <v-btn
                                            variant="outlined"
                                            size="small"
                                            prepend-icon="mdi-upload"
                                            @click="$refs.logoInput.click()"
                                        >
                                            Upload Logo
                                        </v-btn>
                                        <input
                                            ref="logoInput"
                                            type="file"
                                            accept="image/*"
                                            style="display: none"
                                            @change="handleLogoChange"
                                        />
                                        <p class="text-caption text-grey mt-2">
                                            PNG, JPG, SVG (MAX. 2MB)<br />
                                            Recommended: 400x100px (4:1 ratio)
                                        </p>
                                    </div>
                                </div>

                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="company_name"
                                            label="Company Name *"
                                            variant="outlined"
                                            prepend-inner-icon="mdi-domain"
                                            :error-messages="
                                                errors.company_name
                                            "
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="company_email"
                                            label="Company Email"
                                            type="email"
                                            variant="outlined"
                                            prepend-inner-icon="mdi-email"
                                            :error-messages="
                                                errors.company_email
                                            "
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="company_phone"
                                            label="Company Phone"
                                            variant="outlined"
                                            prepend-inner-icon="mdi-phone"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="company_address"
                                            label="Company Address"
                                            variant="outlined"
                                            prepend-inner-icon="mdi-map-marker"
                                        />
                                    </v-col>
                                </v-row>
                            </div>

                            <v-divider class="my-6" />

                            <!-- Appearance Settings -->
                            <h3 class="text-h6 font-weight-bold mb-4">
                                <v-icon color="primary" start
                                    >mdi-palette</v-icon
                                >
                                Appearance
                            </h3>

                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="theme"
                                        label="Theme"
                                        :items="[
                                            {
                                                title: 'Light Mode',
                                                value: 'light',
                                            },
                                            {
                                                title: 'Dark Mode',
                                                value: 'dark',
                                            },
                                        ]"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-theme-light-dark"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="primary_color"
                                        label="Primary Color"
                                        type="color"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-palette"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="secondary_color"
                                        label="Secondary Color"
                                        type="color"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-palette-outline"
                                    />
                                </v-col>
                            </v-row>

                            <v-divider class="my-6" />

                            <!-- Regional Settings -->
                            <h3 class="text-h6 font-weight-bold mb-4">
                                <v-icon color="primary" start>mdi-earth</v-icon>
                                Regional Settings
                            </h3>

                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="timezone"
                                        label="Timezone"
                                        :items="timezones"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-clock-outline"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="date_format"
                                        label="Date Format"
                                        :items="dateFormats"
                                        item-title="title"
                                        item-value="value"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-calendar"
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="time_format"
                                        label="Time Format"
                                        :items="timeFormats"
                                        item-title="title"
                                        item-value="value"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-clock-time-four-outline"
                                    />
                                </v-col>
                            </v-row>

                            <v-divider class="my-6" />

                            <div class="d-flex justify-end">
                                <v-btn
                                    type="submit"
                                    color="primary"
                                    size="large"
                                    :loading="loading"
                                    prepend-icon="mdi-content-save"
                                >
                                    Save Settings
                                </v-btn>
                            </div>
                        </form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.v-btn {
    text-transform: none;
}

.logo-preview-container {
    width: 200px;
    height: 80px;
    border: 2px dashed #e0e0e0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fafafa;
    overflow: hidden;
}

.logo-preview {
    border-radius: 4px;
}

.logo-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Dark theme styles */
.v-theme--dark .logo-preview-container {
    background-color: #1e1e1e;
    border-color: #1e448f;
}
</style>
