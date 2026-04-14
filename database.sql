CREATE DATABASE IF NOT EXISTS `UniBank`;
USE `UniBank`;

CREATE TABLE `Utente` (
    `id_utente` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(20) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(30) UNIQUE NOT NULL,
    'ruolo' BOOLEAN NOT NULL,
    'saldo' INT NOT NULL,
    'id_universita' INT(11) NOT NULL,
    'id_facolta' INT(11) NOT NULL,
    CONSTRAINT 'id_universita' FOREIGN KEY (id_universita) REFERENCES Universita(id_universita),
    CONSTRAINT 'id_facolta' FOREIGN KEY (id_facolta) REFERENCES Facolta(id_facolta)
),

CREATE TABLE 'Universita' (
    `id_universita` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(50) NOT NULL,
    'citta_sede' VARCHAR(50) NOT NULL
),

CREATE TABLE 'Facolta' (
    `id_facolta` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(50) NOT NULL,
),

CREATE TABLE 'Dispensa' (
    'id_dispensa' INT(11) AUTO_INCREMENT PRIMARY KEY,
    'titolo' VARCHAR(50) NOT NULL,
    'descrizione' VARCHAR(255) NOT NULL,
    'prezzo' INT NOT NULL,
    'file' VARCHAR(255) NOT NULL,
    'data_car' DATETIME NOT NULL,
    
)