<?php
include 'db.php';

$comanda_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($comanda_id > 0) {
    $stmt = $pdo->prepare("UPDATE comandas SET status = 'aberta' WHERE id = ?");
    $stmt->execute([$comanda_id]);
}

header("Location: abrir_comanda.php");
?>