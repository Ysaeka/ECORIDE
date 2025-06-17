-- Active: 1744179742580@@127.0.0.1@3306@ecoride
-- Création de la base de données
CREATE DATABASE IF NOT EXISTS Ecoride;
USE Ecoride;

-- Table des utilisateurs (ne pas oublié d'ajouter les rôles - admin/ employe/ clients - mettre les libelle en cle étrangere et les credit)
CREATE TABLE users (
    users_id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100),
    first_name VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo BLOB,
    phone_number VARCHAR(20),
    adresse VARCHAR (20),
    credit INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE configuration (
    id_configuration INT AUTO_INCREMENT PRIMARY KEY
);

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

CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire VARCHAR(50),
    note VARCHAR(50),
    statut VARCHAR(50)
);

/*INSERT*/
INSERT INTO users (last_name, first_name, email, password, phone_number, adresse, photo) VALUES 
('Dupont', 'Jean', 'jean.dupont@mail.com', 'pass123', '0600000001', '10 rue des Lilas', NULL),
('Martin', 'Lucie', 'lucie.martin@mail.com', 'lucie456', '0600000002', '22 avenue Paris', NULL);

INSERT INTO configuration VALUES (1);

INSERT INTO parametre (propriete, valeur) VALUES 
('max_utilisateurs', '1000'),
('theme', 'sombre');


INSERT INTO role (libelle) VALUES 
('Administrateur')
('Employe')
('Conducteur'), 
('Passager');

INSERT INTO marque (libelle) VALUES 
('Toyota'),
('Peugeot'),
('Renault');


INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation, marque_id) VALUES 
('Yaris', 'AB-123-CD', 'Essence', 'Rouge', '2018-05-10', 1),
('308', 'EF-456-GH', 'Diesel', 'Noire', '2020-07-15', 2);

INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, statut, nb_place, prix_personne) VALUES 
('2025-05-20', '2025-05-20', 'Lyon', '2025-05-20', '12:00', 'Paris', 'ouvert', 3, 25.0),
('2025-05-22', '2025-05-22', 'Marseille', '2025-05-22', '15:30', 'Nice', 'ouvert', 2, 18.5);


INSERT INTO avis (commentaire, note, statut) VALUES 
('Très bon trajet !', '5', 'valide'),
('Conduite agréable', '4', 'valide');





