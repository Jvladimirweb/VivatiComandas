<?php
include 'db.php';

// Gerar número automático se não for fornecido
if (empty($_POST['numero'])) {
    // Obter o maior número de comanda existente
    $stmt = $pdo->query("SELECT MAX(numero) AS max_numero FROM comandas");
    $resultado = $stmt->fetch();
    $numero = $resultado ? $resultado['max_numero'] + 1 : 1; // Começa em 1 se não houver comandas
    $mesa = intval($_POST['mesa']);
} else {
    $numero = intval($_POST['numero']);
    $mesa = intval($_POST['mesa']);
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
    <title>Comanda Nº <?= htmlspecialchars($comanda['numero']) ?></title>
    <link rel="stylesheet" href="style/style.css">
    <script>
        function atualizarPreco() {
            const select = document.getElementById('produto');
            const precoInput = document.getElementById('preco');
            const quantidadeInput = document.getElementById('quantidade');

            // Obter o preço unitário do atributo data-preco da opção selecionada
            const precoUnitario = parseFloat(select.options[select.selectedIndex].getAttribute('data-preco')) || 0;
            const quantidade = parseFloat(quantidadeInput.value) || 1;

            // Calcular o preço total
            const precoTotal = precoUnitario * quantidade;

            // Atualizar o campo de preço
            precoInput.value = precoTotal.toFixed(2);
        }

        // Garantir que o preço seja atualizado ao alterar a quantidade
        document.addEventListener('DOMContentLoaded', () => {
            const quantidadeInput = document.getElementById('quantidade');
            quantidadeInput.addEventListener('input', atualizarPreco);
        });
    </script>

</head>
<body>
    <h1>Comanda Nº <?= htmlspecialchars($comanda['numero']) ?>
    Mesa: <?= htmlspecialchars($comanda['mesa']) ?></br>
    Status: <?= htmlspecialchars($comanda['status']) ?></br>
    </h1>
    <?php if ($comanda['status'] === 'aberta'): ?>
        <form method="post" action="add_item.php">
            <input type="hidden" name="comanda_id" value="<?= htmlspecialchars($comanda_id) ?>">
            <div class="ajuste">
                <label for="produto">Adicionar produto:</label>
                <select id="produto" name="produto" required onchange="atualizarPreco()">
                    <option value="" data-preco="0">Selecione um produto</option>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT id, nome, preco FROM produtos");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($row['nome']) . '" data-preco="' . htmlspecialchars($row['preco']) . '">'
                                . htmlspecialchars($row['nome']) . '</option>';
                        }
                    } catch (PDOException $e) {
                        echo '<option value="">Erro ao carregar produtos</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="ajuste">
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" value="Descrição">
            </div>
            <div class="ajuste">
                <label for="quantidade"c>Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" step="1">
            </div>
            <div class="ajuste">
                <label for="preco">Preço (R$):</label>
                <input type="number" id="preco" name="preco" step="0.01" required readonly>
            </div>

            <button type="submit">Adicionar</button>
            </form>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Observação</th>
            <th>Preço (R$)</th>
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
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
            <td style="font-weight: bold;">R$ <?= number_format($total, 2, ',', '.') ?></td>
        </tr>
    </table>
    <?php if ($comanda['status'] === 'aberta'): ?>
        <form method="get" action="fechar_comanda.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($comanda_id) ?>">
            <nav class="navbar">
                <a href="abrir_comanda.php" class="butt">voltar</a>
                <button type="submit" style="background-color: red">Fechar Comanda</button>
            </nav>
        </form>
    <?php else: ?>
        <nav class="navbar">
        <a href="imprimir_comanda.php?id=<?= htmlspecialchars($comanda_id) ?>" class="butt">
        <button">Imprimir Comanda</button>
        </a>
        <a href="reabrir_comanda.php?id=<?= htmlspecialchars($comanda_id) ?>" class="butt">
        <button">Reabrir Comanda</button>
        </a>
    </nav>
        <p style="color: green;">Esta comanda está fechada.</p>
    <?php endif; ?>
</body>
</html>
