// Pinia store per a la gestió de l'estat d'autenticació de l'usuari.
// Gestiona l'inici de sessió, el tancament de sessió i la persistència.
import { defineStore } from 'pinia'

export var useAuthStore = defineStore('auth', {
    state: function () {
        return {
            usuari: null,
            estaLoguejat: false
        };
    },
    actions: {
        // Funció per iniciar sessió.
        iniciarSessio: function (dadesUsuari) {
            this.usuari = dadesUsuari;
            this.estaLoguejat = true;
        },
        // Funció per tancar sessió.
        tancarSessio: function () {
            this.usuari = null;
            this.estaLoguejat = false;
        }
    }
});
