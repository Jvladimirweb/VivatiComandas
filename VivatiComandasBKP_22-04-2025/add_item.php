<?php
session_start();
include 'db.php';
include "autenticar.php";

$message = "";
$messageClass = "";

// Obter comanda_id via POST ou GET
$comanda_id = isset($_POST['comanda_id']) ? intval($_POST['comanda_id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = trim($_POST['produto']);
    $descricao = trim($_POST['descricao']);
    $quantidade = intval($_POST['quantidade']);
    $preco = floatval($_POST['preco']);

    if ($comanda_id > 0 && !empty($produto) && !empty($descricao) && $quantidade > 0 && $preco > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO itens (comanda_id, produto, descricao, quantidade, preco) VALUES (?, ?, ?, ?, ?)");
            $success = $stmt->execute([$comanda_id, $produto, $descricao, $quantidade, $preco]);

            if ($success) {
                $message = "Item adicionado à comanda com sucesso!";
                $messageClass = "success";
            } else {
                $message = "Erro ao adicionar o item à comanda.";
                $messageClass = "error";
            }
        } catch (PDOException $e) {
            $message = "Erro no banco de dados: " . $e->getMessage();
            $messageClass = "error";
        }
    } else {
        $message = "Preencha todos os campos corretamente.";
        $messageClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 420px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
        }

        h1 {
            font-size: 1.6em;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        form {
            width: 90%;
        }

        form label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
            color: #34495e;
        }

        form select,
        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 6px;
            box-sizing: border-box;
        }


        button[type="submit"] {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #219150;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            text-align: center;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            text-align: center;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
        }

        .btn-group a button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-group a button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Adicionar Produto<br>à Comanda <?= htmlspecialchars($comanda_id) ?></h1>

        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($messageClass) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($comanda_id > 0): ?>
        <form method="post" action="add_item.php">
            <input type="hidden" name="comanda_id" value="<?= $comanda_id ?>">

            <label>Produto:</label>
            <select name="produto" required onchange="atualizarPreco()">
                <option value="">Selecione</option>
                <?php
                $stmt = $pdo->query("SELECT nome, preco FROM produtos");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['nome']) . '" data-preco="' . htmlspecialchars($row['preco']) . '">'
                        . htmlspecialchars($row['nome']) . '</option>';
                }
                ?>
            </select>

            <label>Descrição:</label>
            <input type="text" name="descricao" style="width: 100%; margin-left: 0px" required>

            <label>Quantidade:</label>
            <input type="number" name="quantidade" min="1" value="1" required oninput="atualizarPreco()" style="width: 100%; margin-left: 0px">

            <label>Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" readonly required>

            <button type="submit" style="width: 100%; margin-left: 0px">Adicionar</button>
        </form>

        <div class="btn-group">
            <?php if ($papel_usuario === 'admin'): ?>
                <a href="index.php"><button>MENU</button></a>
            <?php else: ?>
                <a href="index_user.php"><button>MENU</button></a>
            <?php endif; ?>

            <a href="abrir_comanda.php?id=<?= $comanda_id ?>"><button>Ver Comanda</button></a>
        </div>
        <?php else: ?>
            <p style="text-align:center; color: red;">Comanda não encontrada.</p>
        <?php endif; ?>
    </div>

    <script>
        function atualizarPreco() {
            const select = document.querySelector('select[name="produto"]');
            const precoField = document.getElementById('preco');
            const quantidade = parseFloat(document.querySelector('input[name="quantidade"]').value) || 1;
            const precoUnitario = parseFloat(select.options[select.selectedIndex].dataset.preco) || 0;
            precoField.value = (quantidade * precoUnitario).toFixed(2);
        }
    </script>
</body>
</html>
