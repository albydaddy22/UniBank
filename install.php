<?php
session_start();

$_SESSION['nomedatabase'] = $_POST['nomedatabase'];
$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];
$_SESSION['servername'] = $_POST['indirizzo'];

header("Location: index.html");
?>