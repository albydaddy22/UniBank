<?php
session_start();

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: install.html');
    exit;
}

$databaseName = trim($_POST['nomedatabase'] ?? '');
$dbUser = trim($_POST['username'] ?? '');
$dbHost = trim($_POST['indirizzo'] ?? '');
$dbPassword = $_POST['password'] ?? '';

$errors = [];

if($databaseName === '' || !preg_match('/^[A-Za-z0-9_]+$/', $databaseName)){
    $errors[] = 'Nome database non valido. Usa solo lettere, numeri e underscore.';
}
if($dbUser === ''){
    $errors[] = 'Username database è obbligatorio.';
}
if($dbHost === ''){
    $errors[] = 'Indirizzo server è obbligatorio.';
}

if(!empty($errors)){
    echo '<div style="color:red;">';
    echo '<p>Errore nell\'installazione:</p>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
    echo '<p><a href="install.html">Torna al form di installazione</a></p>';
    exit;
}

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword);
if(!$conn){
    die('Connessione al database fallita: ' . mysqli_connect_error());
}

$dbNameEscaped = mysqli_real_escape_string($conn, $databaseName);

$sql = "
CREATE DATABASE IF NOT EXISTS `$dbNameEscaped`;
USE `$dbNameEscaped`;

CREATE TABLE IF NOT EXISTS universita(
    id_universita INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    citta_sede VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS facolta(
    id_facolta INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS materia(
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS materiaperfacolta(
    id_materiaperfacolta INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_materia INT(11) NOT NULL,
    id_facolta INT(11) NOT NULL,
    CONSTRAINT fk_categoria_materia FOREIGN KEY (id_materia) REFERENCES materia(id_materia),
    CONSTRAINT fk_categoria_facolta FOREIGN KEY (id_facolta) REFERENCES facolta(id_facolta),
    UNIQUE (id_materia, id_facolta)
);

CREATE TABLE IF NOT EXISTS utenti(
    id_utente INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    ruolo BOOLEAN NOT NULL,
    saldo INT NOT NULL DEFAULT 20 CHECK (saldo >= 0),
    bloccato BOOLEAN NOT NULL DEFAULT 0,
    data_iscrizione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_universita INT(11) NOT NULL,
    id_facolta INT(11) NOT NULL,
    CONSTRAINT fk_utente_universita FOREIGN KEY (id_universita) REFERENCES universita(id_universita),
    CONSTRAINT fk_utente_facolta FOREIGN KEY (id_facolta) REFERENCES facolta(id_facolta)
);

CREATE TABLE IF NOT EXISTS dispense(
    id_dispensa INT(11) AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    descrizione VARCHAR(255) NOT NULL,
    prezzo INT(4) NOT NULL CHECK (prezzo >= 0),
    percorso_file VARCHAR(255) NOT NULL,
    data_caricamento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    approvata BOOLEAN NOT NULL DEFAULT 0,
    id_utente INT(11) NOT NULL,
    id_materiaperfacolta INT(11) NOT NULL,
    CONSTRAINT fk_dispensa_utente FOREIGN KEY (id_utente) REFERENCES utenti(id_utente),
    CONSTRAINT fk_dispensa_categoria FOREIGN KEY (id_materiaperfacolta) REFERENCES materiaperfacolta(id_materiaperfacolta)
);

CREATE TABLE IF NOT EXISTS acquisti(
    id_acquisto INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_utente INT(11) NOT NULL,
    id_dispensa INT(11) NOT NULL,
    data_acquisto DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_acquisto_utente FOREIGN KEY (id_utente) REFERENCES utenti(id_utente),
    CONSTRAINT fk_acquisto_dispensa FOREIGN KEY (id_dispensa) REFERENCES dispense(id_dispensa),
    UNIQUE (id_utente, id_dispensa)
);
";

$queries = array_filter(array_map('trim', explode(';', $sql)));
foreach($queries as $query){
    if(!mysqli_query($conn, $query)){
        die('Errore durante la creazione del database: ' . mysqli_error($conn));
    }
}

mysqli_select_db($conn, $dbNameEscaped);

$checkUniversita = mysqli_query($conn, 'SELECT COUNT(*) as tot FROM universita');
$row = mysqli_fetch_assoc($checkUniversita);
if((int)$row['tot'] === 0){
    $passHashAdmin = password_hash('admin123', PASSWORD_DEFAULT);
    $passHashUtente = password_hash('utente123', PASSWORD_DEFAULT);

    $inserisciDati = "
    INSERT INTO universita (nome, citta_sede) VALUES
    ('Università politecnica delle Marche', 'Ancona'),
    ('Università degli Studi della Valle d\'Aosta', 'Aosta'),
    ('Università degli Studi di Bari', 'Bari'),
    ('Politecnico di Bari', 'Bari'),
    ('Università LUM \"Jean Monnet\"', 'Bari'),
    ('Università degli Studi di Benevento - Sannio', 'Benevento'),
    ('Università degli Studi di Bergamo', 'Bergamo'),
    ('Università degli Studi di Bologna', 'Bologna'),
    ('Libera Università di Bolzano', 'Bolzano'),
    ('Università degli Studi di Brescia', 'Brescia'),
    ('Università degli Studi di Cagliari', 'Cagliari'),
    ('Università degli Studi di Camerino', 'Camerino'),
    ('Università degli Studi del Molise', 'Campobasso'),
    ('Università degli Studi di Cassino e Lazio Meridionale', 'Cassino'),
    ('Università Cattaneo - LIUC Castellanza', 'Castellanza'),
    ('Università degli Studi di Catania', 'Catania'),
    ('Università degli Studi di Catanzaro', 'Catanzaro'),
    ('Università degli Studi di Chieti e Pescara', 'Chieti'),
    ('Università degli Studi della Calabria', 'Cosenza'),
    ('Università Kore di Enna', 'Enna'),
    ('Università degli Studi di Ferrara', 'Ferrara'),
    ('Università degli Studi di Firenze', 'Firenze'),
    ('Università degli Studi di Foggia', 'Foggia'),
    ('Università degli Studi di Genova', 'Genova'),
    ('Università degli Studi dell\'Aquila', 'L\'Aquila'),
    ('Università degli Studi del Salento', 'Lecce'),
    ('Scuola IMT di Lucca', 'Lucca'),
    ('Università degli Studi di Macerata', 'Macerata'),
    ('Università degli Studi di Messina', 'Messina'),
    ('Università degli Studi di Milano', 'Milano'),
    ('Università degli Studi Milano Bicocca', 'Milano'),
    ('Università Cattolica del Sacro Cuore Milano', 'Milano'),
    ('Politecnico di Milano', 'Milano'),
    ('Università Bocconi di Milano', 'Milano'),
    ('Humanitas University Milano', 'Milano'),
    ('Università IULM Milano', 'Milano'),
    ('San Raffaele Milano', 'Milano'),
    ('Università degli Studi di Modena e Reggio Emilia', 'Modena'),
    ('Università degli Studi di Napoli Federico II', 'Napoli'),
    ('Università degli Studi della Campania Vanvitelli', 'Napoli'),
    ('Università degli Studi L\'Orientale di Napoli', 'Napoli'),
    ('Università degli Studi Suor Orsola Benincasa Napoli', 'Napoli'),
    ('Università Telematica Leonardo Da Vinci', 'Online'),
    ('Università Telematica E-Campus', 'Online'),
    ('Università Telematica Giustino Fortunato', 'Online'),
    ('Università Telematica Guglielmo Marconi', 'Online'),
    ('Università Telematica San Raffaele Roma', 'Online'),
    ('Università Telematica UniNettuno', 'Online'),
    ('Università Telematica IUL', 'Online'),
    ('Università Telematica Pegaso', 'Online'),
    ('Università Telematica Unitelma Sapienza', 'Online'),
    ('Università Telematica Unicusano', 'Online'),
    ('Università Telematica Universitas Mercatorum', 'Online'),
    ('Università degli Studi di Padova', 'Padova'),
    ('Università degli Studi di Palermo', 'Palermo'),
    ('Università degli Studi di Parma', 'Parma'),
    ('Università degli Studi di Pavia', 'Pavia'),
    ('IUSS di Pavia', 'Pavia'),
    ('Università degli Studi di Perugia', 'Perugia'),
    ('Università degli Studi Stranieri Perugia', 'Perugia'),
    ('Università degli Studi di Pisa', 'Pisa'),
    ('Scuola Superiore Normale di Pisa', 'Pisa'),
    ('Scuola Superiore Sant\'Anna di Pisa', 'Pisa'),
    ('Università degli Studi della Basilicata', 'Potenza'),
    ('Università Mediterranea di Reggio Calabria', 'Reggio Calabria'),
    ('Università degli Stranieri di Reggio Calabria', 'Reggio Calabria'),
    ('Università Europea di Roma', 'Roma'),
    ('Università del Foro Italico di Roma', 'Roma'),
    ('Università degli Studi La Sapienza di Roma', 'Roma'),
    ('Università degli Studi di Tor Vergata di Roma', 'Roma'),
    ('Università degli Studi Roma Tre', 'Roma'),
    ('Università Campus Bio-Medico di Roma', 'Roma'),
    ('Università degli Studi LUISS Guido Carli di Roma', 'Roma'),
    ('Università degli Studi Internazionali di Roma UNINT', 'Roma'),
    ('Libera Università Maria SS Assunta LUMSA Roma', 'Roma'),
    ('UniCamillus', 'Roma'),
    ('Link Campus', 'Roma'),
    ('Università degli Studi di Salerno', 'Salerno'),
    ('Università degli Studi di Sassari', 'Sassari'),
    ('Università degli Studi di Siena', 'Siena'),
    ('Università degli Studi degli Stranieri di Siena', 'Siena'),
    ('Università degli Studi di Teramo', 'Teramo'),
    ('Università degli Studi di Torino', 'Torino'),
    ('Politecnico di Torino', 'Torino'),
    ('Università degli Studi di Trento', 'Trento'),
    ('Università degli Studi di Trieste', 'Trieste'),
    ('Università degli Studi Sissa Trieste', 'Trieste'),
    ('Università degli Studi di Udine', 'Udine'),
    ('Università degli Studi di Urbino Carlo Bo', 'Urbino'),
    ('Università degli Studi dell\'Insubria', 'Varese'),
    ('Università degli Studi Cà Foscari di Venezia', 'Venezia'),
    ('Università degli Studi del Piemonte Orientale', 'Vercelli'),
    ('Università degli Studi di Verona', 'Verona'),
    ('Università degli Studi della Tuscia', 'Viterbo');

    INSERT INTO facolta (nome) VALUES
    ('Antropologia culturale ed etnologia (LM-1)'),
    ('Archeologia (LM-2)'),
    ('Architettura del paesaggio (LM-3)'),
    ('Architettura e ingegneria edile-architettura (LM-4)'),
    ('Architettura e ingegneria edile-architettura (quinquennale) (LM-4 c.u.)'),
    ('Archivistica e biblioteconomia (LM-5)'),
    ('Beni culturali (L-1)'),
    ('Biologia (LM-6)'),
    ('Biotecnologie (L-2)'),
    ('Biotecnologie agrarie (LM-7)'),
    ('Biotecnologie industriali (LM-8)'),
    ('Biotecnologie mediche, veterinarie e farmaceutiche (LM-9)'),
    ('Classe di abilitazione A033 - Tecnologia (Abil.)'),
    ('Classe di abilitazione A059 - Matematica e scienze nella scuola secondaria di I grado (Abil.)'),
    ('Conservazione dei beni architettonici e ambientali (LM-10)'),
    ('Conservazione e restauro dei beni culturali (L-43 / LM-11)'),
    ('Data Science (LM-91)'),
    ('Design (L-4)'),
    ('Diagnostica per la conservazione dei beni culturali (LM-11)'),
    ('Discipline delle arti figurative, della musica, dello spettacolo e della moda (L-3)'),
    ('Disegno industriale (L-4)'),
    ('Farmacia e farmacia industriale (LM-13)'),
    ('Filologia moderna (LM-14)'),
    ('Filologia moderna (abilitazione A043) (LM-14)'),
    ('Filologia, letterature e storia dell\'antichità (LM-15)'),
    ('Filosofia (L-5)'),
    ('Finanza (LM-16)'),
    ('Fisica (L-30)'),
    ('Geografia (L-6)'),
    ('Informatica (L-31)'),
    ('Informazione e sistemi editoriali (LM-19)'),
    ('Ingegneria aerospaziale e astronautica (LM-20)'),
    ('Ingegneria biomedica (L-8 / LM-21)'),
    ('Ingegneria chimica (L-9 / LM-22)'),
    ('Ingegneria civile (L-7 / LM-23)'),
    ('Ingegneria civile e ambientale (L-7)'),
    ('Ingegneria dei materiali (L-9 / LM-53)'),
    ('Ingegneria dei sistemi edilizi (LM-24)'),
    ('Ingegneria dell\'automazione (L-8 / LM-25)'),
    ('Ingegneria dell\'informazione (L-8)'),
    ('Ingegneria della sicurezza (LM-26)'),
    ('Ingegneria delle telecomunicazioni (LM-27)'),
    ('Ingegneria elettrica (L-9 / LM-28)'),
    ('Ingegneria elettronica (L-8 / LM-29)'),
    ('Ingegneria energetica e nucleare (LM-30)'),
    ('Ingegneria gestionale (L-9 / LM-31)'),
    ('Ingegneria industriale (L-9)'),
    ('Ingegneria informatica (L-8 / LM-32)'),
    ('Ingegneria meccanica (L-9 / LM-33)'),
    ('Ingegneria navale (L-9 / LM-34)'),
    ('Ingegneria per l\'ambiente e il territorio (LM-35)'),
    ('Lettere (L-10)'),
    ('Lingue e culture moderne (L-11)'),
    ('Lingue e letterature dell\'Africa e dell\'Asia (L-11 / LM-36)'),
    ('Lingue e letterature moderne europee e americane (LM-37)'),
    ('Lingue e letterature moderne europee e americane (abilitazione A045) (LM-37)'),
    ('Lingue moderne per la comunicazione e la cooperazione internazionale (LM-38)'),
    ('Linguistica (LM-39)'),
    ('Magistrali in giurisprudenza (LMG-01)'),
    ('Matematica (L-35)'),
    ('Mediazione linguistica (L-12)'),
    ('Medicina e chirurgia (LM-41)'),
    ('Medicina veterinaria (LM-42)'),
    ('Metodologie informatiche per le discipline umanistiche (LM-43)'),
    ('Modellistica matematico-fisica per l\'ingegneria (LM-44)'),
    ('Musicologia e beni musicali (LM-45)'),
    ('Musicologia e beni musicali (abilitazione A032) (LM-45)'),
    ('Odontoiatria e protesi dentaria (LM-46)'),
    ('Organizzazione e gestione dei servizi per lo sport e le attività motorie (LM-47)'),
    ('Pianificazione territoriale urbanistica e ambientale (L-21 / LM-48)'),
    ('Professioni sanitarie della prevenzione (L/SNT4)'),
    ('Professioni sanitarie della riabilitazione (L/SNT2)'),
    ('Professioni sanitarie tecniche (L/SNT3)'),
    ('Professioni sanitarie, infermieristiche e professione sanitaria ostetrica (L/SNT1)'),
    ('Professioni tecniche agrarie, alimentari e forestali (L-P02)'),
    ('Professioni tecniche industriali e dell\'informazione (L-P03)'),
    ('Professioni tecniche per l\'edilizia e il territorio (L-P01)'),
    ('Progettazione e gestione dei sistemi turistici (LM-49)'),
    ('Programmazione e gestione dei servizi educativi (LM-50)'),
    ('Psicologia (L-24 / LM-51)'),
    ('Relazioni internazionali (LM-52)'),
    ('Scienza e ingegneria dei materiali (LM-53)'),
    ('Scienze della formazione primaria (LM-85 bis)'),
    ('Scienze biologiche (L-13)'),
    ('Scienze chimiche (L-27 / LM-54)'),
    ('Scienze cognitive (LM-55)'),
    ('Scienze criminologiche applicate all\'investigazione e alla sicurezza (L-14)'),
    ('Scienze criminologiche e della sicurezza (L-14 / LM-62)'),
    ('Scienze dei beni culturali (L-1)'),
    ('Scienze dei Materiali (L-30 / LM-53)'),
    ('Scienze dei servizi giuridici (L-14)'),
    ('Scienze del servizio sociale (L-39)'),
    ('Scienze del turismo (L-15)'),
    ('Scienze dell\'amministrazione (L-16)'),
    ('Scienze dell\'amministrazione e dell\'organizzazione (L-16)'),
    ('Scienze dell\'architettura (L-17)'),
    ('Scienze dell\'architettura e dell\'ingegneria edile (L-23)'),
    ('Scienze dell\'architettura e dell\'ingegneria edile - Ciclo Unico (LM-4 c.u.)'),
    ('Scienze dell\'economia (LM-56)'),
    ('Scienze dell\'economia e della gestione aziendale (L-18)'),
    ('Scienze dell\'educazione degli adulti e della formazione continua (LM-57)'),
    ('Scienze dell\'educazione e della formazione (L-19)'),
    ('Scienze dell\'universo (LM-58)'),
    ('Scienze della comunicazione (L-20)'),
    ('Scienze della comunicazione pubblica, d\'impresa e pubblicità (LM-59)'),
    ('Scienze della difesa e della sicurezza (L-DS / LM-DS)'),
    ('Scienze della Formazione Primaria - Quadriennale (V.O.)'),
    ('Scienze della mediazione linguistica (L-12)'),
    ('Scienze della natura (L-32 / LM-60)'),
    ('Scienze della nutrizione umana (LM-61)'),
    ('Scienze della pianificazione territoriale, urbanistica, paesaggistica e ambientale (L-21)'),
    ('Scienze della politica (L-36 / LM-62)'),
    ('Scienze della Terra (L-34)'),
    ('Scienze delle attivita motorie e sportive (L-22)'),
    ('Scienze delle professioni sanitarie della prevenzione (LM/SNT4)'),
    ('Scienze delle professioni sanitarie tecniche (LM/SNT3)'),
    ('Scienze delle pubbliche amministrazioni (LM-63)'),
    ('Scienze delle religioni (LM-64)'),
    ('Scienze dello spettacolo e produzione multimediale (LM-65)'),
    ('Scienze e tecniche dell\'edilizia (L-23)'),
    ('Scienze e tecniche delle attività motorie preventive e adattate (LM-67)'),
    ('Scienze e tecniche delle attività motorie preventive e adattate (abilitazione A030) (LM-67)'),
    ('Scienze e tecniche dello sport (LM-68)'),
    ('Scienze e tecniche dello sport (abilitazione A030) (LM-68)'),
    ('Scienze e tecniche psicologiche (L-24)'),
    ('Scienze e tecnologie agrarie (L-25 / LM-69)'),
    ('Scienze e tecnologie agrarie e forestali (L-25)'),
    ('Scienze e tecnologie agrarie, agroalimentari e forestali (L-25)'),
    ('Scienze e tecnologie alimentari (L-26 / LM-70)'),
    ('Scienze e tecnologie chimiche (L-27)'),
    ('Scienze e tecnologie della chimica industriale (L-27 / LM-71)'),
    ('Scienze e tecnologie della navigazione (L-28 / LM-72)'),
    ('Scienze e tecnologie delle arti figurative, della musica, dello spettacolo e della moda (L-3)'),
    ('Scienze e tecnologie farmaceutiche (L-29)'),
    ('Scienze e tecnologie fisiche (L-30)'),
    ('Scienze e tecnologie forestali ed ambientali (L-25 / LM-73)'),
    ('Scienze e tecnologie geologiche (L-34 / LM-74)'),
    ('Scienze e tecnologie informatiche (L-31)'),
    ('Scienze e tecnologie per l\'ambiente e il territorio (L-32 / LM-75)'),
    ('Scienze e tecnologie per l\'ambiente e la natura (L-32)'),
    ('Scienze e tecnologie zootecniche e delle produzioni animali (L-38 / LM-86)'),
    ('Scienze economiche (L-33)'),
    ('Scienze economiche e sociali della gastronomia (L-Gastr)'),
    ('Scienze economiche per l\'ambiente e la cultura (LM-76)'),
    ('Scienze economico-aziendali (LM-77)'),
    ('Scienze filosofiche (LM-78)'),
    ('Scienze geofisiche (LM-79)'),
    ('Scienze geografiche (LM-80)'),
    ('Scienze geologiche (L-34)'),
    ('Scienze Giuridiche (L-14)'),
    ('Scienze giuridiche - Quinquennale (LMG-01)'),
    ('Scienze infermieristiche e ostetriche (LM/SNT1)'),
    ('Scienze matematiche (L-35)'),
    ('Scienze pedagogiche (LM-85)'),
    ('Scienze per la conservazione dei beni culturali (L-43)'),
    ('Scienze per la cooperazione allo sviluppo (L-37 / LM-81)'),
    ('Scienze politiche e delle relazioni internazionali (L-36)'),
    ('Scienze riabilitative delle professioni sanitarie (LM/SNT2)'),
    ('Scienze sociali per la cooperazione, lo sviluppo e la pace (L-37)'),
    ('Scienze sociologiche (L-40)'),
    ('Scienze statistiche (L-41 / LM-82)'),
    ('Scienze statistiche attuariali e finanziarie (LM-83)'),
    ('Scienze storiche (L-42 / LM-84)'),
    ('Scienze zootecniche e tecnologie animali (L-38)'),
    ('Scienze, culture e politiche della gastronomia (L-Gastr)'),
    ('Servizio sociale (L-39)'),
    ('Servizio sociale e politiche sociali (LM-87)'),
    ('Sicurezza informatica (LM-66)'),
    ('Sociologia (L-40 / LM-88)'),
    ('Sociologia e ricerca sociale (LM-88)'),
    ('Specialistiche in antropologia culturale ed etnologia (1/S)'),
    ('Specialistiche in archeologia (2/S)'),
    ('Specialistiche in architettura del paesaggio (3/S)'),
    ('Specialistiche in architettura e ingegneria edile (4/S)'),
    ('Specialistiche in archivistica e biblioteconomia (5/S)'),
    ('Specialistiche in biologia (6/S)'),
    ('Specialistiche in biotecnologie agrarie (7/S)'),
    ('Specialistiche in biotecnologie industriali (8/S)'),
    ('Specialistiche in biotecnologie mediche, veterinarie e farmaceutiche (9/S)'),
    ('Specialistiche in conservazione dei beni architettonici e ambientali (10/S)'),
    ('Specialistiche in conservazione dei beni scientifici e della civilta industriale (11/S)'),
    ('Specialistiche in conservazione e restauro del patrimonio storico-artistico (12/S)'),
    ('Specialistiche in editoria, comunicazione multimediale e giornalismo (13/S)'),
    ('Specialistiche in farmacia e farmacia industriale (14/S)'),
    ('Specialistiche in filologia e letterature dell\'antichita (15/S)'),
    ('Specialistiche in filologia moderna (16/S)'),
    ('Specialistiche in filosofia e storia della scienza (17/S)'),
    ('Specialistiche in filosofia teoretica, morale, politica ed estetica (18/S)'),
    ('Specialistiche in finanza (19/S)'),
    ('Specialistiche in fisica (20/S)'),
    ('Specialistiche in geografia (21/S)'),
    ('Specialistiche in giurisprudenza (22/S)'),
    ('Specialistiche in informatica (23/S)'),
    ('Specialistiche in informatica per le discipline umanistiche (24/S)'),
    ('Specialistiche in ingegneria aerospaziale e astronautica (25/S)'),
    ('Specialistiche in ingegneria biomedica (26/S)'),
    ('Specialistiche in ingegneria chimica (27/S)'),
    ('Specialistiche in ingegneria civile (28/S)'),
    ('Specialistiche in ingegneria dell\'automazione (29/S)'),
    ('Specialistiche in ingegneria delle telecomunicazioni (30/S)'),
    ('Specialistiche in ingegneria elettrica (31/S)'),
    ('Specialistiche in ingegneria elettronica (32/S)'),
    ('Specialistiche in ingegneria energetica e nucleare (33/S)'),
    ('Specialistiche in ingegneria gestionale (34/S)'),
    ('Specialistiche in ingegneria informatica (35/S)'),
    ('Specialistiche in ingegneria meccanica (36/S)'),
    ('Specialistiche in ingegneria navale (37/S)'),
    ('Specialistiche in ingegneria per l\'ambiente e il territorio (38/S)'),
    ('Specialistiche in interpretariato di conferenza (39/S)'),
    ('Specialistiche in lingua e cultura italiana (40/S)'),
    ('Specialistiche in lingue e letterature afroasiatiche (41/S)'),
    ('Specialistiche in lingue e letterature moderne euroamericane (42/S)'),
    ('Specialistiche in lingue straniere per la comunicazione internazionale (43/S)'),
    ('Specialistiche in linguistica (44/S)'),
    ('Specialistiche in matematica (45/S)'),
    ('Specialistiche in medicina e chirurgia (46/S)'),
    ('Specialistiche in medicina veterinaria (47/S)'),
    ('Specialistiche in metodi per l\'analisi valutativa dei sistemi complessi (48/S)'),
    ('Specialistiche in metodi per la ricerca empirica nelle scienze sociali (49/S)'),
    ('Specialistiche in modellistica matematico-fisica per l\'ingegneria (50/S)'),
    ('Specialistiche in musicologia e beni musicali (51/S)'),
    ('Specialistiche in odontoiatria e protesi dentaria (52/S)'),
    ('Specialistiche in organizzazione e gestione dei servizi per lo sport e le attivita motorie (53/S)'),
    ('Specialistiche in pianificazione territoriale urbanistica e ambientale (54/S)'),
    ('Specialistiche in progettazione e gestione dei sistemi turistici (55/S)'),
    ('Specialistiche in programmazione e gestione dei servizi educativi e formativi (56/S)'),
    ('Specialistiche in programmazione e gestione delle politiche e dei servizi sociali (57/S)'),
    ('Specialistiche in psicologia (58/S)'),
    ('Specialistiche in pubblicita e comunicazione d\'impresa (59/S)'),
    ('Specialistiche in relazioni internazionali (60/S)'),
    ('Specialistiche in scienza e ingegneria dei materiali (61/S)'),
    ('Specialistiche in scienze chimiche (62/S)'),
    ('Specialistiche in scienze cognitive (63/S)'),
    ('Specialistiche in scienze dell\'economia (64/S)'),
    ('Specialistiche in scienze dell\'educazione degli adulti e della formazione continua (65/S)'),
    ('Specialistiche in scienze dell\'universo (66/S)'),
    ('Specialistiche in scienze della comunicazione sociale e istituzionale (67/S)'),
    ('Specialistiche in scienze della natura (68/S)'),
    ('Specialistiche in scienze della nutrizione umana (69/S)'),
    ('Specialistiche in scienze della politica (70/S)'),
    ('Specialistiche in scienze delle pubbliche amministrazioni (71/S)'),
    ('Specialistiche in scienze delle religioni (72/S)'),
    ('Specialistiche in scienze dello spettacolo e della produzione multimediale (73/S)'),
    ('Specialistiche in scienze e gestione delle risorse rurali e forestali (74/S)'),
    ('Specialistiche in scienze e tecnica dello sport (75/S)'),
    ('Specialistiche in scienze e tecniche delle attivita motorie preventive e adattative (76/S)'),
    ('Specialistiche in scienze e tecnologie agrarie (77/S)'),
    ('Specialistiche in scienze e tecnologie agroalimentari (78/S)'),
    ('Specialistiche in scienze e tecnologie agrozootecniche (79/S)'),
    ('Specialistiche in scienze e tecnologie dei sistemi di navigazione (80/S)'),
    ('Specialistiche in scienze e tecnologie della chimica industriale (81/S)'),
    ('Specialistiche in scienze e tecnologie per l\'ambiente e il territorio (82/S)'),
    ('Specialistiche in scienze economiche per l\'ambiente e la cultura (83/S)'),
    ('Specialistiche in scienze economico-aziendali (84/S)'),
    ('Specialistiche in scienze geofisiche (85/S)'),
    ('Specialistiche in scienze geologiche (86/S)'),
    ('Specialistiche in scienze pedagogiche (87/S)'),
    ('Specialistiche in scienze per la cooperazione allo sviluppo (88/S)'),
    ('Specialistiche in sociologia (89/S)'),
    ('Specialistiche in statistica demografica e sociale (90/S)'),
    ('Specialistiche in statistica economica, finanziaria ed attuariale (91/S)'),
    ('Specialistiche in statistica per la ricerca sperimentale (92/S)'),
    ('Specialistiche in storia antica (93/S)'),
    ('Specialistiche in storia contemporanea (94/S)'),
    ('Specialistiche in storia dell\'arte (95/S)'),
    ('Specialistiche in storia della filosofia (96/S)'),
    ('Specialistiche in storia medievale (97/S)'),
    ('Specialistiche in storia moderna (98/S)'),
    ('Specialistiche in studi europei (99/S)'),
    ('Specialistiche in tecniche e metodi per la societa dell\'informazione (100/S)'),
    ('Specialistiche in teoria della comunicazione (101/S)'),
    ('Specialistiche in teoria e tecniche della normazione e dell\'informazione giuridica (102/S)'),
    ('Specialistiche in teorie e metodi del disegno industriale (103/S)'),
    ('Specialistiche in traduzione letteraria e in traduzione tecnico-scientifica (104/S)'),
    ('Specialistiche nelle scienze della difesa e della sicurezza (105/S)'),
    ('Specialistiche nelle scienze delle professioni sanitarie della prevenzione (SNT/04/S)'),
    ('Specialistiche nelle scienze delle professioni sanitarie della riabilitazione (SNT/02/S)'),
    ('Specialistiche nelle scienze delle professioni sanitarie tecniche (SNT/03/S)'),
    ('Specialistiche nelle scienze infermieristiche e ostetriche (SNT/01/S)'),
    ('Statistica (L-41)'),
    ('Storia (L-42)'),
    ('Storia dell\'arte (LM-89)'),
    ('Studi europei (LM-90)'),
    ('Tecniche e metodi per la societa dell\'informazione (LM-91)'),
    ('Tecnologie per la conservazione e il restauro dei beni culturali (L-43)'),
    ('Teorie della comunicazione (LM-92)'),
    ('Teorie e metodologie dell\'e-learning e della media education (LM-93)'),
    ('Traduzione specialistica e interpretariato (LM-94)'),
    ('Urbanistica e scienze della pianificazione territoriale e ambientale (L-21 / LM-48)');
    ";

    $inserisciDati .= "
    INSERT INTO utenti (username, password, email, ruolo, id_universita, id_facolta, data_iscrizione) VALUES
    ('admin', '" . mysqli_real_escape_string($conn, $passHashAdmin) . "', 'admin@unibank.it', 1, 1, 1, NOW()),
    ('utente', '" . mysqli_real_escape_string($conn, $passHashUtente) . "', 'utente@email.it', 0, 1, 1, NOW());
    ";

    $inserisciDati .= "
    INSERT INTO materia (nome) VALUES
    ('Database e Normalizzazione'),
    ('NoSQL e Database Non Relazionali'),
    ('Transazioni e Controllo della Concorrenza');

    INSERT INTO materiaperfacolta (id_materia, id_facolta) VALUES
    (1, 48),
    (2, 48),
    (3, 48);

    INSERT INTO dispense (titolo, descrizione, prezzo, percorso_file, data_caricamento, id_utente, id_materiaperfacolta) VALUES
    ('Normalizzazione dei Database', 'Guida completa alle forme normali e normalizzazione relazionale', 15, 'dispense/normalizzazione.pdf', NOW(), 2, 1),
    ('NoSQL - Dispensa Completa', 'Introduzione ai database NoSQL, MongoDB e tecnologie alternative', 9, 'dispense/nosqldispensa.pdf', NOW(), 2, 2),
    ('Transazioni nei Database', 'ACID, controllo della concorrenza e gestione delle transazioni', 11, 'dispense/transazioni.pdf', NOW(), 2, 3);
    ";

    $inserisciQueries = array_filter(array_map('trim', explode(';', $inserisciDati)));
    foreach($inserisciQueries as $query){
        if(!mysqli_query($conn, $query)){
            die('Errore durante l\'inserimento dei dati: ' . mysqli_error($conn));
        }
    }
}

$configContent = "<?php\n";
$configContent .= "define('DB_HOST', " . var_export($dbHost, true) . ");\n";
$configContent .= "define('DB_USER', " . var_export($dbUser, true) . ");\n";
$configContent .= "define('DB_PASS', " . var_export($dbPassword, true) . ");\n";
$configContent .= "define('DB_NAME', " . var_export($databaseName, true) . ");\n\n";
$configContent .= "function db_connect() {\n";
$configContent .= "    \$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);\n";
$configContent .= "    if (!\$conn) {\n";
$configContent .= "        die('Connessione al database fallita: ' . mysqli_connect_error());\n";
$configContent .= "    }\n";
$configContent .= "    mysqli_set_charset(\$conn, 'utf8mb4');\n";
$configContent .= "    return \$conn;\n";
$configContent .= "}\n";

if(file_put_contents(__DIR__ . '/../config.php', $configContent) === false){
    die('Impossibile scrivere il file di configurazione. Controlla i permessi.');
}

mysqli_close($conn);
#unlink(__FILE__);
$_SESSION['isInstalled'] = true;
header('Location: ../src/index.php');
exit;