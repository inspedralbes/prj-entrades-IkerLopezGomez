// Fitxer de configuració de Nuxt 3.
export default defineNuxtConfig({
    devtools: { enabled: true },
    modules: [
        '@nuxtjs/tailwindcss',
        '@pinia/nuxt'
    ],
    router: {
        middleware: ['auth']
    },
    vite: {
        server: {
            host: '0.0.0.0',
            port: 3001,
            hmr: {
                protocol: 'ws',
                host: '0.0.0.0',
                port: 3001
            },
            watch: {
                usePolling: true,
                interval: 1000
            },
            proxy: {
                '/api': {
                    target: 'http://laravel:8000',
                    changeOrigin: true
                }
            }
        }
    }
})