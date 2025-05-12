<?php
include 'db.php';
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $preco = floatval($_POST['preco']);

    // Validar se os campos estão preenchidos corretamente
    if (!empty($nome) && $preco > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
            $success = $stmt->execute([$nome, $preco]);

            if ($success) {
                $message = "Produto inserido com sucesso!";
                $messageClass = "success";
            } else {
                $message = "Erro ao inserir o produto. Tente novamente.";
                $messageClass = "error";
            }
        } catch (PDOException $e) {
            $message = "Erro no banco de dados: " . $e->getMessage();
            $messageClass = "error";
        }
    } else {
        $message = "Preencha todos os campos corretamente!";
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
        <a href="index.php">
        <button style="background-color: #007BFF; color: white;">Menu</button>
        </a>
        <a href="cadastro_produto.php">
        <button style="background-color: #007BFF; color: white;">Cadastrar novo produto</button>
        </a>
    </div>
</body>
</html>
