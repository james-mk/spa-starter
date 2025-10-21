import { usePermissions } from '@/composables/usePermissions';

export function checkPermission(to, from, next) {
    const { hasPermission } = usePermissions();

    // Define route permissions
    const routePermissions = {
        '/users': 'view users',
        '/roles': 'view roles',
        '/permissions': 'view permissions',
    };

    const requiredPermission = routePermissions[to.path];

    // If route requires permission and user doesn't have it
    if (requiredPermission && !hasPermission(requiredPermission)) {
        // Redirect to dashboard or show unauthorized
        next('/');
        return;
    }

    next();
}
