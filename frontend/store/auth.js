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
                localStorage.setItem('token', this.token);
            }
        },
        // Funció per tancar sessió.
        tancarSessio: function () {
            this.usuari = null;
            this.token = null;
            this.estaLoguejat = false;
            if (process.client) {
                localStorage.removeItem('token');
            }
        },
        // Carregar token des de localStorage
        carregarToken: function () {
            if (process.client) {
                const token = localStorage.getItem('token');
                if (token) {
                    this.token = token;
                    this.estaLoguejat = true;
                }
            }
        }
    }
});
