<template>
    <div
        aria-live="assertive"
        class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-50"
    >
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <TransitionGroup
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
                move-class="transition-transform duration-300 ease-in-out"
            >
                <NotificationToast
                    v-for="notification in notifications"
                    :key="notification.id"
                    :type="notification.type"
                    :title="notification.title"
                    :message="notification.message"
                    :duration="notification.duration"
                    :show="true"
                    @close="removeNotification(notification.id)"
                />
            </TransitionGroup>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import NotificationToast from './NotificationToast.vue';

const notifications = ref([]);
let notificationId = 0;

// Listen for flash messages from Inertia
const checkFlashMessages = () => {
    const flash = window.$page?.props?.flash;
    if (flash) {
        if (flash.success) {
            addNotification({
                type: 'success',
                title: 'Success',
                message: flash.success,
                duration: 5000
            });
        }
        if (flash.error) {
            addNotification({
                type: 'error',
                title: 'Error',
                message: flash.error,
                duration: 7000
            });
        }
        if (flash.warning) {
            addNotification({
                type: 'warning',
                title: 'Warning',
                message: flash.warning,
                duration: 6000
            });
        }
        if (flash.info) {
            addNotification({
                type: 'info',
                title: 'Information',
                message: flash.info,
                duration: 5000
            });
        }
    }
};

const addNotification = (notification) => {
    const id = ++notificationId;
    notifications.value.push({
        id,
        ...notification
    });

    // Auto-remove after duration + buffer
    setTimeout(() => {
        removeNotification(id);
    }, (notification.duration || 5000) + 1000);
};

const removeNotification = (id) => {
    const index = notifications.value.findIndex(n => n.id === id);
    if (index > -1) {
        notifications.value.splice(index, 1);
    }
};

// Global notification methods
const showSuccess = (message, title = 'Success', duration = 5000) => {
    addNotification({ type: 'success', title, message, duration });
};

const showError = (message, title = 'Error', duration = 7000) => {
    addNotification({ type: 'error', title, message, duration });
};

const showWarning = (message, title = 'Warning', duration = 6000) => {
    addNotification({ type: 'warning', title, message, duration });
};

const showInfo = (message, title = 'Information', duration = 5000) => {
    addNotification({ type: 'info', title, message, duration });
};

// Make methods globally available
onMounted(() => {
    // Check for flash messages on mount
    checkFlashMessages();

    // Make notification methods globally available
    window.$notify = {
        success: showSuccess,
        error: showError,
        warning: showWarning,
        info: showInfo
    };

    // Listen for Inertia page updates
    if (window.$inertia) {
        window.$inertia.on('navigate', () => {
            setTimeout(checkFlashMessages, 100);
        });
    }
});

onUnmounted(() => {
    // Clean up global methods
    if (window.$notify) {
        delete window.$notify;
    }
});

// Expose methods for parent components
defineExpose({
    showSuccess,
    showError,
    showWarning,
    showInfo,
    addNotification,
    removeNotification
});
</script>
