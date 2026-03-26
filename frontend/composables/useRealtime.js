// Composable per a la gestió del temps real (Socket.io) amb Node.js
import { ref, onMounted, onUnmounted } from 'vue';
import { io } from 'socket.io-client';

export var useRealtime = function () {
    var socket = null;
    var connectat = ref(false);

    onMounted(function () {
        // Connexió al backend de Node.js (temps real)
        socket = io('http://localhost:3000');

        socket.on('connect', function () {
            console.log('Connectat al servidor de temps real');
            connectat.value = true;
        });

        socket.on('disconnect', function () {
            console.log('Desconnectat del servidor de temps real');
            connectat.value = false;
        });

        // Escolta d'actualitzacions de seients
        socket.on('actualitzacio_seient', function (dades) {
            console.log('Actualització de seient rebuda:', dades);
            // Aquí podriem disparar un esdeveniment global o actualitzar un store de Pinia
        });
    });

    onUnmounted(function () {
        if (socket) {
            socket.disconnect();
        }
    });

    var enviarReserva = function (dades) {
        if (socket && connectat.value) {
            socket.emit('reserva_seient', dades);
        }
    };

    return {
        connectat: connectat,
        enviarReserva: enviarReserva
    };
};
