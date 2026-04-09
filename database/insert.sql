-- Script d'inserts per al sistema de venda d'entrades.
USE ticketing_db;

-- Inserts per a usuaris
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@totosala.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Inserts per a pel·lícules
INSERT INTO movies (titol, descripcio, data, hora, preu, imatge_url) VALUES
('Dune: Part Two', 'L''epopeia de Paul Atreides continua.', '2026-04-10', '18:30:00', 8.00, 'dune.jpeg'),
('Interstellar', 'Viatge a través del temps i l''espai.', '2026-04-12', '20:00:00', 8.00, 'interstellar.jpg'),
('Oppenheimer', 'La història de la bomba atòmica.', '2026-04-15', '21:00:00', 8.00, 'oppenheimer.png'),
('The Dark Knight', 'Batman s''enfronta al Joker.', '2026-05-01', '19:00:00', 8.00, 'the dark knight.jpeg');

-- Inserts per a concerts
INSERT INTO concerts (titol, artista, descripcio, data, hora, preu, imatge_url) VALUES
('Estopa - Gira 25 anys', 'Estopa', 'Concert al Palau Sant Jordi.', '2026-06-15', '21:00:00', 45.00, 'Estopa.jpeg'),
('Coldplay - Music of the Spheres', 'Coldplay', 'Espectacle visual i sonor únic.', '2026-05-20', '20:30:00', 85.00, 'Coldplay.jpeg'),
('The Tyets - Èpic Solete', 'The Tyets', 'Música festiva en català.', '2026-04-25', '22:00:00', 25.00, 'thetyets.jpeg'),
('Bad Gyal - La Joia Tour', 'Bad Gyal', 'Ritmes de dancehall i reggaeton.', '2026-07-05', '21:30:00', 55.00, 'badgyal.jpeg');
