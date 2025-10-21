<script setup>
import { ref, onMounted, computed } from "vue";
import { useField, useForm } from "vee-validate";
import * as yup from "yup";
import api from "@/plugins/axios";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { useToast } from "@/composables/useToast";

const toast = useToast();

const permissions = ref([]);
const loading = ref(false);
const showModal = ref(false);
const editMode = ref(false);
const selectedPermission = ref(null);
const selectedItems = ref([]);

const schema = yup.object({
    name: yup.string().required("Permission name is required"),
});

const { handleSubmit, resetForm } = useForm({
    validationSchema: schema,
    initialValues: {
        name: "",
    },
});

const { value: name, errorMessage: nameError } = useField("name");

const fetchPermissions = async () => {
    loading.value = true;
    try {
        const { data } = await api.get("/permissions");
        permissions.value = data;
    } catch (error) {
        console.error("Error fetching permissions:", error);
    } finally {
        loading.value = false;
    }
};

const openModal = (permission = null) => {
    editMode.value = !!permission;
    selectedPermission.value = permission;

    if (permission) {
        name.value = permission.name;
    } else {
        resetForm();
        name.value = "";
    }

    showModal.value = true;
};

const savePermission = handleSubmit(async (values) => {
    try {
        if (editMode.value && selectedPermission.value) {
            await api.put(
                `/permissions/${selectedPermission.value.id}`,
                values
            );
            toast.success(`Permission "${values.name}" updated successfully!`);
        } else {
            await api.post("/permissions", values);
            toast.success(`Permission "${values.name}" created successfully!`);
        }
        showModal.value = false;
        await fetchPermissions();
    } catch (error) {
        console.error("Error saving permission:", error);
        const errorMessage =
            error.response?.data?.message || "Error saving permission";
        toast.error(errorMessage);
        alert(errorMessage);
    }
});

const deletePermission = async (permission) => {
    if (confirm(`Delete permission "${permission.name}"?`)) {
        try {
            await api.delete(`/permissions/${permission.id}`);
            toast.success(
                `Permission "${permission.name}" deleted successfully!`
            );
            await fetchPermissions();
        } catch (error) {
            console.error("Error deleting permission:", error);
            toast.error("Error deleting permission");
            alert("Error deleting permission");
        }
    }
};

// Get full permission objects from selected IDs
const getSelectedPermissions = () => {
    if (selectedItems.value.length > 0) {
        return permissions.value.filter((perm) =>
            selectedItems.value.includes(perm.id)
        );
    }
    return permissions.value;
};

const exportCSV = () => {
    const itemsToExport = getSelectedPermissions();

    const csv = [
        ["#", "Permission Name"],
        ...itemsToExport.map((p, index) => [index + 1, p.name]),
    ];
    const blob = new Blob([csv.map((r) => r.join(",")).join("\n")], {
        type: "text/csv",
    });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `permissions_${new Date().getTime()}.csv`;
    a.click();
    URL.revokeObjectURL(url);
};

const exportPDF = () => {
    const itemsToExport = getSelectedPermissions();

    const doc = new jsPDF();
    doc.setFontSize(16);
    doc.text("Permissions Management Report", 14, 15);
    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);
    doc.text(`Total Permissions: ${itemsToExport.length}`, 14, 27);

    autoTable(doc, {
        head: [["#", "Permission Name"]],
        body: itemsToExport.map((p, index) => [index + 1, p.name]),
        startY: 32,
        styles: { fontSize: 10 },
        headStyles: { fillColor: [33, 150, 243] },
    });
    doc.save(`permissions_${new Date().getTime()}.pdf`);
};

onMounted(fetchPermissions);
</script>

<template>
    <v-container fluid>
        <v-row class="justify-space-between align-center mb-4">
            <v-col cols="auto">
                <h2 class="text-h5 font-weight-bold">Permissions Management</h2>
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
                    Add New Permission
                </v-btn>
            </v-col>
        </v-row>

        <v-alert
            v-if="selectedItems.length > 0"
            type="info"
            variant="tonal"
            class="mb-4"
        >
            {{ selectedItems.length }} permission(s) selected for export
        </v-alert>

        <v-data-table
            v-model="selectedItems"
            :items="permissions"
            :loading="loading"
            :headers="[
                { title: '#', key: 'index', sortable: false, width: '100px' },
                { title: 'Permission Name', key: 'name' },
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
                    @click="deletePermission(item)"
                />
            </template>
        </v-data-table>

        <!-- Add/Edit Permission Modal -->
        <v-dialog v-model="showModal" max-width="600px" persistent>
            <v-card>
                <v-card-title class="bg-primary text-white">
                    <span class="text-h6">{{
                        editMode ? "Edit Permission" : "Add New Permission"
                    }}</span>
                </v-card-title>

                <v-card-text class="pt-6">
                    <v-text-field
                        v-model="name"
                        label="Permission Name"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="nameError"
                        required
                        placeholder="e.g., view users, edit roles, delete posts"
                    />
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
                        @click="savePermission"
                        prepend-icon="mdi-content-save"
                        size="large"
                    >
                        Save Permission
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
