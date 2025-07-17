-- Active: 1744179742580@@127.0.0.1@3306@ecoride
-- Création de la base de données
CREATE DATABASE IF NOT EXISTS Ecoride;
USE Ecoride;

-- Table des utilisateurs
CREATE TABLE users (
    users_id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100),
    first_name VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo BLOB,
    phone_number VARCHAR(20),
    adresse VARCHAR (50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Credit INT
);
ALTER TABLE users
ADD COLUMN role_id INT DEFAULT 1;

ALTER TABLE users
MODIFY COLUMN adresse VARCHAR(255);

ALTER TABLE users
MODIFY COLUMN photo VARCHAR(255);

ALTER TABLE users
ADD FOREIGN KEY (role_id) REFERENCES role(role_id);


CREATE TABLE configuration (
    id_configuration INT AUTO_INCREMENT PRIMARY KEY
);
DROP TABLE configuration;

CREATE TABLE parametre (
    parametre_id INT AUTO_INCREMENT PRIMARY KEY,
    propriete VARCHAR(50),
    valeur VARCHAR(50)
);

CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50)
);

CREATE TABLE marque (
    marque_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50)
);

CREATE TABLE voiture (
    voiture_id INT AUTO_INCREMENT PRIMARY KEY,
    modele VARCHAR(50),
    immatriculation VARCHAR(50),
    energie VARCHAR(50),
    couleur VARCHAR(50),
    date_premiere_immatriculation VARCHAR(50),
    marque_id INT,
    FOREIGN KEY (marque_id) REFERENCES marque(marque_id)
);
ALTER TABLE voiture
ADD COLUMN users_id INT,
ADD FOREIGN KEY (users_id) REFERENCES users(users_id);

ALTER TABLE voiture
MODIFY COLUMN date_premiere_immatriculation DATE;

CREATE TABLE covoiturage (
    covoiturage_id INT AUTO_INCREMENT PRIMARY KEY,
    date_depart DATE,
    heure_depart DATE,
    lieu_depart VARCHAR(50),
    date_arrivee DATE,
    heure_arrivee VARCHAR(50),
    lieu_arrivee VARCHAR(50),
    statut VARCHAR(50),
    nb_place INT,
    prix_personne FLOAT
);

ALTER TABLE covoiturage
MODIFY heure_depart TIME,
MODIFY heure_arrivee TIME;

ALTER TABLE covoiturage
MODIFY prix_personne DECIMAL(10,2);

ALTER TABLE covoiturage
ADD COLUMN voiture_id INT,
ADD FOREIGN KEY (voiture_id) REFERENCES voiture(voiture_id);

ALTER TABLE covoiturage
ADD COLUMN conducteur_id INT,
ADD FOREIGN KEY (conducteur_id) REFERENCES users(users_id);

ALTER TABLE covoiturage
ADD COLUMN trajet_Ecologique BOOLEAN,
ADD COLUMN Details VARCHAR(255);

ALTER TABLE covoiturage
MODIFY Details TEXT (5000);

ALTER TABLE covoiturage
DROP COLUMN statut;

ALTER TABLE covoiturage
ADD COLUMN statut ENUM('non_démarré', 'en_cours', 'termniné') DEFAULT 'non_demarré';

ALTER TABLE covoiturage
MODIFY statut ENUM ('non_démarré', 'en_cours', 'terminé') DEFAULT 'non_démarré';

ALTER TABLE covoiturage
MODIFY statut ENUM ('non_démarré', 'terminé') DEFAULT 'non_démarré';

CREATE TABLE reservation (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    covoiturage_id INT NOT NULL,
    passager_id INT NOT NULL,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'en attente de confirmation',
    nb_places_reservees INT NOT NULL DEFAULT 1,
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id),
    FOREIGN KEY (passager_id) REFERENCES users(users_id),
    UNIQUE (covoiturage_id, passager_id)
);

CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire VARCHAR(50),
    note VARCHAR(50),
    statut VARCHAR(50)
);

ALTER TABLE avis
ADD COLUMN reviewer_id INT,
ADD COLUMN reviewed_user_id INT,
ADD COLUMN covoiturage_id INT,
MODIFY COLUMN note TINYINT;

ALTER TABLE avis
ADD FOREIGN KEY (reviewer_id) REFERENCES users(users_id),
ADD FOREIGN KEY (reviewed_user_id) REFERENCES users(users_id),
ADD FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id);


/*INSERT*/
INSERT INTO users (last_name, first_name, email, password, phone_number, adresse, photo) VALUES 
('Dupont', 'Jean', 'jean.dupont@mail.com', 'pass123', '0600000001', '10 rue des Lilas', NULL),
('Martin', 'Lucie', 'lucie.martin@mail.com', 'lucie456', '0600000002', '22 avenue Paris', NULL);

INSERT INTO users (last_name, first_name, email, password, role_id) VALUES
('Martin', 'Lea', 'admnin@mail.com', 'passAdmin123@', 2 ),
('Petit', 'Romain', 'employe1@mail.com', 'passEmploye123@', 3),
('Garcia', 'Anais', 'employe2@mail.com', 'passEmploye456@', 3)


INSERT INTO parametre (propriete, valeur) VALUES 
('max_utilisateurs', '1000'),
('theme', 'sombre');

INSERT INTO role (libelle) VALUES 
('Utilisateur'),
('Administrateur'),
('Employe');

INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation, marque_id, users_id) VALUES 
('Yaris', 'AB-123-CD', 'Essence', 'Rouge', '2018-05-10', 1, 1),
('308', 'EF-456-GH', 'Diesel', 'Noire', '2020-07-15', 2, 2);

INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, statut, nb_place, prix_personne) VALUES 
('2025-05-20', '2025-05-20', 'Lyon', '2025-05-20', '12:00', 'Paris', 'ouvert', 3, 25.0),
('2025-05-22', '2025-05-22', 'Marseille', '2025-05-22', '15:30', 'Nice', 'ouvert', 2, 18.5);

INSERT INTO avis (commentaire, note, statut) VALUES 
('Très bon trajet !', '5', 'valide'),
('Conduite agréable', '4', 'valide');





