-- Script d'inicialització de la base de dades per al sistema de venda d'entrades.
-- Optimitzat per a alta concurrència amb índexs i restriccions específiques.

-- Creació de la base de dades si no existeix
CREATE DATABASE IF NOT EXISTS ticketing_db;
USE ticketing_db;

-- Taula d'usuaris
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Taula de pel·lícules
CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titol VARCHAR(255) NOT NULL,
    descripcio TEXT,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    preu DECIMAL(10, 2) NOT NULL,
    imatge_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Taula de concerts
CREATE TABLE IF NOT EXISTS concerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titol VARCHAR(255) NOT NULL,
    descripcio TEXT,
    artista VARCHAR(255) NOT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    preu DECIMAL(10, 2) NOT NULL,
    imatge_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Taula de seients per a pel·lícules
CREATE TABLE IF NOT EXISTS movie_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    status ENUM('available', 'reserved', 'sold') DEFAULT 'available',
    `row` VARCHAR(10) NOT NULL,
    `number` INT NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

-- Taula de seients per a concerts
CREATE TABLE IF NOT EXISTS concert_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    concert_id INT NOT NULL,
    status ENUM('available', 'reserved', 'sold') DEFAULT 'available',
    `row` VARCHAR(10) NOT NULL,
    `number` INT NOT NULL,
    FOREIGN KEY (concert_id) REFERENCES concerts(id) ON DELETE CASCADE
);

-- Taula de comandes (generic)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
