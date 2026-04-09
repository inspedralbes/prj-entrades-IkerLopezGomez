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
            hmr: {
                protocol: 'ws',
                host: 'localhost',
                port: 3001
            },
            watch: {
                usePolling: true,
                interval: 1000
            },
            proxy: {
                '/api': {
                    target: 'http://laravel-web',
                    changeOrigin: true
                }
            }
        }
    }
})