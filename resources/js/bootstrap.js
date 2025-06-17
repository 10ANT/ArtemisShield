import 'bootstrap';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// --- Axios Setup ---
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// --- Laravel Echo Configuration for PUSHER ---
window.Pusher = Pusher;

if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth', // This remains the same
        auth: {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        },
    });
    console.log("SUCCESS: Laravel Echo initialized for Pusher.");
} else {
    console.error("CONFIGURATION ERROR: Pusher VITE keys are missing from your .env file.");
}