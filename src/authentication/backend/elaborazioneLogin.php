<?php
session_start();
require_once __DIR__ . '/../../../config.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: ../frontend/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$errors = [];

if($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Email non valida.';
}
if($password === ''){
    $errors[] = 'Password è obbligatoria.';
}

if(!empty($errors)){
    echo '<div style="color:red;">';
    echo '<p>Errore durante il login:</p>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
    echo '<p><a href="../frontend/login.php">Torna al login</a></p>';
    exit;
}

$connection = db_connect();

$query = 'SELECT id_utente, username, password, ruolo, email FROM utenti WHERE email = ? LIMIT 1';
$stmt = mysqli_prepare($connection, $query);
if(!$stmt){
    die('Errore nella preparazione della query: ' . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if(!$user || !password_verify($password, $user['password'])){
    echo '<p style="color:red;">Email o password errate.</p>';
    echo '<p><a href="../frontend/login.php">Torna al login</a></p>';
    mysqli_close($connection);
    exit;
}

$_SESSION['user_id'] = $user['id_utente'];
$_SESSION['username'] = $user['username'];
$_SESSION['ruolo'] = $user['ruolo'];
$_SESSION['email'] = $user['email'];
$_SESSION['is_logged'] = true;

mysqli_close($connection);

if((int) $user['ruolo'] === 1){
    header('Location: ../frontend/admin.html');
    exit;
}

header('Location: ../../index.php');
exit;
?>