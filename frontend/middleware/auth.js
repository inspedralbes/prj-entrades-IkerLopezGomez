// Middleware per protegir les rutes.
export default defineNuxtRouteMiddleware((to, from) => {
    if (process.client) {
        const token = sessionStorage.getItem('token');
        const rutesPubliques = ['/login', '/register', '/public-catalog', '/admin'];
        
        if (to.path === '/') {
            if (!token) {
                return navigateTo('/login');
            }
            return;
        }
        
        if (!token && !rutesPubliques.includes(to.path)) {
            return navigateTo('/login');
        }
        
        if (token && (to.path === '/login' || to.path === '/register')) {
            return navigateTo('/');
        }
    }
});