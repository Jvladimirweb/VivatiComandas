<?php
include 'db.php';
include "autenticar.php";

$comanda_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($comanda_id > 0) {
    $stmt = $pdo->prepare("UPDATE comandas SET status = 'fechada' WHERE id = ?");
    $stmt->execute([$comanda_id]);

    // Exemplo: Atualizar status e salvar data_fechamento ao fechar a comanda
    $stmt = $pdo->prepare("UPDATE comandas SET status = 'fechada', data_fechamento = NOW() WHERE id = ?");
    $stmt->execute([$comanda_id]);

}

header("Location: comanda.php?id=" . $comanda_id);
?>