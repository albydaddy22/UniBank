<?php
require_once __DIR__ . '/../../../config.php';

header('Content-Type: application/json');

if (!isset($_GET['id_facolta']) || !is_numeric($_GET['id_facolta'])) {
    exit;
}

$idFacolta = (int)$_GET['id_facolta'];
$conn = db_connect();

$query = "SELECT m.id_materia, m.nome 
          FROM materia m, materiaperfacolta mpf 
          WHERE m.id_materia = mpf.id_materia 
          AND mpf.id_facolta = ? 
          ORDER BY m.nome ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $idFacolta);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$materie = [];
while($row = mysqli_fetch_assoc($result)){
    $materie[] =[
        'id' => (int)$row['id_materia'],
        'nome' => $row['nome']
    ];
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo json_encode($materie);
