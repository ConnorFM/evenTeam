DROP DATABASE eventeam

CREATE DATABASE eventeam;
use eventeam

CREATE TABLE room (
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50),
capacity INT NOT NULL,
description TEXT NOT NULL
image TEXT NULL
);

CREATE TABLE status (
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(20)
);

CREATE TABLE users (
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
firstname varchar(30) NOT NULL,
lastname varchar(30) NOT NULL,
email varchar(50) NOT NULL,
status_ID INT NOT NULL,
image TEXT NULL,
password VARCHAR(50),
FOREIGN KEY (status_id) REFERENCES status(id)
);

CREATE TABLE events (
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(80) NOT NULL,
date_start DATETIME NOT NULL,
date_end DATETIME NOT NULL,
room_id INT NOT NULL,
description text NULL,
FOREIGN KEY (room_id) REFERENCES room(id)
);

CREATE TABLE user_event (
user_id INT NOT NULL,
event_id INT NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (event_id) REFERENCES events(id)
);

INSERT INTO room (name, capacity, description, image) VALUES
("Salle Réunion 1er Etage", 35, "Salle de réunion du premier étage a droite en sortant de l'ascenseur" "http://tinyurl.com/y5p7yjox"),
("Salle de Réception RDC", 300, "Salle de Réception pour les cocktail ou les evenement important de l'entreprise", "http://tinyurl.com/y5p7yjox"),
("Amphithéatre", 120, "Salle située au dernier étage", "http://tinyurl.com/y5p7yjox"),
("Salle de Réunion 2ème étage", 25, "Salle de réunion du premier étage a droite en sortant de l'ascenseur", "http://tinyurl.com/y5p7yjox"),

INSERT INTO status (name) VALUES 
('admin'), ('user');

INSERT INTO users (firstname, lastname, email, status_ID, image, password) VALUES
("Quentin", "BISIAUX", "quentin@bisiaux.fr", 1, "http://tinyurl.com/yxq8jnen", "quentinBISIAUX"),
("Noel", "AN", "noel@an.fr", 1, "http://tinyurl.com/yxq8jnen", "noelAN"),
("Foucauld", "GAUDIN", "foucauld@gaudin.fr", 1, "http://tinyurl.com/yxq8jnen", "foucauldGAUDIN"),
("Catherine", "VINCENT", "catherine@vincent.fr", 1, "http://tinyurl.com/yxq8jnen", "catherineVINCENT"),
("userFirstName", "userName", "user@user.fr", 2, "http://tinyurl.com/yxq8jnen", userUSER),

INSERT INTO events (name, date_start, date_end, room_id, description) VALUES
("réunion d'équipe", "2018-04-20 14:00:00", "2018-04-20 16:00:00", 1, "c'est une description"),
("cocktail de fin d'année", "2018-04-25 14:00:00", "2018-04-25 16:00:00", 2, "c'est une description"),
("Annonce résultats Annuel", "2018-04-29 14:00:00", "2018-04-29 16:00:00", 3, "c'est une description"),
("HACKATON", "2018-04-22 14:00:00", "2018-04-23 14:00:00", 3, "c'est une description"),
("organisation projet", "2018-04-25 16:00:00", "2018-04-25 18:00:00", 4, "c'est une description");



INSERT INTO user_event (user_id, event_id) VALUES
(3, 1), (4, 1), (2, 1),
(1, 2), (2, 2), (3, 2),
(1, 3), (2, 3),
(1, 4), (2, 4), (3, 4) (4, 4), (5, 4),
(3, 5), (4, 5);


