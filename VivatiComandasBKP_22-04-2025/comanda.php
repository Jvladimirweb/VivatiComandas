<?php
include 'db.php';

// Gerar número automático se não for fornecido
if (empty($_POST['numero'])) {
    // Obter o maior número de comanda existente
    $stmt = $pdo->query("SELECT MAX(numero) AS max_numero FROM comandas");
    $resultado = $stmt->fetch();
    $numero = $resultado ? $resultado['max_numero'] + 1 : 1; // Começa em 1 se não houver comandas
    $mesa = intval($_POST['mesa'] ?? 0);
} else {
    $numero = intval($_POST['numero']);
    $mesa = intval($_POST['mesa'] ?? 0);
}

// Obter ID da comanda
$comanda_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($comanda_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM comandas WHERE id = ?");
    $stmt->execute([$comanda_id]);
    $comanda = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM itens WHERE comanda_id = ?");
    $stmt->execute([$comanda_id]);
    $itens = $stmt->fetchAll();
} else {
    // Inserir a nova comanda no banco
    $stmt = $pdo->prepare("INSERT INTO comandas (numero, mesa) VALUES (?, ?)");
    $stmt->execute([$numero, $mesa]);
    header("Location: abrir_comanda.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Comanda <?= htmlspecialchars($comanda['numero']) ?></title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>Comanda <?= htmlspecialchars($comanda['numero']) ?>, 
    Mesa: <?= htmlspecialchars($comanda['mesa']) ?>.<br>
    </h1>

    <table border="1">
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Observação</th>
            <th>Preço (R$)</th>
            <?php if ($comanda['status'] === 'aberta'): ?>
                <th>Ação</th>
            <?php endif; ?>
        </tr>
        <?php 
        $total = 0; // Inicializar o total
        foreach ($itens as $item): 
            $total += $item['preco']; // Somar o preço ao total
        ?>
        <tr>
            <td><?= htmlspecialchars($item['produto']) ?></td>
            <td><?= number_format($item['quantidade'], 0) ?></td>
            <td><?= htmlspecialchars($item['descricao']) ?></td>
            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
            <?php if ($comanda['status'] === 'aberta'): ?>
                <td>
                    <form method="post" action="remover_item.php" style="margin: 0;">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <input type="hidden" name="comanda_id" value="<?= $comanda_id ?>">
                        <button type="submit" style="color: white; background-color: red; border: none; padding: 5px; cursor: pointer;">Remover</button>
                    </form>
                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="<?= $comanda['status'] === 'aberta' ? '4' : '3' ?>" style="text-align: right; font-weight: bold;">Total:</td>
            <td style="font-weight: bold;">R$ <?= number_format($total, 2, ',', '.') ?></td>
        </tr>
    </table>

    <?php if ($comanda['status'] === 'aberta'): ?>
        <form method="get" action="fechar_comanda.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($comanda_id) ?>">
            <nav class="navbar">
                <a href="abrir_comanda.php" class="butt">Voltar</a>
                <button type="submit" style="background-color: red;">Fechar Comanda</button>
            </nav>
        </form>
    <?php else: ?>
        <table>
            <tr style="background-color: white;">
                <td>
                    <a href="imprimir_comanda.php?id=<?= htmlspecialchars($comanda_id) ?>" class="btn">
                        <button>Imprimir Comanda</button>
                    </a>
                </td>
                <td>
                    <a href="reabrir_comanda.php?id=<?= htmlspecialchars($comanda_id) ?>" class="btn">
                        <button>Reabrir Comanda</button>
                    </a>
                </td>
            </tr>
    </table>
        <p style="color: green;">Esta comanda está fechada.</p>
    <?php endif; ?>
</body>
</html>
