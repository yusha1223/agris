import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';
import AOS from 'aos';
import 'aos/dist/aos.css';
import './wilayah';
import './upload-handler';
import './eye';
import './form-refresh';
window.AOS = AOS;

window.Pusher = Pusher;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
}

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        disable: typeof window !== 'undefined' && typeof window.isAdmin !== 'undefined',
        duration: 800,
        easing: 'ease-out-cubic',
        once: false,
        mirror: false,
        offset: 50,
    });

    if (window.Echo) {
        window.Echo.channel('chat.global')
            .listen('.MessageSent', (e) => {
                const chatDot = document.getElementById('chat-notification-dot');

                const isNotOnChatPage = !window.location.href.includes('chat');

                if (chatDot && isNotOnChatPage) {
                    chatDot.classList.remove('hidden');

                    const dots = chatDot.querySelectorAll('span');

                    dots.forEach(dot => {
                        dot.classList.remove('bg-red-400', 'bg-red-500', 'bg-red-600');

                        if (dot.classList.contains('animate-ping')) {
                            dot.classList.add('bg-amber-400');
                        } else {
                            dot.classList.add('bg-amber-500');
                        }
                    });
                }
            });
    }
});
