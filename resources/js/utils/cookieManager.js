// Cookie Management Utility
export class CookieManager {
    constructor() {
        this.preferences = this.getCookiePreferences();
    }

    // Get cookie preferences from localStorage
    getCookiePreferences() {
        const stored = localStorage.getItem('cookiePreferences');
        if (stored) {
            return JSON.parse(stored);
        }
        return {
            necessary: true,
            analytics: false,
            marketing: false,
            preferences: false
        };
    }

    // Check if user has given consent
    hasConsent() {
        return localStorage.getItem('cookieConsent') === 'accepted';
    }

    // Check if specific cookie type is allowed
    isAllowed(type) {
        if (!this.hasConsent()) {
            return type === 'necessary';
        }
        return this.preferences[type] || false;
    }

    // Set a cookie with proper consent checking
    setCookie(name, value, options = {}) {
        const type = options.type || 'necessary';

        if (!this.isAllowed(type)) {
            console.warn(`Cookie "${name}" blocked - ${type} cookies not allowed`);
            return false;
        }

        let cookieString = `${name}=${value}`;

        if (options.expires) {
            const date = new Date();
            date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            cookieString += `; expires=${date.toUTCString()}`;
        }

        if (options.path) {
            cookieString += `; path=${options.path}`;
        }

        if (options.domain) {
            cookieString += `; domain=${options.domain}`;
        }

        if (options.secure) {
            cookieString += `; secure`;
        }

        if (options.sameSite) {
            cookieString += `; samesite=${options.sameSite}`;
        }

        document.cookie = cookieString;
        return true;
    }

    // Get a cookie value
    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Delete a cookie
    deleteCookie(name, path = '/') {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=${path};`;
    }

    // Clear all cookies (except necessary ones)
    clearNonEssentialCookies() {
        const cookies = document.cookie.split(';');
        cookies.forEach(cookie => {
            const eqPos = cookie.indexOf('=');
            const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();

            // Don't delete necessary cookies
            if (!this.isNecessaryCookie(name)) {
                this.deleteCookie(name);
            }
        });
    }

    // Check if a cookie is necessary
    isNecessaryCookie(name) {
        const necessaryCookies = [
            'cookieConsent',
            'cookiePreferences',
            'XSRF-TOKEN',
            'laravel_session',
            'remember_token'
        ];
        return necessaryCookies.includes(name);
    }

    // Initialize analytics if allowed
    initializeAnalytics() {
        if (this.isAllowed('analytics')) {
            // Google Analytics initialization
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'granted'
                });
            }

            // Other analytics services can be initialized here
            console.log('Analytics initialized');
        }
    }

    // Initialize marketing if allowed
    initializeMarketing() {
        if (this.isAllowed('marketing')) {
            // Facebook Pixel, Google Ads, etc.
            console.log('Marketing tracking initialized');
        }
    }

    // Update preferences and reinitialize services
    updatePreferences(newPreferences) {
        this.preferences = { ...this.preferences, ...newPreferences };
        localStorage.setItem('cookiePreferences', JSON.stringify(this.preferences));

        // Reinitialize services based on new preferences
        this.initializeAnalytics();
        this.initializeMarketing();
    }

    // Reset all preferences
    resetPreferences() {
        localStorage.removeItem('cookieConsent');
        localStorage.removeItem('cookiePreferences');
        this.clearNonEssentialCookies();
        this.preferences = {
            necessary: true,
            analytics: false,
            marketing: false,
            preferences: false
        };
    }
}

// Create a global instance
export const cookieManager = new CookieManager();

// Export for use in components
export default cookieManager;
