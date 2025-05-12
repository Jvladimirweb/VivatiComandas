<?php
// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = ''; // Altere conforme a senha do seu banco
$dbname = 'bar'; // Altere se o nome do banco for diferente

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Erro de conexão: " . $conn->connect_error]));
}

// Ler dados enviados pelo cliente
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['item_id'])) {
    echo json_encode(["success" => false, "message" => "ID do item não fornecido."]);
    exit;
}

$item_id = $data['item_id'];

// Atualizar o status do item no banco de dados
$sql = "UPDATE itens SET atendido = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $item_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Item marcado como atendido com sucesso."]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao atualizar o item."]);
}

$stmt->close();
$conn->close();
?>
