CREATE DATABASE IF NOT EXISTS formcampus
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE formcampus;

CREATE TABLE IF NOT EXISTS formations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(150) NOT NULL,
  categorie VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  duree VARCHAR(50) NOT NULL,
  prix DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS inscriptions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  tel VARCHAR(30) NOT NULL,
  id_formation INT UNSIGNED NOT NULL,
  commentaire TEXT NULL,
  date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_inscriptions_formation
    FOREIGN KEY (id_formation) REFERENCES formations(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (login, password)
SELECT 'admin', '$2y$12$4gl0VXFx.lUkmxq45ffdVuI.3bmelIFXT02/sGBPVIjA8dupBN76G'
WHERE NOT EXISTS (
  SELECT 1 FROM users WHERE login = 'admin'
);

INSERT INTO formations (titre, categorie, description, duree, prix)
SELECT 'Developpement Web PHP', 'Developpement', 'Apprenez a creer des applications web avec PHP, MySQL et JavaScript.', '8 semaines', 499.00
WHERE NOT EXISTS (SELECT 1 FROM formations WHERE titre = 'Developpement Web PHP');

INSERT INTO formations (titre, categorie, description, duree, prix)
SELECT 'UI/UX Design Fondamental', 'Design', 'Introduction aux principes de design d interface, wireframes et prototypage.', '6 semaines', 399.00
WHERE NOT EXISTS (SELECT 1 FROM formations WHERE titre = 'UI/UX Design Fondamental');

INSERT INTO formations (titre, categorie, description, duree, prix)
SELECT 'Marketing Digital', 'Marketing', 'Maitrisez les bases du SEO, reseaux sociaux et campagnes publicitaires.', '5 semaines', 350.00
WHERE NOT EXISTS (SELECT 1 FROM formations WHERE titre = 'Marketing Digital');

INSERT INTO formations (titre, categorie, description, duree, prix)
SELECT 'Data Analyse avec Excel', 'Data', 'Analyse de donnees, tableaux croises dynamiques et reporting professionnel.', '4 semaines', 290.00
WHERE NOT EXISTS (SELECT 1 FROM formations WHERE titre = 'Data Analyse avec Excel');

INSERT INTO inscriptions (nom, prenom, email, tel, id_formation, commentaire, date_inscription)
SELECT 'Dupont', 'Alice', 'alice.dupont@example.com', '0601020304', f.id, 'Tres motivee pour cette formation.', NOW()
FROM formations f
WHERE f.titre = 'Developpement Web PHP'
  AND NOT EXISTS (
    SELECT 1 FROM inscriptions i WHERE i.email = 'alice.dupont@example.com'
  );

INSERT INTO inscriptions (nom, prenom, email, tel, id_formation, commentaire, date_inscription)
SELECT 'Martin', 'Yanis', 'yanis.martin@example.com', '0611223344', f.id, 'Disponible en soiree uniquement.', NOW()
FROM formations f
WHERE f.titre = 'UI/UX Design Fondamental'
  AND NOT EXISTS (
    SELECT 1 FROM inscriptions i WHERE i.email = 'yanis.martin@example.com'
  );

INSERT INTO inscriptions (nom, prenom, email, tel, id_formation, commentaire, date_inscription)
SELECT 'Bernard', 'Sofia', 'sofia.bernard@example.com', '0677889900', f.id, 'Souhaite evoluer professionnellement.', NOW()
FROM formations f
WHERE f.titre = 'Marketing Digital'
  AND NOT EXISTS (
    SELECT 1 FROM inscriptions i WHERE i.email = 'sofia.bernard@example.com'
  );
