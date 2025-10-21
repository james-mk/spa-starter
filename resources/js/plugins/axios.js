import axios from 'axios';
import { useAuthStore } from '../store/auth';
import { useLoadingStore } from '../store/loading';
import router from '../router';

const api = axios.create({
  baseURL: '/api/v1',
  headers: { Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
  const auth = useAuthStore();
  const loading = useLoadingStore();
  
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`;
  }
  
  // Show loading spinner
  loading.show();
  
  return config;
}, (error) => {
  const loading = useLoadingStore();
  loading.hide();
  return Promise.reject(error);
});

api.interceptors.response.use(
  (response) => {
    const loading = useLoadingStore();
    loading.hide();
    return response;
  },
  async (error) => {
    const loading = useLoadingStore();
    loading.hide();

    if (error.config?.url?.includes('/logout')) {
      return Promise.reject(error);
    }
    
    const status = error.response?.status;
    const auth = useAuthStore();

    if (status === 401 || status === 419) {
      if (auth.isLoggedIn) {
        // Perform a silent logout to stop the loop
        await auth.logout(true);
        router.push('/login');
      }
    } else if (status === 403) {
      router.push('/403');
    } else if (status === 404) {
      router.push('/404');
    } else if (status >= 500) {
      router.push('/500');
    }

    return Promise.reject(error);
  }
);

export default api;