//==============================================================================
//================================ IMPORTS =====================================
//==============================================================================
var mysql = require('mysql2/promise');
require('dotenv').config();

//==============================================================================
//================================ VARIABLES ===================================
//==============================================================================
// Pas A: Configuració del Pool de connexions per a MySQL.
// Pas B: Ús de variables d'entorn per a la seguretat.
var pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'user',
  password: process.env.DB_PASSWORD || 'pass',
  database: process.env.DB_NAME || 'ticketing_db',
  waitForConnections: true,
  connectionLimit: 10, // Límit de connexions segons la càrrega esperada.
  queueLimit: 0
});

//==============================================================================
//================================ EXPORTS =====================================
//==============================================================================
module.exports = pool;
