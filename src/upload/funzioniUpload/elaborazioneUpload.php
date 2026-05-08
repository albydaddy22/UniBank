<?php
session_start();
require_once __DIR__ . '/../../../config.php';

if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] !== true) {
    header("Location: ../../authentication/frontend/login.php");
    exit();
}

$conn = db_connect();

$titolo = $_POST['titolo'];
$descrizione = $_POST['descrizione'];
$prezzo = $_POST['prezzo'];
$percorso = $_POST['file'];


$query = 'INSERT INTO dispense (titolo, descrizione, prezzo, percorso_file, data_caricamento, id_utente, id_materiaperfacolta)
VALUES ()
';


?>
