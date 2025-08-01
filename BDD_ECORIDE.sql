-- Active: 1744179742580@@127.0.0.1@3306@ecoride
-- Création de la base de données
CREATE DATABASE IF NOT EXISTS Ecoride;
USE Ecoride;

CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50)
);

CREATE TABLE users (
    users_id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100),
    first_name VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    adresse VARCHAR (255),
    photo_profil VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Credit INT,
    role_id INT DEFAULT 1,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

CREATE TABLE preferences (
    pref_id INT PRIMARY KEY,
    animaux BOOLEAN DEFAULT FALSE,
    fumeur BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (pref_id) REFERENCES users(users_id) ON DELETE CASCADE
);

CREATE TABLE parametre (
    parametre_id INT AUTO_INCREMENT PRIMARY KEY,
    propriete VARCHAR(50),
    valeur VARCHAR(50)
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
    date_premiere_immatriculation DATE,
    marque_id INT,
    users_id INT,
    FOREIGN KEY (marque_id) REFERENCES marque(marque_id),
    FOREIGN KEY (users_id) REFERENCES users(users_id)
);

CREATE TABLE covoiturage (
    covoiturage_id INT AUTO_INCREMENT PRIMARY KEY,
    date_depart DATE,
    heure_depart TIME,
    lieu_depart VARCHAR(50),
    date_arrivee DATE,
    heure_arrivee TIME,
    lieu_arrivee VARCHAR(50),
    statut ENUM ('non_démarré', 'en_cours', 'terminé') DEFAULT 'non_démarré',
    nb_place INT,
    prix_personne DECIMAL(10,2),
    voiture_id INT,
    conducteur_id INT,
    trajet_Ecologique BOOLEAN,
    Details TEXT,
    FOREIGN KEY (voiture_id) REFERENCES voiture(voiture_id),
    FOREIGN KEY (conducteur_id) REFERENCES users(users_id)
);

CREATE TABLE reservation (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    covoiturage_id INT NOT NULL,
    passager_id INT NOT NULL,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en attente', 'validée', 'en cours') DEFAULT 'en attente',
    nb_places_reservees INT NOT NULL DEFAULT 1,
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id),
    FOREIGN KEY (passager_id) REFERENCES users(users_id),
    UNIQUE (covoiturage_id, passager_id)
);

CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire VARCHAR(50),
    note TINYINT,
    statut VARCHAR(50),
    reviewer_id INT,
    reviewed_user_id INT,
    covoiturage_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (reviewer_id, covoiturage_id),
    FOREIGN KEY (reviewer_id) REFERENCES users(users_id),
    FOREIGN KEY (reviewed_user_id) REFERENCES users(users_id),
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id);
);

CREATE TABLE signalement (
    signalement_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    commentaire TEXT NOT NULL,
    statut ENUM('ouvert', 'résolu') DEFAULT 'ouvert',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id)
);


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





