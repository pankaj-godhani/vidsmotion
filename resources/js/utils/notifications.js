/**
 * Notification utility functions
 * Provides easy access to the global notification system
 */

/**
 * Show a success notification
 * @param {string} message - The notification message
 * @param {string} title - The notification title (optional)
 * @param {number} duration - Duration in milliseconds (optional, default: 5000)
 */
export const showSuccess = (message, title = 'Success', duration = 5000) => {
    if (window.$notify) {
        window.$notify.success(message, title, duration);
    } else {
        console.warn('Notification system not available');
    }
};

/**
 * Show an error notification
 * @param {string} message - The notification message
 * @param {string} title - The notification title (optional)
 * @param {number} duration - Duration in milliseconds (optional, default: 7000)
 */
export const showError = (message, title = 'Error', duration = 7000) => {
    if (window.$notify) {
        window.$notify.error(message, title, duration);
    } else {
        console.warn('Notification system not available');
    }
};

/**
 * Show a warning notification
 * @param {string} message - The notification message
 * @param {string} title - The notification title (optional)
 * @param {number} duration - Duration in milliseconds (optional, default: 6000)
 */
export const showWarning = (message, title = 'Warning', duration = 6000) => {
    if (window.$notify) {
        window.$notify.warning(message, title, duration);
    } else {
        console.warn('Notification system not available');
    }
};

/**
 * Show an info notification
 * @param {string} message - The notification message
 * @param {string} title - The notification title (optional)
 * @param {number} duration - Duration in milliseconds (optional, default: 5000)
 */
export const showInfo = (message, title = 'Information', duration = 5000) => {
    if (window.$notify) {
        window.$notify.info(message, title, duration);
    } else {
        console.warn('Notification system not available');
    }
};

/**
 * Show a payment success notification
 * @param {string} planName - The plan name
 * @param {number} amount - The payment amount
 */
export const showPaymentSuccess = (planName, amount) => {
    showSuccess(
        `Payment successful! You have subscribed to ${planName} for ₹${amount}.`,
        'Payment Completed',
        6000
    );
};

/**
 * Show a payment error notification
 * @param {string} error - The error message
 */
export const showPaymentError = (error = 'Payment failed. Please try again.') => {
    showError(error, 'Payment Failed', 8000);
};

/**
 * Show a subscription deactivated notification
 */
export const showSubscriptionDeactivated = () => {
    showSuccess(
        'Your subscription has been deactivated successfully.',
        'Subscription Deactivated',
        6000
    );
};

/**
 * Show a login success notification
 * @param {string} userName - The user's name
 */
export const showLoginSuccess = (userName) => {
    showSuccess(
        `Welcome back, ${userName}!`,
        'Login Successful',
        4000
    );
};

/**
 * Show a logout notification
 */
export const showLogoutSuccess = () => {
    showInfo('You have been logged out successfully.', 'Logged Out', 3000);
};

/**
 * Show a form validation error notification
 * @param {string} message - The validation error message
 */
export const showValidationError = (message) => {
    showError(message, 'Validation Error', 6000);
};

/**
 * Show a network error notification
 */
export const showNetworkError = () => {
    showError(
        'Network error. Please check your connection and try again.',
        'Connection Error',
        8000
    );
};

/**
 * Show a loading notification (info type)
 * @param {string} message - The loading message
 */
export const showLoading = (message = 'Loading...') => {
    showInfo(message, 'Please Wait', 0); // 0 duration means it won't auto-hide
};

/**
 * Show a file upload success notification
 * @param {string} fileName - The uploaded file name
 */
export const showUploadSuccess = (fileName) => {
    showSuccess(
        `File "${fileName}" uploaded successfully.`,
        'Upload Complete',
        5000
    );
};

/**
 * Show a file upload error notification
 * @param {string} fileName - The file name that failed to upload
 */
export const showUploadError = (fileName) => {
    showError(
        `Failed to upload "${fileName}". Please try again.`,
        'Upload Failed',
        6000
    );
};

// Export all functions as default object
export default {
    showSuccess,
    showError,
    showWarning,
    showInfo,
    showPaymentSuccess,
    showPaymentError,
    showSubscriptionDeactivated,
    showLoginSuccess,
    showLogoutSuccess,
    showValidationError,
    showNetworkError,
    showLoading,
    showUploadSuccess,
    showUploadError
};
