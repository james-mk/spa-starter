import { computed } from 'vue';

export function usePermissions() {
    const user = computed(() => {
        const userData = localStorage.getItem('user');
        return userData ? JSON.parse(userData) : null;
    });

    const userPermissions = computed(() => {
        if (!user.value || !user.value.roles) {
            return [];
        }

        // Get all permissions from all roles
        const permissions = [];
        user.value.roles.forEach(role => {
            if (role.permissions) {
                role.permissions.forEach(permission => {
                    if (!permissions.includes(permission.name)) {
                        permissions.push(permission.name);
                    }
                });
            }
        });

        return permissions;
    });

    const hasPermission = (permissionName) => {
        return userPermissions.value.includes(permissionName);
    };

    const hasAnyPermission = (permissionNames) => {
        return permissionNames.some(permission => hasPermission(permission));
    };

    const hasAllPermissions = (permissionNames) => {
        return permissionNames.every(permission => hasPermission(permission));
    };

    const hasRole = (roleName) => {
        if (!user.value || !user.value.roles) {
            return false;
        }
        return user.value.roles.some(role => role.name === roleName);
    };

    return {
        user,
        userPermissions,
        hasPermission,
        hasAnyPermission,
        hasAllPermissions,
        hasRole,
    };
}
