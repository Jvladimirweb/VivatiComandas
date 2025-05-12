<?php
include 'db.php';

$comanda_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($comanda_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM comandas WHERE id = ?");
    $stmt->execute([$comanda_id]);
    $comanda = $stmt->fetch();

    if (!$comanda) {
        echo "Comanda não encontrada.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT produto, descricao, preco, quantidade FROM itens WHERE comanda_id = ?");
    $stmt->execute([$comanda_id]);
    $itens = $stmt->fetchAll();
} else {
    echo "Comanda não encontrada.";
    exit;
}

date_default_timezone_set('America/Sao_Paulo');
$data_hora = date('d/m/Y H:i:s');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Imprimir Comanda Nº <?= htmlspecialchars($comanda['numero']) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f8f8;
        }

        .comanda-container {
            width: 80mm;
            background: white;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1, h2 {
            text-align: center;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th, td {
            text-align: left;
            padding: 5px 0;
        }

        th {
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        td {
            border-bottom: 1px dashed #000;
        }

        .total {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .no-print button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .no-print button:hover {
            background-color: #0056b3;
        }

        @media print {
            body {
                display: block;
                background: none;
                padding: 0;
            }

            .comanda-container {
                box-shadow: none;
                width: 80mm;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            h1, h2 {
                font-size: 8px;
            }

            table th, table td {
                font-size: 10px;
            }

            .total {
                font-size: 16px;
            }
        }

        small {
            display: block;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="comanda-container">
        <table>
            <tr>
                <th>
                    <h1>Vivati Sabores</h1>
                    <h2>Rua Miguel Burnier 36 - Higienópolis RJ</h2>
                    <h2>21 97593-8155</h2>
                    <h2>CNPJ: 31.000.539/0001-74</h2>
                </th>
            </tr>
            <tr>
                <th>
                    <h1>Mesa: <?= htmlspecialchars($comanda['mesa']) ?> - Comanda <?= htmlspecialchars($comanda['numero']) ?></h1>
                    <h2>Status: <?= htmlspecialchars($comanda['status']) ?></h2>
                </th>
            </tr>
        </table>

        <p class="datetime">Impresso em: <?= $data_hora ?></p>

        <table>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Unit.</th>
                <th>Subtotal</th>
            </tr>
            <?php 
            $total = 0;
            foreach ($itens as $item): 
                $quantidade = max((int)$item['quantidade'], 1);
                $subtotal = $item['preco'];
                $unitario = $quantidade > 0 ? $subtotal / $quantidade : $subtotal;
                $total += $subtotal;
            ?>
            <tr>
                <td>
                    <?= htmlspecialchars($item['produto']) ?>
                    <?php if (!empty($item['descricao'])): ?>
                        <small><?= htmlspecialchars($item['descricao']) ?></small>
                    <?php endif; ?>
                </td>
                <td><?= $quantidade ?></td>
                <td>R$ <?= number_format($unitario, 2, ',', '.') ?></td>
                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p class="total">Total: R$ <?= number_format($total, 2, ',', '.') ?></p>

        <div class="no-print">
            <a href="imprimir.php?id=<?= $comanda_id ?>" style="text-decoration: none;">
                <button style="background-color: #6c757d;">Imprimir</button>
            </a>
            <a href="index.php" style="text-decoration: none;">
                <button style="background-color: #6c757d;">Voltar</button>
            </a>
        </div>
    </div>
</body>
</html>
