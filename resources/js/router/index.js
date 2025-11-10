import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/auth';

// Pages
import Dashboard from '../pages/Dashboard.vue';
import Login from '../pages/auth/Login.vue';
import ForgotPassword from '../pages/auth/ForgotPassword.vue';
import ResetPassword from '../pages/auth/ResetPassword.vue';
import NotFound from '../pages/errors/NotFound.vue';
import Forbidden from '../pages/errors/Forbidden.vue';
import ServerError from '../pages/errors/ServerError.vue';
import Roles from '../pages/roles/Index.vue';
import Permissions from '../pages/permissions/Index.vue';
import Users from '../pages/users/Index.vue';
import Profile from '../pages/profile/Index.vue';
import Settings from '../pages/settings/Index.vue';
import UserLogs from '../pages/logs/Index.vue';

// Configuration/Taxonomy Pages
import IncidentCategories from '../pages/configurations/IncidentCategories.vue';
import IncidentSubCategories from '../pages/configurations/IncidentSubCategories.vue';
import IncidentTypes from '../pages/configurations/IncidentTypes.vue';
import IncidentSubTypes from '../pages/configurations/IncidentSubTypes.vue';
import DeductionCategories from '../pages/configurations/DeductionCategories.vue';
import DeductionSubCategories from '../pages/configurations/DeductionSubCategories.vue';
import DeductionSpecificIssues from '../pages/configurations/DeductionSpecificIssues.vue';
import DeductionLocations from '../pages/configurations/DeductionLocations.vue';
import PaymentCategories from '../pages/configurations/PaymentCategories.vue';
import PaymentTypes from '../pages/configurations/PaymentTypes.vue';
import VehicleTypes from '../pages/configurations/VehicleTypes.vue';
import DocumentTypes from '../pages/configurations/DocumentTypes.vue';
import DriverStatuses from '../pages/configurations/DriverStatuses.vue';
import TaxonomyImport from '../pages/configurations/TaxonomyImport.vue';
import VehicleMakes from '../pages/configurations/VehicleMakes.vue';
import VehicleModels from '../pages/configurations/VehicleModels.vue';

import { useLoadingStore } from '../store/loading';
import Drivers from '../pages/drivers/Drivers.vue';
import Notifications from '../pages/notifications/Notifications.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true, layout: 'auth' }
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: ForgotPassword,
    meta: { guest: true, layout: 'auth' }
  },
  {
    path: '/reset-password/:token',
    name: 'reset-password',
    component: ResetPassword,
    meta: { guest: true, layout: 'auth' }
  },

  // Dashboard
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
    meta: {
      requiresAuth: true,
      permission: 'view dashboard'
    }
  },

  // User Access Control
  {
    path: '/users',
    name: 'users',
    component: Users,
    meta: {
      requiresAuth: true,
      permission: 'view users'
    },
  },
  {
    path: '/roles',
    name: 'roles',
    component: Roles,
    meta: {
      requiresAuth: true,
      permission: 'view roles'
    },
  },
  {
    path: '/permissions',
    name: 'permissions',
    component: Permissions,
    meta: {
      requiresAuth: true,
      permission: 'view permissions'
    },
  },

  // Profile
  {
    path: '/profile',
    name: 'profile',
    component: Profile,
    meta: {
      requiresAuth: true,
    }
  },

  // Settings
  {
    path: '/settings',
    name: 'settings',
    component: Settings,
    meta: {
      requiresAuth: true,
      permission: 'view settings'
    },
  },

  // Logs
  {
    path: '/logs',
    name: 'logs',
    component: UserLogs,
    meta: {
      requiresAuth: true,
      permission: 'view logs'
    },
  },

  // ============================================
  // CONFIGURATION ROUTES (TAXONOMIES)
  // ============================================

  // Incident Configurations
  {
    path: '/configurations/incident-categories',
    name: 'incident-categories',
    component: IncidentCategories,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/incident-sub-categories',
    name: 'incident-sub-categories',
    component: IncidentSubCategories,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/incident-types',
    name: 'incident-types',
    component: IncidentTypes,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/incident-sub-types',
    name: 'incident-sub-types',
    component: IncidentSubTypes,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },

  // Deduction Configurations
  {
    path: '/configurations/deduction-categories',
    name: 'deduction-categories',
    component: DeductionCategories,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/deduction-sub-categories',
    name: 'deduction-sub-categories',
    component: DeductionSubCategories,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/deduction-specific-issues',
    name: 'deduction-specific-issues',
    component: DeductionSpecificIssues,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/deduction-locations',
    name: 'deduction-locations',
    component: DeductionLocations,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },

  // Payment Configurations
  {
    path: '/configurations/payment-categories',
    name: 'payment-categories',
    component: PaymentCategories,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/payment-types',
    name: 'payment-types',
    component: PaymentTypes,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },

  // Other Configurations
  {
    path: '/configurations/vehicle-types',
    name: 'vehicle-types',
    component: VehicleTypes,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/document-types',
    name: 'document-types',
    component: DocumentTypes,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/driver-statuses',
    name: 'driver-statuses',
    component: DriverStatuses,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },

  // Vehicle Makes & Models

  {
    path: '/configurations/vehicle-makes',
    name: 'vehicle-makes',
    component: VehicleMakes,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },
  {
    path: '/configurations/vehicle-models',
    name: 'vehicle-models',
    component: VehicleModels,
    meta: {
      requiresAuth: true,
      permission: 'view configurations'
    }
  },

  // Taxonomy Import
  {
    path: '/configurations/import',
    name: 'taxonomy-import',
    component: TaxonomyImport,
    meta: {
      requiresAuth: true,
      permission: 'edit configurations'
    }
  },

  // Notifications
  {
    path: '/notifications',
    name: 'Notifications',
    component: Notifications,
    meta: {
      requiresAuth: true,
      permission: 'view notifications'
    }
  },

  // Drivers
{
    path: '/drivers',
    name: 'Drivers',
    component: Drivers,
    meta: {
      requiresAuth: true,
      permission: 'view drivers'
    }
},

  // Error pages
  {
    path: '/403',
    name: 'forbidden',
    component: Forbidden,
    meta: { layout: 'auth' }
  },
  {
    path: '/500',
    name: 'server-error',
    component: ServerError,
    meta: { guest: true, layout: 'auth' }
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFound,
    meta: { guest: true, layout: 'auth' }
  },


];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Helper function to check permissions
function hasPermission(permissionName) {
  const userData = localStorage.getItem('user');
  if (!userData) return false;

  try {
    const user = JSON.parse(userData);
    if (!user || !user.roles) return false;

    // Get all permissions from all roles
    const permissions = [];
    user.roles.forEach(role => {
      if (role.permissions) {
        role.permissions.forEach(permission => {
          if (!permissions.includes(permission.name)) {
            permissions.push(permission.name);
          }
        });
      }
    });

    return permissions.includes(permissionName);
  } catch (error) {
    console.error('Error parsing user data:', error);
    return false;
  }
}

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();

  // Check authentication first
  if (to.meta.requiresAuth && !auth.isLoggedIn) {
    return next({ name: 'login' });
  }

  // Redirect logged-in users away from guest pages
//   if (to.meta.guest && auth.isLoggedIn) {
//     return next({ name: 'dashboard' });
//   }

  // Check permissions if route requires it
  if (to.meta.permission && auth.isLoggedIn) {
    if (!hasPermission(to.meta.permission)) {
      // User doesn't have required permission, redirect to forbidden
      return next({ name: 'forbidden' });
    }
  }

  next();
});

// Hide loading after route change
router.afterEach(() => {
  const loading = useLoadingStore();

  // Small delay to ensure smooth transition
  setTimeout(() => {
    loading.hide();
  }, 300);
});

export default router;
