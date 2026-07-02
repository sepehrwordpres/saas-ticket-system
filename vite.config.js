import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // 💡 این بخش زیر را دقیقاً اضافه کن تا ویت به Herd کاری نداشته باشد:
    server: {
        host: '127.0.0.1',
        port: 5173,
        
    }
});