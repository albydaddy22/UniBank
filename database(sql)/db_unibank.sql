CREATE DATABASE IF NOT EXISTS UniBank;
USE UniBank;

CREATE TABLE Universita(
    id_universita INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    citta_sede VARCHAR(50) NOT NULL
);

CREATE TABLE Facolta(
    id_facolta INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE Materia(
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE MateriaPerFacolta(
    id_MateriaPerFacolta INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_materia INT(11) NOT NULL,
    id_facolta INT(11) NOT NULL,
    CONSTRAINT fk_categoria_materia FOREIGN KEY (id_materia) REFERENCES Materia(id_materia),
    CONSTRAINT fk_categoria_facolta FOREIGN KEY (id_facolta) REFERENCES Facolta(id_facolta),
    UNIQUE (id_materia, id_facolta)
);

CREATE TABLE Utenti(
    id_utente INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    ruolo BOOLEAN NOT NULL,
    saldo INT NOT NULL DEFAULT 20 CHECK (saldo >= 0), --saldo iniziale a 20 per dare la possibilita di acquistare almeno una dispensa
    id_universita INT(11) NOT NULL,
    id_facolta INT(11) NOT NULL,
    CONSTRAINT fk_utente_universita FOREIGN KEY (id_universita) REFERENCES Universita(id_universita),
    CONSTRAINT fk_utente_facolta FOREIGN KEY (id_facolta) REFERENCES Facolta(id_facolta)
);

CREATE TABLE Dispense(
    id_dispensa INT(11) AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(50) NOT NULL,
    descrizione VARCHAR(255) NOT NULL,
    prezzo INT(4) NOT NULL CHECK (prezzo >= 0),
    percorso_file VARCHAR(255) NOT NULL,
    data_caricamento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_utente INT(11) NOT NULL,
    id_MateriaPerFacolta INT(11) NOT NULL,
    CONSTRAINT fk_dispensa_utente FOREIGN KEY (id_utente) REFERENCES Utenti(id_utente),
    CONSTRAINT fk_dispensa_categoria FOREIGN KEY (id_MateriaPerFacolta) REFERENCES MateriaPerFacolta(id_MateriaPerFacolta)
);

CREATE TABLE Acquisti(
    id_acquisto INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_utente INT(11) NOT NULL,
    id_dispensa INT(11) NOT NULL,
    data_acquisto DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_acquisto_utente FOREIGN KEY (id_utente) REFERENCES Utenti(id_utente),
    CONSTRAINT fk_acquisto_dispensa FOREIGN KEY (id_dispensa) REFERENCES Dispense(id_dispensa),
    UNIQUE (id_utente, id_dispensa)
);