<?php
include 'db.php';

$hoje = date('Y-m-d');
$inicio = $_GET['inicio'] ?? date('Y-m-01');
$fim = $_GET['fim'] ?? $hoje;

try {
    $stmt = $pdo->prepare("SELECT * FROM comandas WHERE status = 'fechada' AND data_fechamento BETWEEN ? AND ? ORDER BY data_fechamento ASC");
    $stmt->execute([$inicio, $fim]);
    $comandas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_geral = 0;
    $detalhes = [];

    foreach ($comandas as $comanda) {
        $stmtItens = $pdo->prepare("SELECT produto, descricao, preco, quantidade FROM itens WHERE comanda_id = ?");
        $stmtItens->execute([$comanda['id']]);
        $itens = $stmtItens->fetchAll();

        $total_comanda = 0;
        foreach ($itens as $item) {
            $total_comanda += $item['preco']; // Subtotal é igual ao valor unitário
        }

        $total_geral += $total_comanda;

        $detalhes[] = [
            'numero' => $comanda['numero'],
            'data' => date('d/m/Y', strtotime($comanda['data_fechamento'])),
            'itens' => $itens,
            'total' => $total_comanda
        ];
    }

    $total_comandas = count($detalhes);
    $media = $total_comandas > 0 ? $total_geral / $total_comandas : 0;

} catch (PDOException $e) {
    die("Erro ao gerar relatório: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas Detalhado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }

        .container {
            max-width: 960px;
            background: white;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        input, button {
            padding: 8px 12px;
            font-size: 14px;
        }

        .comanda {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 15px;
        }

        .comanda h3 {
            margin: 0 0 10px 0;
            color: #007BFF;
        }

        .comanda table {
            width: 100%;
            border-collapse: collapse;
        }

        .comanda th, .comanda td {
            border-bottom: 1px solid #eee;
            padding: 8px;
            font-size: 14px;
        }

        .comanda th {
            background: #f5f5f5;
        }

        .comanda .total {
            text-align: right;
            font-weight: bold;
            padding-top: 10px;
        }

        .stats {
            margin-top: 30px;
            font-size: 16px;
            text-align: right;
        }

        .buttons {
            text-align: right;
            margin-top: 20px;
        }

        .buttons a {
            margin-left: 10px;
            padding: 8px 12px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .buttons a:hover {
            background: #218838;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 30px;
        }

        .back-button a {
            padding: 10px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-button a:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Relatório de Vendas Detalhado</h1>

        <form method="get">
            <label>De: <input type="date" name="inicio" value="<?= htmlspecialchars($inicio) ?>"></label>
            <label>Até: <input type="date" name="fim" value="<?= htmlspecialchars($fim) ?>"></label>
            <button type="submit">Filtrar</button>
        </form>

        <div class="buttons">
            <a href="export_pdf.php?inicio=<?= urlencode($inicio) ?>&fim=<?= urlencode($fim) ?>" target="_blank">Exportar PDF</a>
            <a href="export_csv.php?inicio=<?= urlencode($inicio) ?>&fim=<?= urlencode($fim) ?>">Exportar CSV</a>
        </div>

        <?php if (!empty($detalhes)): ?>
            <?php foreach ($detalhes as $comanda): ?>
                <div class="comanda">
                    <h3>Comanda Nº <?= htmlspecialchars($comanda['numero']) ?> - <?= $comanda['data'] ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Qtd</th>
                                <th>Unitário (R$)</th>
                                <th>Subtotal (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comanda['itens'] as $item): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($item['produto']) ?>
                                        <?php if (!empty($item['descricao'])): ?>
                                            <br><small><?= htmlspecialchars($item['descricao']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $item['quantidade'] ?? 1 ?></td>
                                    <td><?= number_format($item['preco'], 2, ',', '.') ?></td>
                                    <td><?= number_format($item['preco'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="total">Total da comanda: R$ <?= number_format($comanda['total'], 2, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>

            <div class="stats">
                <p>Total geral: <strong>R$ <?= number_format($total_geral, 2, ',', '.') ?></strong></p>
                <p>Total de comandas fechadas: <strong><?= $total_comandas ?></strong></p>
                <p>Média por comanda: <strong>R$ <?= number_format($media, 2, ',', '.') ?></strong></p>
            </div>
        <?php else: ?>
            <p style="color: red; text-align: center;">Nenhuma comanda encontrada neste período.</p>
        <?php endif; ?>

        <div class="back-button">
            <a href="index.php">← Voltar para o Início</a>
        </div>
    </div>
</body>
</html>

