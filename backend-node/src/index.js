//==============================================================================
//================================ IMPORTS =====================================
//==============================================================================
var express = require('express');
var http = require('http');
var socketIo = require('socket.io');
var cors = require('cors');

console.log('Iniciant el backend de temps real (Node.js)...');

//==============================================================================
//================================ VARIABLES ===================================
//==============================================================================
var app = express();
var server = http.createServer(app);
var io = socketIo(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});
var port = process.env.PORT || 3000;

//==============================================================================
//================================ MIDDLEWARE ==================================
//==============================================================================
app.use(cors());
app.use(express.json());

//==============================================================================
//================================ RUTES = [SOLS ESTAT] ========================
//==============================================================================
app.get('/api/estat', function (req, res) {
    res.json({ estat: 'en funcionament', servei: 'Temps Real (Socket.io)' });
});

//==============================================================================
//================================ SOCKET.IO ===================================
//==============================================================================
io.on('connection', function (socket) {
    console.log('Nou client connectat: ' + socket.id);

    socket.on('disconnect', function () {
        console.log('Client desconnectat: ' + socket.id);
    });

    // Aquí anirà la lògica de reserva de seients en temps real.
    socket.on('reserva_seient', function (dades) {
        console.log('Reserva de seient rebuda:', dades);
        // Emetem a tots els clients la actualització.
        io.emit('actualitzacio_seient', dades);
    });
});

//==============================================================================
//================================ INICIS ======================================
//==============================================================================
server.listen(port, function () {
    console.log('--------------------------------------------------');
    console.log('SERVIDOR DE TEMPS REAL ACTIU AL PORT ' + port);
    console.log('Projecte: TOTOSALA (Node.js)');
    console.log('--------------------------------------------------');
});

process.on('SIGINT', function () {
    console.log('Tancant el servidor de temps real...');
    process.exit();
});
