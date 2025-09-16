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
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Credit INT,
    role_id INT DEFAULT 1,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);
ALTER TABLE users 
ADD statut ENUM('actif','suspendu') DEFAULT 'actif';
ALTER TABLE users
ADD user_type ENUM('chauffeur','passager','deux') NOT NULL DEFAULT 'passager';

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
ALTER TABLE covoiturage MODIFY statut ENUM ('non_démarré', 'en_cours', 'terminé', 'annulé') DEFAULT 'non_démarré';

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
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id)
);

CREATE TABLE signalement (
    signalement_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    commentaire TEXT NOT NULL,
    statut ENUM('ouvert', 'résolu') DEFAULT 'ouvert',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id)
);

/* Désactive les clés étrangères (pour éviter les conflits)*/
SET FOREIGN_KEY_CHECKS=0;


/* INSERTS */

/* Insertion des rôles */
INSERT INTO role (role_id, libelle) VALUES
(1, 'Utilisateur'),
(2, 'Administrateur'),
(3, 'Employe');

/* Insertion des users (chauffeurs, passager et "les deux")*/
INSERT INTO users (last_name, first_name, email, password, phone_number, adresse, Credit, role_id, user_type, statut)
VALUES
('Dupont', 'Jean', 'jean.dupont@mail.com', '$2b$12$J7kF6U4TlSBN7KncM0XqnODSu7YWQxCFSwY5Eu1fjHu45vL72vwU.', '0600000001', '12 rue de Paris, Lyon', 100, 1, 'chauffeur','actif'),
('Fernandere', 'Jacky.fernandere@mail.com', 'sophie.martin@mail.com', '$2b$12$uKZWVVYwpW89o/lqBda4cuDt3CnoMPZBNxE.gNmtUYlNrcWVYCggi', '0600000002', '5 avenue République, Marseille', 100, 1, 'chauffeur','actif'),
('Durand', 'Paul', 'paul.durand@mail.com', '$2b$12$bl7NFAT9gDSfFQpuCglbOuEkeFJqXUSXJpb6o3dyYCBR5b.CfltAu', '0600000003', '8 bd Victor Hugo, Lille', 100, 1, 'chauffeur','actif'),
('Bernard', 'Lucie', 'lucie.bernard@mail.com', '$2b$12$1e3D1B3sOW/hDoIaT9rYFes1GTylxclU4bkmvW1I7j84ruglLOqLW', '0600000004', '3 rue des Lilas, Toulouse', 100, 1, 'chauffeur','actif'),
('Petit', 'Marc', 'marc.petit@mail.com', '$2b$12$Q.sYNdpNk60dq2ADx/M6kut.WVyuDWn5.Wbm9FYKXcRiB8SD5xQ2W', '0600000005', '10 chemin Vert, Nantes', 100, 1, 'chauffeur','actif'),
('Robert', 'Claire', 'claire.robert@mail.com', '$2b$12$HxlA03kRlAMTrRe/CNi9E.UF/Av/tYkINs7aJitTkdpofsPEQUTRy', '0600000006', '7 place Bellecour, Lyon', 100, 1, 'chauffeur','actif'),
('Richard', 'Hugo', 'hugo.richard@mail.com', '$2b$12$nr8hdHhgyJQwU0gpaA.Up.hU/8EcBTE0eLumpeOR8swyPMLXV9/CW', '0600000007', '22 rue Lafayette, Bordeaux', 100, 1, 'chauffeur','actif'),
('Moreau', 'Emma', 'emma.moreau@mail.com', '$2b$12$VSxtoV1aizRkLaMPdbuIqOCm.IgXSPmthNxsyZLHaXvXYaHx6pVAa', '0600000008', '15 rue Alsace, Rennes', 100, 1, 'chauffeur','actif'),
('Laurent', 'Louis', 'louis.laurent@mail.com', '$2b$12$U9twgiNNQHcM52y2jAN94ub1m8NX.rkXw/NSvard743dZWYzwRSu.', '0600000009', '18 av Liberté, Strasbourg', 100, 1, 'chauffeur','actif'),
('Simon', 'Julie', 'julie.simon@mail.com', '$2b$12$fQyAQDV.bF7FSaDGHOSuJ.ykaLXZfdP9/j7EcVvbzqNauhIdE7.NS', '0600000010', '9 impasse des Roses, Nice', 100, 1, 'chauffeur','actif'),

('Thomas', 'Alice', 'alice.thomas@mail.com', '$2b$12$OBlR1OmQ48lSYfK/Pkd8nenC8TAXf8Q.AMs6QHscPN3idhHXSBimi', '0600000011', '25 rue Pasteur, Paris', 100, 2, 'passager','actif'),
('Lefebvre', 'Mathieu', 'mathieu.lefebvre@mail.com', '$2b$12$vM0Fr5p/tAC8w1ydvkwAxuzIv8pYNKOvC2MBFS51PUeH9Ns1tqHrq', '0600000012', '30 rue Nationale, Lyon', 100, 1, 'passager','actif'),
('Garcia', 'Camille', 'camille.garcia@mail.com', '$2b$12$alihEKP8FLJABfFwYOV2n.BuDX4Pms6JEOHfgZGZZd0Nfis3Ooh8S', '0600000013', '2 rue Victor Hugo, Marseille', 100, 1, 'passager','actif'),
('David', 'Lucas', 'lucas.david@mail.com', '$2b$12$NO172KCFSxZQ5i/U/jXFVOdxwJYKMkdUDtLqPLZQHCAXwWP40WzvO', '0600000014', '6 allée Jardins, Bordeaux', 100, 1, 'passager','actif'),
('Roux', 'Léa', 'lea.roux@mail.com', '$2b$12$xmWmHnnGizw9M1rDaUQKAO0fxpeTeAk6nj3sxWukTDkkypcgKuUvq', '0600000015', '17 rue Fleurs, Toulouse', 100, 1, 'passager','actif'),
('Vincent', 'Noah', 'noah.vincent@mail.com', '$2b$12$xjZ12I6NEdV.K.CRuSpCu.EQe05YgNgMXx6JiRtXQMaXXYi6YxU6q', '0600000016', '11 rue Colbert, Nantes', 100, 1, 'passager','actif'),
('Muller', 'Chloé', 'chloe.muller@mail.com', '$2b$12$v3KnvFAeQ7T5BesNgubsDe3X.FET1fNh2lAt.FiIIoC2QOo0JhPqG', '0600000017', '4 bd Wilson, Strasbourg', 100, 1, 'passager','actif'),
('Faure', 'Enzo', 'enzo.faure@mail.com', '$2b$12$YnO9uMBk9614JE1I.58g0.7Gd1dG0palJly1a.p9iHy549lFm0OVO', '0600000018', '20 rue République, Rennes', 100, 1, 'passager','actif'),
('Andre', 'Manon', 'manon.andre@mail.com', '$2b$12$T4/YWnIGVR.piQ0QU43A.ukyfuRtXk0eo/k3Yi5tOGulyR4CeRIYe', '0600000019', '7 rue St Michel, Lille', 100, 3, 'passager','actif'),
('Mercier', 'Hugo', 'hugo.mercier@mail.com', '$2b$12$7dWlz0b7Tq2FOkYHeItGFuuADNiyDJj4jP4cmi4wTAONaSgNrM6La', '0600000020', '19 rue du Port, Nice', 100, 3, 'passager','actif'),

('Blanc', 'Sarah', 'sarah.blanc@mail.com', '$2b$12$EDYfz449PcIC2bdnH7K.Yeh3sZqmpQnCdNBjn35RTMvOQ184U6DXK', '0600000021', '21 rue Lafayette, Paris', 100, 1, 'deux','actif'),
('Guerin', 'Thomas', 'thomas.guerin@mail.com', '$2b$12$XrUKNoXzZaMK9FZBIAfzR./Y7MRy4siu6Jy0GuuSPLNWNoH0BAX.K', '0600000022', '12 rue Bretagne, Lyon', 100, 1, 'deux','actif'),
('Henry', 'Laura', 'laura.henry@mail.com', '$2b$12$nMsz6kT0auFrR45LF2U7geQa2xvEuggU5Jc5cvtBbopAQ6O4OXgxS', '0600000023', '14 rue Alsace, Marseille', 100, 1, 'deux','actif'),
('Perrin', 'Antoine', 'antoine.perrin@mail.com', '$2b$12$ggX59vtMV0V8az.ZNOjmOuKzIAT8lg7DfwkLvJ.NKhLhirOGCVSZS', '0600000024', '9 av Vosges, Strasbourg', 100, 1, 'deux','actif'),
('Collet', 'Emma', 'emma.collet@mail.com', '$2b$12$zL5XJoOGwMUWxlInKV8PSeM.6nXIg/oX6syIx8siILmk7o2P8j.NK', '0600000025', '33 rue Liberté, Toulouse', 100, 1, 'deux','actif');

/* Insertions des marques */
INSERT INTO marque (libelle) VALUES
('Renault'),('Peugeot'),('Citroen'),('Tesla'),('BMW');

/* Insertion des voitures */
INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation, marque_id, users_id)
VALUES
('Clio', 'AB-123-CD', 'Electrique', 'Rouge', '2020-01-15', 1, 1),
('208', 'EF-456-GH', 'Diesel', 'Bleu', '2019-05-10', 2, 2),
('C3', 'IJ-789-KL', 'Essence', 'Blanc', '2021-03-22', 3, 3),
('Model 3', 'MN-234-OP', 'Electrique', 'Noir', '2022-08-12', 4, 4),
('Serie 1', 'QR-567-ST', 'Diesel', 'Gris', '2018-07-30', 5, 5),
('Clio', 'UV-890-WX', 'Essence', 'Bleu', '2020-02-14', 1, 6),
('208', 'YZ-123-AB', 'Electrique', 'Rouge', '2019-11-19', 2, 7),
('C3', 'CD-456-EF', 'Diesel', 'Blanc', '2021-06-01', 3, 8),
('Model 3', 'GH-789-IJ', 'Essence', 'Noir', '2022-09-05', 4, 9),
('Serie 1', 'KL-012-MN', 'Electrique', 'Gris', '2018-12-20', 5, 10),
('Clio', 'OP-345-QR', 'Diesel', 'Rouge', '2020-03-11', 1, 21),
('208', 'ST-678-UV', 'Essence', 'Bleu', '2019-06-18', 2, 22),
('C3', 'WX-901-YZ', 'Electrique', 'Blanc', '2021-08-09', 3, 23),
('Model 3', 'AB-234-CD', 'Diesel', 'Noir', '2022-10-01', 4, 24),
('Serie 1', 'EF-567-GH', 'Essence', 'Gris', '2018-05-25', 5, 25);

/* Insertion des covoiturages */
INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, nb_place, prix_personne, voiture_id, conducteur_id, trajet_Ecologique, Details, statut)
VALUES
('2025-09-15', '08:00:00', 'Paris', '2025-09-15', '12:00:00', 'Lyon', 3, 35.00, 1, 1, TRUE, 'RAS', 'non_démarré'),
('2025-09-16', '09:30:00', 'Lyon', '2025-09-16', '13:30:00', 'Marseille', 2, 40.00, 1, 1, TRUE, 'RAS', 'terminé'),

('2025-09-15', '07:00:00', 'Marseille', '2025-09-15', '11:00:00', 'Toulouse', 4, 30.00, 2, 2, FALSE, 'RAS', 'non_démarré'),
('2025-09-17', '14:00:00', 'Toulouse', '2025-09-17', '18:00:00', 'Bordeaux', 3, 45.00, 2, 2, FALSE, 'RAS', 'terminé'),

('2025-09-16', '06:30:00', 'Bordeaux', '2025-09-16', '10:30:00', 'Nantes', 3, 25.00, 3, 3, FALSE, 'RAS', 'non_démarré'),
('2025-09-18', '12:00:00', 'Nantes', '2025-09-18', '16:00:00', 'Paris', 2, 50.00, 3, 3, FALSE, 'RAS', 'non_démarré'),

('2025-09-15', '08:45:00', 'Strasbourg', '2025-09-15', '12:30:00', 'Lille', 5, 20.00, 4, 4, TRUE, 'RAS', 'terminé'),
('2025-09-16', '15:00:00', 'Lille', '2025-09-16', '19:00:00', 'Paris', 3, 35.00, 4, 4, TRUE, 'RAS', 'terminé'),

('2025-09-15', '09:00:00', 'Rennes', '2025-09-15', '13:00:00', 'Nantes', 4, 28.00, 5, 5, FALSE, 'RAS', 'terminé'),
('2025-09-17', '10:30:00', 'Nantes', '2025-09-17', '14:30:00', 'Bordeaux', 2, 42.00, 5, 5, FALSE, 'RAS', 'non_démarré');

/* Insertion des réservations */
INSERT INTO reservation (covoiturage_id, passager_id, nb_places_reservees, statut)
VALUES
(1, 11, 1, 'validée'),
(2, 12, 2, 'en attente'),
(3, 13, 1, 'validée'),
(4, 14, 1, 'en attente'),
(5, 15, 2, 'validée'),
(6, 16, 1, 'en attente'),
(7, 17, 1, 'validée'),
(8, 18, 2, 'en attente'),
(9, 19, 1, 'validée'),
(10, 20, 1, 'en attente');

/* Réactivation des clés étrangères */
SET FOREIGN_KEY_CHECKS=1;
