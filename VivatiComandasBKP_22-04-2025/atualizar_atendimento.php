<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['item_id']) && isset($data['status'])) {
    $item_id = intval($data['item_id']);
    $status = intval($data['status']) === 1 ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE itens SET atendido = ? WHERE id = ?");
    $success = $stmt->execute([$status, $item_id]);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos.']);
}
