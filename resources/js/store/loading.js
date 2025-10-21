import { defineStore } from 'pinia';

export const useLoadingStore = defineStore('loading', {
    state: () => ({
        isLoading: false,
        requestCount: 0,
    }),
    
    actions: {
        show() {
            this.requestCount++;
            this.isLoading = true;
        },
        
        hide() {
            this.requestCount--;
            if (this.requestCount <= 0) {
                this.requestCount = 0;
                this.isLoading = false;
            }
        },
        
        forceHide() {
            this.requestCount = 0;
            this.isLoading = false;
        }
    }
});