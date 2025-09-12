<template>
    <div>
        <slot />
        <NotificationManager ref="notificationManager" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import NotificationManager from '@/Components/NotificationManager.vue';

const notificationManager = ref(null);

// Expose notification methods globally
onMounted(() => {
    // Wait for notification manager to be ready
    setTimeout(() => {
        if (notificationManager.value) {
            // Make notification methods available globally
            window.$notify = {
                success: (message, title, duration) => notificationManager.value.showSuccess(message, title, duration),
                error: (message, title, duration) => notificationManager.value.showError(message, title, duration),
                warning: (message, title, duration) => notificationManager.value.showWarning(message, title, duration),
                info: (message, title, duration) => notificationManager.value.showInfo(message, title, duration)
            };
        }
    }, 100);
});
</script>
