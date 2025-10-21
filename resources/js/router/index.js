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
import Roles from '../pages/roles/Index.vue'
import Permissions from '../pages/permissions/Index.vue'
import Users from '../pages/users/Index.vue'
import { useLoadingStore } from '../store/loading';
import Profile from '../pages/profile/Index.vue'
import Settings from '../pages/settings/Index.vue';
import UserLogs from '../pages/logs/Index.vue';

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

  //settings
    {
    path: '/settings',
    name: 'settings',
    component: Settings,
    meta: {
      requiresAuth: true,
      permission: 'view settings'
    },
  },

  //logs
   {
    path: '/logs',
    name: 'logs',
    component: UserLogs,
    meta: { 
      requiresAuth: true,
      permission: 'view logs'
    },
  },

  //profile
  {
    path: '/profile',
    name: 'profile',
    component: Profile,
    meta: {
      requiresAuth: true,
    //   permission: 'view profile'
    }
  },

  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
    meta: {
      requiresAuth: true,
      permission: 'view dashboard'
    }
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
  {
    path: '/users',
    name: 'users',
    component: Users,
    meta: {
      requiresAuth: true,
      permission: 'view users'
    },
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
  if (to.meta.guest && auth.isLoggedIn) {
    return next({ name: 'dashboard' });
  }

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
