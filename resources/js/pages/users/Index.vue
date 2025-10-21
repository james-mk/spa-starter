<script setup>
import { ref, onMounted, computed } from "vue";
import { useField, useForm } from "vee-validate";
import * as yup from "yup";
import api from "@/plugins/axios";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { useToast } from "@/composables/useToast";

const toast = useToast();

const users = ref([]);
const roles = ref([]);
const loading = ref(false);
const showModal = ref(false);
const editMode = ref(false);
const selectedUser = ref(null);
const selectedItems = ref([]);
const imagePreview = ref(null);
const imageFile = ref(null);
const saveError = ref("");
const saveLoading = ref(false);

const schema = yup.object({
    first_name: yup.string().required("First name is required"),
    last_name: yup.string().required("Last name is required"),
    email: yup.string().email("Invalid email").required("Email is required"),
    user_type: yup.string(),
    password: yup.string().when("$editMode", {
        is: false,
        then: (schema) =>
            schema
                .required("Password is required")
                .min(8, "Password must be at least 8 characters"),
        otherwise: (schema) =>
            schema
                .transform((value) => (value === "" ? undefined : value))
                .nullable()
                .notRequired()
                .test(
                    "min-length",
                    "Password must be at least 8 characters",
                    (value) => !value || value.length >= 8
                ),
    }),
    password_confirmation: yup.string().when("password", {
        is: (val) => val && val.length > 0,
        then: (schema) =>
            schema
                .required("Password confirmation is required")
                .oneOf([yup.ref("password")], "Passwords must match"),
        otherwise: (schema) => schema.notRequired(),
    }),
    roles: yup
        .array()
        .min(1, "At least one role must be selected")
        .required("At least one role must be selected"),
});

const { handleSubmit, resetForm, errors, setFieldError } = useForm({
    validationSchema: schema,
    initialValues: {
        first_name: "",
        last_name: "",
        email: "",
        user_type: "",
        password: "",
        password_confirmation: "",
        roles: [],
    },
    context: computed(() => ({ editMode: editMode.value })),
});

const { value: first_name, errorMessage: firstNameError } =
    useField("first_name");
const { value: last_name, errorMessage: lastNameError } = useField("last_name");
const { value: email, errorMessage: emailError } = useField("email");
const { value: user_type } = useField("user_type");
const { value: password, errorMessage: passwordError } = useField("password");
const {
    value: password_confirmation,
    errorMessage: passwordConfirmationError,
} = useField("password_confirmation");
const { value: rolesSelected, errorMessage: rolesError } = useField("roles");

// Password strength calculator
const passwordStrength = computed(() => {
    if (!password.value || editMode.value) return null;

    let strength = 0;
    const pwd = password.value;

    // Length check
    if (pwd.length >= 8) strength += 1;
    if (pwd.length >= 12) strength += 1;

    // Character variety checks
    if (/[a-z]/.test(pwd)) strength += 1;
    if (/[A-Z]/.test(pwd)) strength += 1;
    if (/[0-9]/.test(pwd)) strength += 1;
    if (/[^a-zA-Z0-9]/.test(pwd)) strength += 1;

    return {
        score: strength,
        label: strength <= 2 ? "Weak" : strength <= 4 ? "Medium" : "Strong",
        color: strength <= 2 ? "error" : strength <= 4 ? "warning" : "success",
        percentage: (strength / 6) * 100,
    };
});

const fetchUsers = async () => {
    loading.value = true;
    try {
        const { data } = await api.get("/users");
        users.value = data;
    } catch (error) {
        console.error("Error fetching users:", error);
    } finally {
        loading.value = false;
    }
};

const fetchRoles = async () => {
    try {
        const { data } = await api.get("/roles");
        roles.value = data;
    } catch (error) {
        console.error("Error fetching roles:", error);
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

const openModal = (user = null) => {
    editMode.value = !!user;
    selectedUser.value = user;
    imagePreview.value = null;
    imageFile.value = null;
    saveError.value = "";

    if (user) {
        first_name.value = user.first_name;
        last_name.value = user.last_name;
        email.value = user.email;
        user_type.value = user.user_type || "";
        password.value = "";
        password_confirmation.value = "";
        rolesSelected.value = user.roles.map((r) => r.id);
        imagePreview.value = user.profile_image_url;
    } else {
        resetForm();
    }

    showModal.value = true;
};

const saveUser = handleSubmit(
    async (values) => {
        saveLoading.value = true;
        saveError.value = "";

        try {
            const formData = new FormData();
            formData.append("first_name", values.first_name);
            formData.append("last_name", values.last_name);
            formData.append("email", values.email);
            formData.append("user_type", values.user_type || "");

            if (values.password && values.password.trim() !== "") {
                formData.append("password", values.password);
                formData.append(
                    "password_confirmation",
                    values.password_confirmation
                );
            }

            if (values.roles && values.roles.length > 0) {
                values.roles.forEach((roleId, index) => {
                    formData.append(`roles[${index}]`, roleId);
                });
            }

            if (imageFile.value) {
                formData.append("profile_image", imageFile.value);
            }

            if (editMode.value && selectedUser.value) {
                formData.append("_method", "PUT");
                await api.post(`/users/${selectedUser.value.id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                toast.success(
                    `User "${values.first_name} ${values.last_name}" updated successfully!`
                );
            } else {
                await api.post("/users", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                toast.success(
                    `User "${values.first_name} ${values.last_name}" created successfully! Credentials sent to ${values.email}`
                );
            }

            showModal.value = false;
            await fetchUsers();
        } catch (error) {
            console.error("Error saving user:", error);

            if (error.response?.data?.errors) {
                const backendErrors = error.response.data.errors;
                Object.keys(backendErrors).forEach((field) => {
                    setFieldError(field, backendErrors[field][0]);
                });
            }

            const errorMessage =
                error.response?.data?.message ||
                error.message ||
                "Error saving user";
            saveError.value = errorMessage;
            toast.error(errorMessage);
        } finally {
            saveLoading.value = false;
        }
    },
    (errors) => {
        console.log("Validation errors:", errors);
        saveError.value = "Please fix the validation errors before saving.";
        toast.error("Please fix the validation errors before saving.");
    }
);

const deleteUser = async (user) => {
    if (confirm(`Delete user "${user.full_name}"?`)) {
        try {
            await api.delete(`/users/${user.id}`);
            toast.success(`User "${user.full_name}" deleted successfully!`);
            await fetchUsers();
        } catch (error) {
            console.error("Error deleting user:", error);
            toast.error("Error deleting user");
        }
    }
};

const getSelectedUsers = () => {
    if (selectedItems.value.length > 0) {
        return users.value.filter((user) =>
            selectedItems.value.includes(user.id)
        );
    }
    return users.value;
};

const exportCSV = () => {
    const itemsToExport = getSelectedUsers();

    const csv = [
        ["#", "First Name", "Last Name", "Email", "User Type", "Roles"],
        ...itemsToExport.map((u, index) => [
            index + 1,
            u.first_name,
            u.last_name,
            u.email,
            u.user_type || "N/A",
            u.roles.map((r) => r.name).join("; "),
        ]),
    ];
    const blob = new Blob([csv.map((r) => r.join(",")).join("\n")], {
        type: "text/csv",
    });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `users_${new Date().getTime()}.csv`;
    a.click();
    URL.revokeObjectURL(url);

    toast.success(`Exported ${itemsToExport.length} user(s) to CSV`);
};

const exportPDF = () => {
    const itemsToExport = getSelectedUsers();

    const doc = new jsPDF();
    doc.setFontSize(16);
    doc.text("Users Management Report", 14, 15);
    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);
    doc.text(`Total Users: ${itemsToExport.length}`, 14, 27);

    autoTable(doc, {
        head: [["#", "First Name", "Last Name", "Email", "User Type", "Roles"]],
        body: itemsToExport.map((u, index) => [
            index + 1,
            u.first_name,
            u.last_name,
            u.email,
            u.user_type || "N/A",
            u.roles.map((r) => r.name).join(", "),
        ]),
        startY: 32,
        styles: { fontSize: 9 },
        headStyles: { fillColor: [33, 150, 243] },
    });
    doc.save(`users_${new Date().getTime()}.pdf`);

    toast.success(`Exported ${itemsToExport.length} user(s) to PDF`);
};

onMounted(() => {
    fetchUsers();
    fetchRoles();
});
</script>
<template>
    <!-- Keep the same template as before -->
    <v-container fluid>
        <v-row class="justify-space-between align-center mb-4">
            <v-col cols="auto">
                <h2 class="text-h5 font-weight-bold">Users Management</h2>
            </v-col>
            <v-col cols="auto" class="d-flex gap-2">
                <v-btn
                    color="teal"
                    variant="elevated"
                    @click="exportCSV"
                    prepend-icon="mdi-file-delimited"
                >
                    Export CSV
                </v-btn>
                <v-btn
                    color="teal"
                    variant="elevated"
                    @click="exportPDF"
                    prepend-icon="mdi-file-pdf-box"
                >
                    Export PDF
                </v-btn>
                <v-btn
                    color="primary"
                    variant="elevated"
                    @click="openModal()"
                    prepend-icon="mdi-plus"
                >
                    Add New User
                </v-btn>
            </v-col>
        </v-row>

        <v-alert
            v-if="selectedItems.length > 0"
            type="info"
            variant="tonal"
            class="mb-4"
        >
            {{ selectedItems.length }} user(s) selected for export
        </v-alert>

        <v-data-table
            v-model="selectedItems"
            :items="users"
            :loading="loading"
            :headers="[
                { title: '#', key: 'index', sortable: false, width: '80px' },
                {
                    title: 'Profile',
                    key: 'profile',
                    sortable: false,
                    width: '100px',
                },
                { title: 'Name', key: 'name' },
                { title: 'Email', key: 'email' },
                { title: 'User Type', key: 'user_type' },
                {
                    title: 'Roles',
                    key: 'roles',
                    sortable: false,
                    width: '200px',
                },
                {
                    title: 'Actions',
                    key: 'actions',
                    sortable: false,
                    align: 'center',
                    width: '150px',
                },
            ]"
            item-value="id"
            show-select
            class="elevation-2"
        >
            <template v-slot:[`item.index`]="{ index }">
                {{ index + 1 }}
            </template>

            <template v-slot:[`item.profile`]="{ item }">
                <v-avatar size="40" color="primary">
                    <v-img
                        v-if="item.profile_image_url"
                        :src="item.profile_image_url"
                        cover
                    />
                    <span v-else class="text-white"
                        >{{ item.first_name[0] }}{{ item.last_name[0] }}</span
                    >
                </v-avatar>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div>
                    <div class="font-weight-medium">{{ item.full_name }}</div>
                </div>
            </template>

            <template v-slot:[`item.user_type`]="{ item }">
                <v-chip
                    v-if="item.user_type"
                    size="small"
                    color="secondary"
                    variant="tonal"
                >
                    {{ item.user_type }}
                </v-chip>
                <span v-else class="text-grey">N/A</span>
            </template>

            <template v-slot:[`item.roles`]="{ item }">
                <v-chip-group>
                    <v-chip
                        v-for="r in item.roles"
                        :key="r.id"
                        size="small"
                        color="primary"
                        variant="tonal"
                    >
                        {{ r.name }}
                    </v-chip>
                </v-chip-group>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <v-btn
                    icon="mdi-pencil"
                    size="small"
                    variant="text"
                    color="primary"
                    @click="openModal(item)"
                />
                <v-btn
                    icon="mdi-delete"
                    size="small"
                    variant="text"
                    color="error"
                    @click="deleteUser(item)"
                />
            </template>
        </v-data-table>

        <!-- Add/Edit User Modal -->
        <v-dialog v-model="showModal" max-width="800px" persistent scrollable>
            <v-card>
                <v-card-title class="bg-primary text-white">
                    <span class="text-h6">{{
                        editMode ? "Edit User" : "Add New User"
                    }}</span>
                </v-card-title>

                <v-card-text class="pt-6" style="max-height: 600px">
                    <!-- Error Alert -->
                    <v-alert
                        v-if="saveError"
                        type="error"
                        variant="tonal"
                        class="mb-4"
                        closable
                        @click:close="saveError = ''"
                    >
                        {{ saveError }}
                    </v-alert>

                    <!-- Profile Image Upload -->
                    <div class="text-center mb-6">
                        <v-avatar
                            size="120"
                            color="grey-lighten-2"
                            class="mb-3"
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
                        <div>
                            <v-btn
                                variant="outlined"
                                size="small"
                                prepend-icon="mdi-camera"
                                @click="$refs.fileInput.click()"
                            >
                                Upload Photo
                            </v-btn>
                            <input
                                ref="fileInput"
                                type="file"
                                accept="image/*"
                                style="display: none"
                                @change="handleImageChange"
                            />
                        </div>
                        <p class="text-caption text-grey mt-2">
                            JPG, PNG or GIF (MAX. 2MB)
                        </p>
                    </div>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="first_name"
                                label="First Name *"
                                variant="outlined"
                                density="comfortable"
                                :error-messages="firstNameError"
                            />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="last_name"
                                label="Last Name *"
                                variant="outlined"
                                density="comfortable"
                                :error-messages="lastNameError"
                            />
                        </v-col>
                    </v-row>

                    <v-text-field
                        v-model="email"
                        label="Email *"
                        type="email"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="emailError"
                    />

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="password"
                                :label="
                                    editMode
                                        ? 'Password (leave empty to keep current)'
                                        : 'Password *'
                                "
                                type="password"
                                variant="outlined"
                                density="comfortable"
                                :error-messages="passwordError"
                            />

                            <!-- Password Strength Meter (only show when adding new user) -->
                            <div
                                v-if="!editMode && password && passwordStrength"
                                class="mt-2"
                            >
                                <div
                                    class="d-flex justify-space-between align-center mb-1"
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
                                    :model-value="passwordStrength.percentage"
                                    :color="passwordStrength.color"
                                    height="6"
                                    rounded
                                />
                                <div class="text-caption text-grey mt-2">
                                    <div class="d-flex flex-column gap-1">
                                        <div
                                            :class="
                                                password.length >= 8
                                                    ? 'text-success'
                                                    : ''
                                            "
                                        >
                                            <v-icon
                                                size="x-small"
                                                :color="
                                                    password.length >= 8
                                                        ? 'success'
                                                        : 'grey'
                                                "
                                            >
                                                {{
                                                    password.length >= 8
                                                        ? "mdi-check-circle"
                                                        : "mdi-circle-outline"
                                                }}
                                            </v-icon>
                                            At least 8 characters
                                        </div>
                                        <div
                                            :class="
                                                /[a-z]/.test(password) &&
                                                /[A-Z]/.test(password)
                                                    ? 'text-success'
                                                    : ''
                                            "
                                        >
                                            <v-icon
                                                size="x-small"
                                                :color="
                                                    /[a-z]/.test(password) &&
                                                    /[A-Z]/.test(password)
                                                        ? 'success'
                                                        : 'grey'
                                                "
                                            >
                                                {{
                                                    /[a-z]/.test(password) &&
                                                    /[A-Z]/.test(password)
                                                        ? "mdi-check-circle"
                                                        : "mdi-circle-outline"
                                                }}
                                            </v-icon>
                                            Uppercase & lowercase letters
                                        </div>
                                        <div
                                            :class="
                                                /[0-9]/.test(password)
                                                    ? 'text-success'
                                                    : ''
                                            "
                                        >
                                            <v-icon
                                                size="x-small"
                                                :color="
                                                    /[0-9]/.test(password)
                                                        ? 'success'
                                                        : 'grey'
                                                "
                                            >
                                                {{
                                                    /[0-9]/.test(password)
                                                        ? "mdi-check-circle"
                                                        : "mdi-circle-outline"
                                                }}
                                            </v-icon>
                                            Contains numbers
                                        </div>
                                        <div
                                            :class="
                                                /[^a-zA-Z0-9]/.test(password)
                                                    ? 'text-success'
                                                    : ''
                                            "
                                        >
                                            <v-icon
                                                size="x-small"
                                                :color="
                                                    /[^a-zA-Z0-9]/.test(
                                                        password
                                                    )
                                                        ? 'success'
                                                        : 'grey'
                                                "
                                            >
                                                {{
                                                    /[^a-zA-Z0-9]/.test(
                                                        password
                                                    )
                                                        ? "mdi-check-circle"
                                                        : "mdi-circle-outline"
                                                }}
                                            </v-icon>
                                            Special characters (!@#$%^&*)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="password_confirmation"
                                :label="
                                    password && password.length > 0
                                        ? 'Confirm Password *'
                                        : 'Confirm Password'
                                "
                                type="password"
                                variant="outlined"
                                density="comfortable"
                                :error-messages="passwordConfirmationError"
                            />
                        </v-col>
                    </v-row>

                    <v-divider class="my-4" />

                    <div>
                        <v-label
                            class="text-subtitle-1 font-weight-bold mb-3 d-block"
                        >
                            Assign Roles *
                        </v-label>

                        <!-- Display error message if no role selected -->
                        <v-alert
                            v-if="rolesError"
                            type="error"
                            variant="tonal"
                            density="compact"
                            class="mb-3"
                        >
                            {{ rolesError }}
                        </v-alert>

                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                                v-for="role in roles"
                                :key="role.id"
                            >
                                <v-checkbox
                                    v-model="rolesSelected"
                                    :label="role.name"
                                    :value="role.id"
                                    density="compact"
                                    hide-details
                                    color="primary"
                                />
                            </v-col>
                        </v-row>
                    </div>
                </v-card-text>

                <v-divider />

                <v-card-actions class="pa-4">
                    <v-spacer />
                    <v-btn
                        variant="text"
                        @click="showModal = false"
                        :disabled="saveLoading"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        color="primary"
                        variant="elevated"
                        @click="saveUser"
                        prepend-icon="mdi-content-save"
                        size="large"
                        :loading="saveLoading"
                    >
                        Save User
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<style scoped>
.v-btn {
    text-transform: none;
}

.gap-2 {
    gap: 8px;
}

.gap-1 {
    gap: 4px;
}
</style>
