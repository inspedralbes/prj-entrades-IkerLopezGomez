-- Script d'inserts per al sistema de venda d'entrades.
USE ticketing_db;

-- Inserts per a pel·lícules
INSERT INTO movies (titol, descripcio, data, hora, preu, imatge_url) VALUES
('Dune: Part Two', 'L''epopeia de Paul Atreides continua.', '2026-04-10', '18:30:00', 9.50, 'https://image.tmdb.org/t/p/w500/8b8R8l3899v9D0XLaq9STWHExUM.jpg'),
('Interstellar', 'Viatge a través del temps i l''espai.', '2026-04-12', '20:00:00', 8.00, 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg'),
('Oppenheimer', 'La història de la bomba atòmica.', '2026-04-15', '21:00:00', 10.00, 'https://image.tmdb.org/t/p/w500/8Gxv2S9e6062G3dh290FlmIExvM.jpg'),
('The Dark Knight', 'Batman s''enfronta al Joker.', '2026-05-01', '19:00:00', 8.50, 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDr9p1v3toZp9PqU6rn.jpg');

-- Inserts per a concerts
INSERT INTO concerts (titol, artista, descripcio, data, hora, preu, imatge_url) VALUES
('Estopa - Gira 25 anys', 'Estopa', 'Concert al Palau Sant Jordi.', '2026-06-15', '21:00:00', 45.00, 'https://www.estopa.com/wp-content/uploads/2023/11/ESTOPA-25-ANOS.jpg'),
('Coldplay - Music of the Spheres', 'Coldplay', 'Espectacle visual i sonor únic.', '2026-05-20', '20:30:00', 85.00, 'https://images.sk-static.com/images/media/profile_images/artists/432128/huge_avatar'),
('The Tyets - Èpic Solete', 'The Tyets', 'Música festiva en català.', '2026-04-25', '22:00:00', 25.00, 'https://thetyets.com/wp-content/uploads/2023/03/the-tyets-epic-solete.jpg'),
('Bad Gyal - La Joia Tour', 'Bad Gyal', 'Ritmes de dancehall i reggaeton.', '2026-07-05', '21:30:00', 55.00, 'https://okdiario.com/look/img/2023/11/27/bad-gyal.jpg');
