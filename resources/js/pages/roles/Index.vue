<script setup>
import { ref, onMounted, computed } from "vue";
import { useField, useForm } from "vee-validate";
import * as yup from "yup";
import api from "@/plugins/axios";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { useToast } from "@/composables/useToast";

const toast = useToast();
const roles = ref([]);
const permissions = ref([]);
const loading = ref(false);
const showModal = ref(false);
const editMode = ref(false);
const selectedRole = ref(null);
const selectedItems = ref([]);
const showPermissionsModal = ref(false);
const viewingRole = ref(null);

const schema = yup.object({
    name: yup.string().required("Role name is required"),
    permissions: yup.array().min(0),
});

const { handleSubmit, resetForm, errors } = useForm({
    validationSchema: schema,
    initialValues: {
        name: "",
        permissions: [],
    },
});

const { value: name, errorMessage: nameError } = useField("name");
const { value: permissionsSelected } = useField("permissions");

const fetchRoles = async () => {
    loading.value = true;
    try {
        const { data } = await api.get("/roles");
        roles.value = data;
    } catch (error) {
        console.error("Error fetching roles:", error);
    } finally {
        loading.value = false;
    }
};

const fetchPermissions = async () => {
    try {
        const { data } = await api.get("/permissions");
        permissions.value = data;
    } catch (error) {
        console.error("Error fetching permissions:", error);
    }
};

const openModal = (role = null) => {
    editMode.value = !!role;
    selectedRole.value = role;

    if (role) {
        name.value = role.name;
        permissionsSelected.value = role.permissions.map((p) => p.id);
    } else {
        resetForm();
        name.value = "";
        permissionsSelected.value = [];
    }

    showModal.value = true;
};

const openPermissionsModal = (role) => {
    viewingRole.value = role;
    showPermissionsModal.value = true;
};

const saveRole = handleSubmit(async (values) => {
    try {
        if (editMode.value && selectedRole.value) {
            await api.put(`/roles/${selectedRole.value.id}`, values);
            toast.success(`Role "${values.name}" updated successfully!`);
        } else {
            await api.post("/roles", values);
            toast.success(`Role "${values.name}" created successfully!`);
        }
        showModal.value = false;
        await fetchRoles();
    } catch (error) {
        console.error("Error saving role:", error);
        const errorMessage =
            error.response?.data?.message || "Error saving role";
        toast.error(errorMessage);
        alert(errorMessage);
    }
});

const deleteRole = async (role) => {
    if (confirm(`Delete role "${role.name}"?`)) {
        try {
            await api.delete(`/roles/${role.id}`);
            toast.success(`Role "${role.name}" deleted successfully!`);
            await fetchRoles();
        } catch (error) {
            console.error("Error deleting role:", error);
            toast.error("Error deleting role");
            alert("Error deleting role");
        }
    }
};
// Get full role objects from selected IDs
const getSelectedRoles = () => {
    if (selectedItems.value.length > 0) {
        return roles.value.filter((role) =>
            selectedItems.value.includes(role.id)
        );
    }
    return roles.value;
};

const exportCSV = () => {
    const itemsToExport = getSelectedRoles();

    const csv = [
        ["#", "Role Name", "Permissions Count", "Permissions"],
        ...itemsToExport.map((r, index) => [
            index + 1,
            r.name,
            r.permissions.length,
            r.permissions.map((p) => p.name).join("; "),
        ]),
    ];
    const blob = new Blob([csv.map((r) => r.join(",")).join("\n")], {
        type: "text/csv",
    });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `roles_${new Date().getTime()}.csv`;
    a.click();
    URL.revokeObjectURL(url);
};

const exportPDF = () => {
    const itemsToExport = getSelectedRoles();

    const doc = new jsPDF();
    doc.setFontSize(16);
    doc.text("Roles Management Report", 14, 15);
    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);
    doc.text(`Total Roles: ${itemsToExport.length}`, 14, 27);

    autoTable(doc, {
        head: [["#", "Role Name", "Permissions Count", "Permissions"]],
        body: itemsToExport.map((r, index) => [
            index + 1,
            r.name,
            r.permissions.length,
            r.permissions.map((p) => p.name).join(", "),
        ]),
        startY: 32,
        styles: { fontSize: 9 },
        headStyles: { fillColor: [33, 150, 243] },
    });
    doc.save(`roles_${new Date().getTime()}.pdf`);
};

// Group permissions into 4 columns
const permissionColumns = computed(() => {
    const columns = [[], [], [], []];
    permissions.value.forEach((perm, index) => {
        columns[index % 4].push(perm);
    });
    return columns;
});

onMounted(() => {
    fetchRoles();
    fetchPermissions();
});
</script>

<template>
    <v-container fluid>
        <v-row class="justify-space-between align-center mb-4">
            <v-col cols="auto">
                <h2 class="text-h5 font-weight-bold">Roles Management</h2>
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
                    Add New Role
                </v-btn>
            </v-col>
        </v-row>

        <v-alert
            v-if="selectedItems.length > 0"
            type="info"
            variant="tonal"
            class="mb-4"
        >
            {{ selectedItems.length }} role(s) selected for export
        </v-alert>

        <v-data-table
            v-model="selectedItems"
            :items="roles"
            :loading="loading"
            :headers="[
                { title: '#', key: 'index', sortable: false, width: '80px' },
                { title: 'Role Name', key: 'name' },
                {
                    title: 'Permissions',
                    key: 'permissions',
                    sortable: false,
                    width: '150px',
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

            <template v-slot:[`item.permissions`]="{ item }">
                <v-btn
                    variant="text"
                    color="primary"
                    size="small"
                    @click="openPermissionsModal(item)"
                >
                    <v-icon start>mdi-eye</v-icon>
                    View ({{ item.permissions.length }})
                </v-btn>
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
                    @click="deleteRole(item)"
                />
            </template>
        </v-data-table>

        <!-- Add/Edit Role Modal -->
        <v-dialog v-model="showModal" max-width="1000px" persistent>
            <v-card>
                <v-card-title class="bg-primary text-white">
                    <span class="text-h6">{{
                        editMode ? "Edit Role" : "Add New Role"
                    }}</span>
                </v-card-title>

                <v-card-text class="pt-6">
                    <v-text-field
                        v-model="name"
                        label="Role Name"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="nameError"
                        required
                    />

                    <v-divider class="my-4" />

                    <v-label
                        class="text-subtitle-1 font-weight-bold mb-3 d-block"
                    >
                        Permissions
                    </v-label>

                    <v-row>
                        <v-col
                            v-for="(column, colIndex) in permissionColumns"
                            :key="colIndex"
                            cols="12"
                            sm="6"
                            md="3"
                        >
                            <v-checkbox
                                v-for="p in column"
                                :key="p.id"
                                :label="p.name"
                                :value="p.id"
                                v-model="permissionsSelected"
                                density="compact"
                                hide-details
                                color="primary"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-divider />

                <v-card-actions class="pa-4">
                    <v-spacer />
                    <v-btn variant="text" @click="showModal = false">
                        Cancel
                    </v-btn>
                    <v-btn
                        color="primary"
                        variant="elevated"
                        @click="saveRole"
                        prepend-icon="mdi-content-save"
                        size="large"
                    >
                        Save Role
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- View Permissions Modal -->
        <v-dialog v-model="showPermissionsModal" max-width="600px">
            <v-card>
                <v-card-title class="bg-primary text-white">
                    <span class="text-h6"
                        >Permissions for "{{ viewingRole?.name }}"</span
                    >
                </v-card-title>

                <v-card-text class="pt-4">
                    <v-alert
                        v-if="viewingRole?.permissions.length === 0"
                        type="info"
                        variant="tonal"
                    >
                        No permissions assigned to this role.
                    </v-alert>

                    <v-list v-else density="compact">
                        <v-list-item
                            v-for="(perm, index) in viewingRole?.permissions"
                            :key="perm.id"
                            :title="`${index + 1}. ${perm.name}`"
                            prepend-icon="mdi-shield-check"
                        >
                        </v-list-item>
                    </v-list>
                </v-card-text>

                <v-divider />

                <v-card-actions class="pa-4">
                    <v-spacer />
                    <v-btn
                        color="primary"
                        variant="elevated"
                        @click="showPermissionsModal = false"
                    >
                        Close
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
</style>
