DROP DATABASE IF EXISTS emprunt_db;
CREATE DATABASE emprunt_db;
USE emprunt_db;

CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre ENUM('H', 'F', 'Autre'),
    email VARCHAR(100) UNIQUE,
    ville VARCHAR(100),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);

CREATE TABLE categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100)
);

CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie) ON DELETE SET NULL,
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre) ON DELETE CASCADE
);

CREATE TABLE images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet) ON DELETE CASCADE
);

CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet) ON DELETE CASCADE,
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre) ON DELETE CASCADE
);

INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '2000-05-12', 'F', 'alice@gmail.com', 'Tana', 'mdp1', 'alice.jpg'),
('Bob', '1999-03-08', 'H', 'bob@gmail.com', 'Fianarantsoa', 'mdp2', 'bob.jpg'),
('Clara', '2001-09-23', 'F', 'clara@gmail.com', 'Majunga', 'mdp3', 'clara.jpg'),
('David', '2002-12-01', 'H', 'david@gmail.com', 'Tamatave', 'mdp4', 'david.jpg');

INSERT INTO categorie_objet (nom_categorie) VALUES
('Esthétique'), ('Bricolage'), ('Mécanique'), ('Cuisine');

INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES

('Sèche-cheveux', 1, 5), ('Trousse maquillage', 1, 5), ('Pinceau', 1, 5), ('Marteau', 2, 5),
('Clé plate', 3, 5), ('Spatule', 4, 5), ('Mixeur', 4, 5), ('Tournevis', 2, 5),
('Lime à ongles', 1, 5), ('Balance cuisine', 4, 5),

('Scie', 2, 2), ('Perceuse', 2, 2), ('Four', 4, 2), ('Friteuse', 4, 2),
('Casserole', 4, 2), ('Pelle', 2, 2), ('Ponceuse', 2, 2), ('Polisseuse', 1, 2),
('Crème visage', 1, 2), ('Rasoir', 1, 2),

('Tournevis étoile', 2, 3), ('Trousse à outils', 2, 3), ('Tapis de yoga', 1, 3), ('Fer à lisser', 1, 3),
('Poêle', 4, 3), ('Cuillère en bois', 4, 3), ('Couteau', 4, 3), ('Wok', 4, 3),
('Clé dynamométrique', 3, 3), ('Pompe à vélo', 3, 3),

('Tournevis plat', 2, 4), ('Cric', 3, 4), ('Compresseur', 3, 4), ('Batteur', 4, 4),
('Moule à gâteau', 4, 4), ('Masque visage', 1, 4), ('Vernis', 1, 4), ('Brosse', 1, 4),
('Coffret outils', 2, 4), ('Râpe cuisine', 4, 4);

INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 1, '2025-07-01', '2025-07-07'),
(2, 1, '2025-07-02', '2025-07-08'),
(3, 1, '2025-07-03', '2025-07-09'),
(4, 1, '2025-07-04', '2025-07-10'),
(5, 1, '2025-07-05', '2025-07-11'),
(6, 1, '2025-07-06', '2025-07-12'),
(7, 1, '2025-07-07', '2025-07-13'),
(8, 1, '2025-07-08', '2025-07-14'),
(9, 1, '2025-07-09', '2025-07-15'),
(10,1, '2025-07-10', '2025-07-16');

CREATE TABLE images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    est_principale BOOLEAN DEFAULT 0,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);


INSERT INTO images_objet (id_objet, nom_image) VALUES
(47, '13.jpg'),
(47, '14.jpg');