<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mesa = intval($_POST['mesa']);
    $comanda_id = intval($_POST['comanda_id']);
    
    if ($comanda_id > 0 && $mesa > 0) {
        $stmt = $pdo->prepare("UPDATE `bar`.`comandas` SET `mesa` = ? WHERE (`id` = ?)");
        $stmt->execute([$mesa, $comanda_id]);
    }
}
header("Location: abrir_comanda.php");
exit;
?>