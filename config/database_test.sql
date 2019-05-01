	DROP DATABASE `eventeam`;

	CREATE DATABASE eventeam;
	use eventeam;

	CREATE TABLE room (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50),
	capacity INT NOT NULL,
	description TEXT NOT NULL,
	image TEXT NULL
	);

	CREATE TABLE status (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20)
	);

	CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	firstname varchar(30) NOT NULL,
	lastname varchar(30) NOT NULL,
	email varchar(50) NOT NULL,
	status_id INT NOT NULL,
	image TEXT NULL,
	password VARCHAR(50),
	FOREIGN KEY (status_id) REFERENCES status(id)
	);

	CREATE TABLE events (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	creator INT NOT NULL,
	name VARCHAR(80) NOT NULL,
	date_start DATETIME NOT NULL,
	date_end DATETIME NOT NULL,
	room_id INT NULL,
	description text NULL,
	FOREIGN KEY (room_id) REFERENCES room(id),
	FOREIGN KEY (creator) REFERENCES users(id)
	);

	CREATE TABLE user_event (
	user_id INT NOT NULL,
	event_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (event_id) REFERENCES events(id)
	);

	INSERT INTO room (name, capacity, description, image) VALUES
	("Salle PHP", 20, "Salle du RDC à gauche en rentrant à la Wild", "http://tinyurl.com/y5p7yjox"),
	("Salle React", 20, "Salle au deuxième Etage", "http://tinyurl.com/y5p7yjox"),
	("Wild Room", 70, "Salle de déjeuner et de Chill au Premier étage", "http://tinyurl.com/y5p7yjox"),
	("Salle Angular",70, "Salle se situant au 1er étage du 12Ter Quai Perrache, 69002 Lyon ", "http://tinyurl.com/y5p7yjox"),
	("Salle Formateurs",7, "Salle du RDC à droite en rentrant à la Wild", "http://tinyurl.com/y5p7yjox");

	INSERT INTO status (name) VALUES
	('admin'), ('user');

	INSERT INTO users (firstname, lastname, email, status_id, image, password) VALUES
	("Quentin", "BISIAUX", "quentin@bisiaux.fr", 1, "http://tinyurl.com/yxq8jnen", "quentinBISIAUX"),
	("Noel", "AN", "noel@an.fr", 1, "http://tinyurl.com/yxq8jnen", "noelAN"),
	("Foucauld", "GAUDIN", "foucauld@gaudin.fr", 1, "http://tinyurl.com/yxq8jnen", "foucauldGAUDIN"),
	("Catherine", "VINCENT", "catherine@vincent.fr", 1, "http://tinyurl.com/yxq8jnen", "catherineVINCENT"),
	("Jean Daniel", "BOCCARA", "jd@boccara.fr", 2, "http://tinyurl.com/yxq8jnen", "jdBOCCARA"),
	("Laeticia", "VARELA", "laeticia@varela.fr", 1, "http://tinyurl.com/yxq8jnen", "laeticiaVARELA"),
	("Kevin", "HEITZ", "kevin@heitz.fr", 2, "http://tinyurl.com/yxq8jnen", "kevinHEITZ");

	INSERT INTO events (name, creator, date_start, date_end, room_id, description) VALUES
	("Démo day",6,"2019-05-02 14:00:00", "2019-05-02 18:00:00",4,"Présentation des projets 2 de toute la promo "),
	("Bière du vendredi Soir",3,"2019-05-03 17:00:00", "2019-05-03 20:00:00", NULL, "C'est la fin de semaine allons boire une bière"),
	("Wild Breakfast",6, "2019-05-15 09:00:00", "2019-05-15 11:00:00", 3, "Accueil des futurs wilders potentiels"),
	("HACKATON",6, "2019-04-22 14:00:00", "2019-04-23 14:00:00", 3, "C'est parti pour une nuit blanche"),
	("organisation projet",3,"2019-05-02 09:00:00", "2019-05-02 12:00:00", 1, "Préparation de la présentation du projet lors de la démo day"),
	("Vacances",3,"2019-05-06 09:00:00", "2019-05-10 23:00:00", NULL , "Bon repos à tous");

	INSERT INTO user_event (user_id, event_id) VALUES
	(1, 1), (2, 1), (3, 1),(4, 1), (5, 1), (6, 1),(7, 1),
	(1, 2), (2, 2), (3, 2),(4, 2), (5, 2), (6, 2),(7, 2),
	(2, 3), (5, 3), (6, 3),
	(1, 4), (2, 4), (3, 4), (4, 4), (5, 4),
	(1, 5), (2, 5), (3, 5), (4, 5),
	(1, 6), (2, 6), (3, 6),(4, 6), (5, 6), (6, 6),(7, 6);