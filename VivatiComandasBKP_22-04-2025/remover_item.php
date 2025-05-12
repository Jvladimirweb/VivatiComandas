<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = intval($_POST['item_id']);
    $comanda_id = intval($_POST['comanda_id']);

    if ($item_id > 0 && $comanda_id > 0) {
        $stmt = $pdo->prepare("DELETE FROM itens WHERE id = ? AND comanda_id = ?");
        $stmt->execute([$item_id, $comanda_id]);
    }
}

// Redireciona de volta para a comanda
header("Location: comanda.php?id=" . $comanda_id);
exit;
?>
