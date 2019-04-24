DROP DATABASE eventeam

CREATE DATABASE eventeam;
use eventeam

CREATE TABLE room (
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50),
capacity INT NOT NULL,
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



INSERT INTO room (name, capacity, image) VALUES
("room1", 100, "http://tinyurl.com/y5p7yjox"),
("room2", 200, "http://tinyurl.com/y5p7yjox"),
("room3", 300, "http://tinyurl.com/y5p7yjox"),
("room4", 400, "http://tinyurl.com/y5p7yjox"),
("room5", 500, "http://tinyurl.com/y5p7yjox"),
("room6", 600, "http://tinyurl.com/y5p7yjox"),
("room7", 700, "http://tinyurl.com/y5p7yjox"),
("room8", 800, "http://tinyurl.com/y5p7yjox"),
("room9", 900, "http://tinyurl.com/y5p7yjox"),
("room10", 1000, "http://tinyurl.com/y5p7yjox");


INSERT INTO status (name) VALUES 
('admin'), ('user');


INSERT INTO users (firstname, lastname, email, status_ID, image, password) VALUES 
("Quentin", "BISIAUX", "quentin.bisiaux@gmail.com", 1, "http://tinyurl.com/yxq8jnen", "unmdpauhasard"),
("userfirstname2", "userlastname2", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname3", "userlastname3", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname4", "userlastname4", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname5", "userlastname5", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname6", "userlastname6", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname7", "userlastname7", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname8", "userlastname8", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname9", "userlastname9", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password"),
("userfirstname10", "userlastname10", "test/@gmail.com", 2, "http://tinyurl.com/yxq8jnen", "password");


INSERT INTO events (name, date_start, date_end, room_id, description) VALUES
("event1", "2018-04-20 14:00:00", "2018-04-20 16:00:00", 1, "c'est une description"),
("event2", "2018-04-25 14:00:00", "2018-04-25 16:00:00", 2, "c'est une description"),
("event3", "2018-04-29 14:00:00", "2018-04-29 16:00:00", 3, "c'est une description"),
("HACKATON", "2018-04-22 14:00:00", "2018-04-23 14:00:00", 4, "c'est une description"),
("event5", "2018-04-25 16:00:00", "2018-04-25 18:00:00", 5, "c'est une description");



INSERT INTO user_event (user_id, event_id) VALUES
(3, 1), (4, 1), (10, 1), (8, 1), 
(1, 2), (2, 2), (3, 2),
(1, 4), (2, 4), (7, 4), (6, 4), (9, 4),
(3, 5), (4, 5), (10, 5), (8, 5);

