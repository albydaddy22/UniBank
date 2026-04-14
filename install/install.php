<?php
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

CREATE TABLE IF NOT EXISTS Universita(
    id_universita INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    citta_sede VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Facolta(
    id_facolta INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Materia(
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS MateriaPerFacolta(
    id_MateriaPerFacolta INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_materia INT(11) NOT NULL,
    id_facolta INT(11) NOT NULL,
    CONSTRAINT fk_categoria_materia FOREIGN KEY (id_materia) REFERENCES Materia(id_materia),
    CONSTRAINT fk_categoria_facolta FOREIGN KEY (id_facolta) REFERENCES Facolta(id_facolta),
    UNIQUE (id_materia, id_facolta)
);

CREATE TABLE IF NOT EXISTS Utenti(
    id_utente INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    ruolo BOOLEAN NOT NULL,
    saldo INT NOT NULL DEFAULT 20,
    id_universita INT(11) NOT NULL,
    id_facolta INT(11) NOT NULL,
    CONSTRAINT fk_utente_universita FOREIGN KEY (id_universita) REFERENCES Universita(id_universita),
    CONSTRAINT fk_utente_facolta FOREIGN KEY (id_facolta) REFERENCES Facolta(id_facolta)
);

CREATE TABLE IF NOT EXISTS Dispense(
    id_dispensa INT(11) AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(50) NOT NULL,
    descrizione VARCHAR(255) NOT NULL,
    prezzo INT(4) NOT NULL,
    percorso_file VARCHAR(255) NOT NULL,
    data_caricamento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_utente INT(11) NOT NULL,
    id_MateriaPerFacolta INT(11) NOT NULL,
    CONSTRAINT fk_dispensa_utente FOREIGN KEY (id_utente) REFERENCES Utenti(id_utente),
    CONSTRAINT fk_dispensa_categoria FOREIGN KEY (id_MateriaPerFacolta) REFERENCES MateriaPerFacolta(id_MateriaPerFacolta)
);

CREATE TABLE IF NOT EXISTS Acquisti(
    id_acquisto INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_utente INT(11) NOT NULL,
    id_dispensa INT(11) NOT NULL,
    data_acquisto DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_acquisto_utente FOREIGN KEY (id_utente) REFERENCES Utenti(id_utente),
    CONSTRAINT fk_acquisto_dispensa FOREIGN KEY (id_dispensa) REFERENCES Dispense(id_dispensa),
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

$checkUniversita = mysqli_query($conn, 'SELECT COUNT(*) as tot FROM Universita');
$row = mysqli_fetch_assoc($checkUniversita);
if ((int)$row['tot'] === 0) {
    $inserisciDati = "
    INSERT INTO Universita (nome, citta_sede) VALUES
    ('Politecnico di Bari', 'Bari'),
    ('Università degli Studi di Foggia', 'Foggia'),
    ('Università degli Studi di Napoli Federico II', 'Napoli'),
    ('Politecnico di Milano', 'Milano'),
    ('Università degli Studi di Roma La Sapienza', 'Roma'),
    ('Università degli Studi di Torino', 'Torino'),
    ('Politecnico di Torino', 'Torino'),
    ('Università degli Studi di Bologna', 'Bologna'),
    ('Università degli Studi di Firenze', 'Firenze'),
    ('Università degli Studi di Padova', 'Padova'),
    ('Università degli Studi di Pisa', 'Pisa'),
    ('Università degli Studi di Genova', 'Genova'),
    ('Università degli Studi di Catania', 'Catania'),
    ('Università degli Studi di Palermo', 'Palermo'),
    ('Università degli Studi di Cagliari', 'Cagliari'),
    ('Università degli Studi di Trieste', 'Trieste'),
    ('Università degli Studi di Perugia', 'Perugia'),
    ('Università degli Studi di Salerno', 'Salerno'),
    ('Università degli Studi di Messina', 'Messina');

    INSERT INTO Facolta (nome) VALUES
    ('Ingegneria Informatica'),
    ('Ingegneria Meccanica'),
    ('Ingegneria Elettronica'),
    ('Ingegneria Civile'),
    ('Ingegneria Chimica'),
    ('Ingegneria Biomedica'),
    ('Ingegneria Aerospaziale'),
    ('Ingegneria Gestionale'),
    ('Economia Aziendale'),
    ('Economia e Commercio'),
    ('Giurisprudenza'),
    ('Medicina e Chirurgia'),
    ('Farmacia'),
    ('Scienze Politiche'),
    ('Lettere e Filosofia'),
    ('Psicologia'),
    ('Architettura'),
    ('Matematica'),
    ('Fisica'),
    ('Biologia');
    ";

    $inserisciQueries = array_filter(array_map('trim', explode(';', $inserisciDati)));
    foreach($inserisciQueries as $query){
        if(!mysqli_query($conn, $query)){
            die('Errore durante l\'inserimento dei dati: ' . mysqli_error($conn));
        }
    }

    $passAdmin = password_hash('admin123', PASSWORD_DEFAULT);
    $passUtente = password_hash('utente123', PASSWORD_DEFAULT);

    $adminUsername = 'admin';
    $adminEmail = 'admin@unibank.it';
    $stmtAdmin = mysqli_prepare($conn, 'INSERT IGNORE INTO Utenti (username, password, email, ruolo, id_universita, id_facolta) VALUES (?, ?, ?, 1, 1, 1)');
    mysqli_stmt_bind_param($stmtAdmin, 'sss', $adminUsername, $passAdmin, $adminEmail);
    mysqli_stmt_execute($stmtAdmin);
    mysqli_stmt_close($stmtAdmin);

    $userUsername = 'utente';
    $userEmail = 'utente@email.it';
    $stmtUtente = mysqli_prepare($conn, 'INSERT IGNORE INTO Utenti (username, password, email, ruolo, id_universita, id_facolta) VALUES (?, ?, ?, 0, 1, 1)');
    mysqli_stmt_bind_param($stmtUtente, 'sss', $userUsername, $passUtente, $userEmail);
    mysqli_stmt_execute($stmtUtente);
    mysqli_stmt_close($stmtUtente);
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

header('Location: ../authentication/frontend/login.php');
exit;