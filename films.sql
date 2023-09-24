CREATE TABLE realisateur
(
	id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	prenom VARCHAR(50) NOT NULL,
	nom VARCHAR(50) NOT NULL
);

CREATE TABLE film
(
	id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	titre VARCHAR(200) NOT NULL,
	duree VARCHAR(4) NOT NULL,
	annee YEAR NOT NULL CHECK (annee > 1900),
	idRealisateur SMALLINT UNSIGNED,
	genre ENUM("Action", "Animation", "Aventure", "Biographie", "Comédie", "Crime", "Drame", "Fantaisie", "Horreur", "Romance", "Science-fiction") NOT NULL,
	FOREIGN KEY (idRealisateur) REFERENCES realisateur(id)
);