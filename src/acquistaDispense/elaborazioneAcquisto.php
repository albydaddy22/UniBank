<?php
session_start();
if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != true){
    header("Location: ../authentication/frontend/signup.php");
    exit;
}

require_once __DIR__ . '/../../config.php';


$idDispensa = $_POST['id_dispensa'];
$conn = db_connect();

$stmt = mysqli_prepare($conn, "
    SELECT *
    FROM dispense
    WHERE id_dispensa = ?
");
mysqli_stmt_bind_param($stmt, "i", $idDispensa);
mysqli_stmt_execute($stmt);
$ris = mysqli_stmt_get_result($stmt);
$dispensa = mysqli_fetch_assoc($ris);

$stmt = mysqli_prepare($conn, "
    SELECT saldo
    FROM utenti
    WHERE id_utente = ?
");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']); 
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$utente = mysqli_fetch_assoc($result);

$saldoUtente = $utente['saldo'];

if($saldoUtente - $dispensa['prezzo'] < 0){
    echo "non hai abbastanza uniToken per comprare questa dispensa";
    echo "<br>";
    echo "<a href = ../index.php>torna alla homepage</a>";
}else{
    $stmt = mysqli_prepare($conn, "
        UPDATE utenti
        SET saldo = saldo - ?
        WHERE id_utente = ?
    ");
    mysqli_stmt_bind_param($stmt, "ii", $dispensa['prezzo'], $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);

    $query = "INSERT INTO acquisti(id_utente, id_dispensa, data_acquisto) 
          VALUES ({$_SESSION['user_id']}, $idDispensa, NOW())";

    $ris = mysqli_query($conn, $query);

    $percorsoPdf = '../../' . $dispensa['percorso_file'];
    header("Location: " . $percorsoPdf);
    exit();
}
?>