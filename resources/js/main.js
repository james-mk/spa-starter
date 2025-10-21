import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import vuetify from './plugins/vuetify';
import App from './App.vue';
import './style.css';
import { Toast, options } from './plugins/toastification';

// Use LoadingScreen component globally
import LoadingScreen from './components/LoadingScreen.vue';

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.use(vuetify);
app.use(Toast, options);

app.component('LoadingScreen', LoadingScreen);

app.mount('#app');