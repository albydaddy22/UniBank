<?php
session_start();
if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != true){
    header("Location: ../authentication/frontend/signup.php");
    exit;
}

require_once __DIR__ . '/../../config.php';

try{
    $idDispensa = (int)$_POST['id_dispensa'];
    $userId = $_SESSION['user_id'];
    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "
        SELECT id_dispensa, id_utente, prezzo
        FROM dispense
        WHERE id_dispensa = ? AND approvata = 1
    ");

    if(!$stmt){
        throw new Exception("Errore nella preparazione della query: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $idDispensa);
    mysqli_stmt_execute($stmt);
    $ris = mysqli_stmt_get_result($stmt);
    $dispensa = mysqli_fetch_assoc($ris);
    mysqli_stmt_close($stmt);

    if(!$dispensa){
        throw new Exception("Dispensa non trovata");
    }

    if($dispensa['id_utente'] == $userId){
        throw new Exception("Non puoi comprare una dispensa caricata da te");
    }


    $stmt = mysqli_prepare($conn, "
        SELECT id_acquisto
        FROM acquisti
        WHERE id_utente = ? AND id_dispensa = ?
    ");
    
    mysqli_stmt_bind_param($stmt, "ii", $userId, $idDispensa);
    mysqli_stmt_execute($stmt);
    $acquistoPrecedente = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if(mysqli_num_rows($acquistoPrecedente) > 0){
        throw new Exception("Hai già comprato questa dispensa in precedenza");
    }

    $stmt = mysqli_prepare($conn, "
        SELECT saldo
        FROM utenti
        WHERE id_utente = ?
    ");
    
    mysqli_stmt_bind_param($stmt, "i", $userId); 
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $utente = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    $saldoUtente = (int)$utente['saldo'];
    $prezzo = (int)$dispensa['prezzo'];

    if($saldoUtente - $prezzo < 0){
        throw new Exception("Saldo insufficiente. Saldo: " . $saldoUtente . ", Prezzo: " . $prezzo);
    }

    mysqli_begin_transaction($conn, );

    $stmt = mysqli_prepare($conn, "
        UPDATE utenti
        SET saldo = saldo - ?
        WHERE id_utente = ?
    ");

    
    mysqli_stmt_bind_param($stmt, "di", $prezzo, $userId);
    $updateResult = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if(!$updateResult){
        throw new Exception("errore " . mysqli_error($conn));
    }

    if(mysqli_affected_rows($conn) == 0){
        throw new Exception("Aggiornamento del saldo non riuscito");
    }

    $stmt = mysqli_prepare($conn, "
        INSERT INTO acquisti(id_utente, id_dispensa, data_acquisto) 
        VALUES (?, ?, NOW())
    ");

    
    mysqli_stmt_bind_param($stmt, "ii", $userId, $idDispensa);
    $insertResult = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    mysqli_commit($conn);
    $_SESSION['comprato'] = true;
    mysqli_close($conn);

    echo "Dispensa comprata con successo!";
    echo "<br>";
    echo "<a href='../index.php'>Torna alla homepage</a>";
    exit();

}catch(Exception $e){
    if($conn){
        mysqli_rollback($conn);
        mysqli_close($conn);
        $_SESSION['comprato'] = true;
    }
    echo "rrrore durante l'acquisto: " . ($e->getMessage());
    echo "<br>";
    echo "<a href='../index.php'>torna alla homepage</a>";
    exit();
}
?>