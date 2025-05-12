<?php
include 'db.php';
include "autenticar.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comanda_id = intval($_POST['comanda_id']);
    $produto = trim($_POST['produto']);
    $descricao = trim($_POST['descricao']);
    $quantidade = intval($_POST['quantidade']);
    $preco = floatval($_POST['preco']);
    $message = ""; // Variável para armazenar mensagens
    $messageClass = ""; // Classe para o estilo da mensagem (success ou error)

    // Validar os dados recebidos
    if ($comanda_id > 0 && !empty($produto) && !empty($descricao) && !empty($quantidade) && $preco > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO itens (comanda_id, produto, descricao, quantidade, preco) VALUES (?, ?, ?, ?, ?)");
            $success = $stmt->execute([$comanda_id, $produto, $descricao, $quantidade, $preco]);

            if ($success) {
                $message = "Item adicionado à comanda com sucesso!";
                $messageClass = "success";
            } else {
                $message = "Erro ao adicionar o item à comanda. Tente novamente.";
                $messageClass = "error";
            }
        } catch (PDOException $e) {
            $message = "Erro no banco de dados: " . $e->getMessage();
            $messageClass = "error";
        }
    } else {
        $message = "Preencha todos os campos corretamente antes de enviar!";
        $messageClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <h1>Adicionar Produto</h1>

        <!-- Exibir mensagens de sucesso ou erro -->
        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($messageClass) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <?php if ($papel_usuario === 'admin'): ?>
        <a href="index.php" class="butt">
        <button>MENU</button>
        </a>
        <?php endif; ?>
        <?php if ($papel_usuario === 'user'): ?>
            <a href="index_user.php" class="butt">
            <button>MENU</button>
            </a>
        <?php endif; ?>
        <a href="abrir_comanda.php">
        <button style="background-color: #007BFF; color: white;">adicionar mais item</button>
        </a>
    </div>
</body>
</html>

