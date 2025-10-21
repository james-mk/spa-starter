<script setup>
import { ref, onMounted, computed } from "vue";
import { useField, useForm } from "vee-validate";
import * as yup from "yup";
import api from "@/plugins/axios";
import { useRouter } from "vue-router";
import { useToast } from "@/composables/useToast";

const toast = useToast();
const router = useRouter();

const user = ref(null);
const loading = ref(false);
const passwordLoading = ref(false);
const imagePreview = ref(null);
const imageFile = ref(null);
const activeTab = ref("profile");
const successMessage = ref("");
const errorMessage = ref("");

// Profile Form Schema
const profileSchema = yup.object({
    first_name: yup.string().required("First name is required"),
    last_name: yup.string().required("Last name is required"),
    email: yup.string().email("Invalid email").required("Email is required"),
    user_type: yup.string(),
});

const { handleSubmit: handleProfileSubmit, resetForm: resetProfileForm } =
    useForm({
        validationSchema: profileSchema,
        initialValues: {
            first_name: "",
            last_name: "",
            email: "",
            user_type: "",
        },
    });

const { value: first_name, errorMessage: firstNameError } =
    useField("first_name");
const { value: last_name, errorMessage: lastNameError } = useField("last_name");
const { value: email, errorMessage: emailError } = useField("email");
const { value: user_type } = useField("user_type");

// Enhanced Password Form Schema with strict validation
const passwordSchema = yup.object({
    current_password: yup.string().required("Current password is required"),
    password: yup
        .string()
        .required("New password is required")
        .min(8, "Password must be at least 8 characters")
        .matches(/[a-z]/, "Password must contain at least one lowercase letter")
        .matches(/[A-Z]/, "Password must contain at least one uppercase letter")
        .matches(/[0-9]/, "Password must contain at least one number")
        .matches(
            /[^A-Za-z0-9]/,
            "Password must contain at least one special character (!@#$%^&*)"
        ),
    password_confirmation: yup
        .string()
        .required("Password confirmation is required")
        .oneOf([yup.ref("password")], "Passwords must match"),
});

const { handleSubmit: handlePasswordSubmit, resetForm: resetPasswordForm } =
    useForm({
        validationSchema: passwordSchema,
        initialValues: {
            current_password: "",
            password: "",
            password_confirmation: "",
        },
    });

const { value: current_password, errorMessage: currentPasswordError } =
    useField("current_password");
const { value: password, errorMessage: passwordError } = useField("password");
const {
    value: password_confirmation,
    errorMessage: passwordConfirmationError,
} = useField("password_confirmation");

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

const fullName = computed(() => {
    if (user.value) {
        return `${user.value.first_name} ${user.value.last_name}`;
    }
    return "";
});

const fetchProfile = async () => {
    loading.value = true;
    try {
        const { data } = await api.get("/profile");
        user.value = data;

        // Populate form fields
        first_name.value = data.first_name;
        last_name.value = data.last_name;
        email.value = data.email;
        user_type.value = data.user_type || "";
        imagePreview.value = data.profile_image_url;
    } catch (error) {
        console.error("Error fetching profile:", error);
        toast.error("Error loading profile");
    } finally {
        loading.value = false;
    }
};

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        imageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const updateProfile = handleProfileSubmit(async (values) => {
    loading.value = true;
    errorMessage.value = "";
    successMessage.value = "";

    try {
        const formData = new FormData();
        formData.append("first_name", values.first_name);
        formData.append("last_name", values.last_name);
        formData.append("email", values.email);
        formData.append("user_type", values.user_type || "");

        if (imageFile.value) {
            formData.append("profile_image", imageFile.value);
        }

        const { data } = await api.post("/profile", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        localStorage.setItem("user", JSON.stringify(data));
        user.value = data;
        imagePreview.value = data.profile_image_url;
        imageFile.value = null;

        toast.success("Profile updated successfully!");
        successMessage.value = "Profile updated successfully!";
    } catch (error) {
        console.error("Error updating profile:", error);
        const errorMsg =
            error.response?.data?.message || "Error updating profile";
        errorMessage.value = errorMsg;
        toast.error(errorMsg);
    } finally {
        loading.value = false;
    }
});

const updatePassword = handlePasswordSubmit(
    async (values) => {
        passwordLoading.value = true;
        errorMessage.value = "";
        successMessage.value = "";

        try {
            await api.post("/profile/password", {
                current_password: values.current_password,
                password: values.password,
                password_confirmation: values.password_confirmation,
            });

            resetPasswordForm();

            toast.success("Password updated successfully!");
            successMessage.value = "Password updated successfully!";
        } catch (error) {
            console.error("Error updating password:", error);
            const errorMsg =
                error.response?.data?.message ||
                error.response?.data?.errors?.current_password?.[0] ||
                "Error updating password";
            errorMessage.value = errorMsg;
            toast.error(errorMsg);
        } finally {
            passwordLoading.value = false;
        }
    },
    (errors) => {
        console.log("Validation errors:", errors);
        toast.error("Please fix the password validation errors");
    }
);

onMounted(() => {
    fetchProfile();
});
</script>

<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <h2 class="text-h4 font-weight-bold mb-2 text-primary">
                    My Profile
                </h2>
                <p class="text-subtitle-1 text-medium-emphasis">
                    Manage your account settings
                </p>
            </v-col>
        </v-row>

        <v-row class="mt-4">
            <!-- Profile Card -->
            <v-col cols="12" md="4">
                <v-card elevation="2">
                    <v-card-text class="text-center pa-6">
                        <v-avatar
                            size="120"
                            color="grey-lighten-2"
                            class="mb-4"
                        >
                            <v-img
                                v-if="imagePreview"
                                :src="imagePreview"
                                cover
                            />
                            <v-icon v-else size="60" color="grey"
                                >mdi-account</v-icon
                            >
                        </v-avatar>

                        <h3 class="text-h5 font-weight-bold mb-2">
                            {{ fullName }}
                        </h3>
                        <p class="text-body-2 text-medium-emphasis mb-2">
                            {{ user?.email }}
                        </p>
                        <v-chip
                            v-if="user?.user_type"
                            size="small"
                            color="primary"
                            variant="tonal"
                            class="mb-4"
                        >
                            {{ user.user_type }}
                        </v-chip>

                        <v-divider class="my-4" />

                        <div class="text-left">
                            <h4 class="text-subtitle-1 font-weight-bold mb-3">
                                Roles
                            </h4>
                            <v-chip
                                v-for="role in user?.roles"
                                :key="role.id"
                                size="small"
                                color="secondary"
                                variant="tonal"
                                class="ma-1"
                            >
                                {{ role.name }}
                            </v-chip>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- Settings Card -->
            <v-col cols="12" md="8">
                <v-card elevation="2">
                    <v-tabs v-model="activeTab" bg-color="primary">
                        <v-tab value="profile">
                            <v-icon start>mdi-account-edit</v-icon>
                            Profile Information
                        </v-tab>
                        <v-tab value="password">
                            <v-icon start>mdi-lock-reset</v-icon>
                            Change Password
                        </v-tab>
                    </v-tabs>

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

                        <!-- Profile Tab -->
                        <v-window v-model="activeTab">
                            <v-window-item value="profile">
                                <form @submit.prevent="updateProfile">
                                    <div class="text-center mb-6">
                                        <v-btn
                                            variant="outlined"
                                            size="small"
                                            prepend-icon="mdi-camera"
                                            @click="$refs.fileInput.click()"
                                        >
                                            Change Photo
                                        </v-btn>
                                        <input
                                            ref="fileInput"
                                            type="file"
                                            accept="image/*"
                                            style="display: none"
                                            @change="handleImageChange"
                                        />
                                        <p class="text-caption text-grey mt-2">
                                            JPG, PNG or GIF (MAX. 2MB)
                                        </p>
                                    </div>

                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="first_name"
                                                label="First Name"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-account"
                                                :error-messages="firstNameError"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="last_name"
                                                label="Last Name"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-account"
                                                :error-messages="lastNameError"
                                            />
                                        </v-col>
                                    </v-row>

                                    <v-text-field
                                        v-model="email"
                                        label="Email"
                                        type="email"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-email"
                                        :error-messages="emailError"
                                    />

                                    <v-text-field
                                        v-model="user_type"
                                        label="User Type"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-briefcase"
                                        placeholder="e.g., Admin, Manager, Driver"
                                    />

                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        size="large"
                                        block
                                        :loading="loading"
                                        prepend-icon="mdi-content-save"
                                    >
                                        Update Profile
                                    </v-btn>
                                </form>
                            </v-window-item>

                            <!-- Password Tab -->
                            <v-window-item value="password">
                                <form @submit.prevent="updatePassword">
                                    <v-text-field
                                        v-model="current_password"
                                        label="Current Password"
                                        type="password"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-lock"
                                        :error-messages="currentPasswordError"
                                    />

                                    <v-text-field
                                        v-model="password"
                                        label="New Password"
                                        type="password"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-lock-reset"
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
                                            :model-value="
                                                passwordStrength.strength
                                            "
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
                                        label="Confirm New Password"
                                        type="password"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-lock-check"
                                        :error-messages="
                                            passwordConfirmationError
                                        "
                                    />

                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        size="large"
                                        block
                                        :loading="passwordLoading"
                                        prepend-icon="mdi-lock-reset"
                                    >
                                        Change Password
                                    </v-btn>
                                </form>
                            </v-window-item>
                        </v-window>
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
</style>
