<?php
include 'db.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Comandas</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>Comandas</h1>
    <table border="1">
        <tr>
            <th>Comanda</th>
            <th>Mesa</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($comandas as $comanda): ?>
        <tr>
            <td><?= htmlspecialchars($comanda['numero']) ?></td>
            <td><?= htmlspecialchars($comanda['mesa']) ?></td>
            <td><?= htmlspecialchars($comanda['status']) ?></td>
            <td>
                <a href="comanda.php?id=<?= htmlspecialchars($comanda['id']) ?>">Adicionar item |</a>
                <a href="mesa.php?id=<?= htmlspecialchars($comanda['id']) ?>">Mesa |</a>
                <?php if ($comanda['status'] === 'aberta'): ?>   
                <a href="fechar_comanda.php?id=<?= htmlspecialchars($comanda['id']) ?>" style="color: red;">Fechar |</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Criar Nova Comanda</h2>
    <form method="post" action="comanda.php">
        <label for="numero">Número da Comanda:</label>
        <input type="number" id="numero" name="numero" placeholder="Digite o número (ou deixe vazio)">
        <button type="submit">Criar</button>
    </form>

    <h2>Cadastrar Produto</h2>
    <form method="post" action="produtos.php">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>
        
        <label for="preco">Preço (R$):</label>
        <input type="number" id="preco" name="preco" step="0.01" required>
        
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
