// Middleware per protegir les rutes.
export default defineNuxtRouteMiddleware((to, from) => {
    const token = process.client ? sessionStorage.getItem('token') : null;
    const rutesPubliques = ['/login', '/register'];
    
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
});