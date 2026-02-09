CREATE DATABASE IF NOT EXISTS cinema CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinema;

DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS screenings;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS movies;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    duration INT NOT NULL COMMENT 'minutes',
    poster VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    capacity INT NOT NULL COMMENT 'number of seats',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE screenings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    room_id INT NOT NULL,
    screening_date DATE NOT NULL,
    screening_time TIME NOT NULL,
    available_seats INT NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    screening_id INT NOT NULL,
    seats INT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (screening_id) REFERENCES screenings(id) ON DELETE CASCADE
);

-- Admin account (password: password)
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@cinema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Sample movies
INSERT INTO movies (title, description, duration) VALUES
('Inception', 'A thief who steals corporate secrets through dream-sharing technology.', 148),
('The Dark Knight', 'Batman fights the Joker in Gotham City.', 152),
('Interstellar', 'A team of explorers travel through a wormhole in space.', 169);

-- Sample rooms with different capacities
INSERT INTO rooms (name, capacity) VALUES
('Salle 1 - Grande', 150),
('Salle 2 - Moyenne', 100),
('Salle 3 - Petite', 50),
('Salle VIP', 30);

-- Sample screenings with room assignments
INSERT INTO screenings (movie_id, room_id, screening_date, screening_time, available_seats) VALUES
(1, 1, CURDATE(), '14:00:00', 150),
(1, 2, CURDATE(), '18:00:00', 100),
(1, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00:00', 50),
(2, 1, CURDATE(), '16:00:00', 150),
(2, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '19:00:00', 30),
(3, 2, CURDATE(), '20:00:00', 100),
(3, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '17:00:00', 150);
