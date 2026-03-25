//==============================================================================
//================================ IMPORTS =====================================
//==============================================================================
var express = require('express');
console.log('Iniciant el backend...');
var cors = require('cors');
var pool = require('./config/db');

//==============================================================================
//================================ VARIABLES ===================================
//==============================================================================
var app = express();
var port = process.env.PORT || 3000;

//==============================================================================
//================================ MIDDLEWARE ==================================
//==============================================================================
app.use(cors());
app.use(express.json());

//==============================================================================
//================================ RUTES =======================================
//==============================================================================
// Ruta de prova per verificar que el backend funciona.
app.get('/api/estat', function (req, res) {
    res.json({ estat: 'en funcionament', projecte: 'Totosala' });
});

// Ruta per obtenir totes les pel·lícules
app.get('/api/movies', function (req, res) {
    pool.query('SELECT * FROM movies')
        .then(function (resultat) {
            res.json(resultat[0]);
        })
        .catch(function (error) {
            res.status(500).json({ error: error.message });
        });
});

// Ruta per obtenir tots els concerts
app.get('/api/concerts', function (req, res) {
    pool.query('SELECT * FROM concerts')
        .then(function (resultat) {
            res.json(resultat[0]);
        })
        .catch(function (error) {
            res.status(500).json({ error: error.message });
        });
});

//==============================================================================
//================================ INICIS ======================================
//==============================================================================
// A. Esperar i llançar el servidor.
app.listen(port, function () {
    console.log('--------------------------------------------------');
    console.log('SERVIDOR BACKEND ACTIU AL PORT ' + port);
    console.log('Projecte: TOTOSALA');
    console.log('--------------------------------------------------');
});

// B. Gestió de tancament net.
process.on('SIGINT', function () {
    console.log('Tancant el servidor backend...');
    process.exit();
});

