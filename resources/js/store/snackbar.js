import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useSnackbarStore = defineStore('snackbar', () => {
    const visible = ref(false);
    const message = ref('');
    const color = ref('info');
    const timeout = ref(3000);

    function show(msg, type = 'info', duration = 3000) {
        message.value = msg;
        color.value = type;
        timeout.value = duration;
        visible.value = true;
    }

    function hide() {
        visible.value = false;
    }

    function success(msg, duration = 3000) {
        show(msg, 'success', duration);
    }

    function error(msg, duration = 5000) {
        show(msg, 'error', duration);
    }

    function warning(msg, duration = 4000) {
        show(msg, 'warning', duration);
    }

    function info(msg, duration = 3000) {
        show(msg, 'info', duration);
    }

    return {
        visible,
        message,
        color,
        timeout,
        show,
        hide,
        success,
        error,
        warning,
        info,
    };
});
