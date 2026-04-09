// Pinia store per a la gestió de l'estat d'autenticació de l'usuari.
// Gestiona l'inici de sessió, el tancament de sessió i la persistència.
import { defineStore } from 'pinia'

export var useAuthStore = defineStore('auth', {
    state: function () {
        return {
            usuari: null,
            estaLoguejat: false,
            token: null
        };
    },
    actions: {
        // Funció per iniciar sessió.
        iniciarSessio: function (dadesUsuari) {
            this.usuari = dadesUsuari.user;
            this.token = dadesUsuari.token;
            this.estaLoguejat = true;
            if (process.client) {
                sessionStorage.setItem('token', this.token);
            }
        },
        // Funció per tancar sessió.
        tancarSessio: function () {
            this.usuari = null;
            this.token = null;
            this.estaLoguejat = false;
            if (process.client) {
                sessionStorage.removeItem('token');
                sessionStorage.removeItem('user');
            }
        },
        // Carregar token des de localStorage
        carregarToken: async function () {
            if (process.client) {
                const token = sessionStorage.getItem('token');
                if (token) {
                    this.token = token;
                    this.estaLoguejat = true;
                    try {
                        const response = await fetch('/api/auth/me', {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.usuari = data.user;
                        }
                    } catch (e) {
                        console.error('Error carregant usuari:', e);
                    }
                }
            }
        }
    }
});
