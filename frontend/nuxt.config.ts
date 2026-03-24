// Fitxer de configuració de Nuxt 3.
// Configura els mòduls com TailwindCSS i Pinia per al frontend del sistema d'entrades.
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    devtools: { enabled: true },
    modules: [
        '@nuxtjs/tailwindcss',
        '@pinia/nuxt'
    ],
    // Configuració de Tailwind si fos necessari
    tailwindcss: {
        // configPath: 'tailwind.config',
        // exposeConfig: false,
        // config: {},
        // viewer: true,
    }
})
