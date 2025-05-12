<?php
include 'db.php';
include "autenticar.php";

// Obter ID da comanda
$comanda_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($comanda_id > 0) {
    // Buscar detalhes da comanda
    $stmt = $pdo->prepare("SELECT * FROM comandas WHERE id = ?");
    $stmt->execute([$comanda_id]);
    $comanda = $stmt->fetch();

    // Buscar itens da comanda
    $stmt = $pdo->prepare("SELECT * FROM itens WHERE comanda_id = ?");
    $stmt->execute([$comanda_id]);
    $itens = $stmt->fetchAll();
} else {
    echo "Comanda não encontrada.";
    exit;
}
// Gerar data e horário atuais
$data_hora = date('d/m/Y H:i:s');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Imprimir Comanda Nº <?= htmlspecialchars($comanda['numero']) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Estilo de texto típico de cupom fiscal */
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 80mm; /* Largura padrão para impressoras térmicas */
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

        table th, table td {
            text-align: left;
            padding: 5px 0;
        }

        table th {
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        table td {
            border-bottom: 1px dashed #000; /* Linha tracejada como em cupons */
        }

        .total {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .no-print {
            text-align: center;
            margin: 20px 0;
        }

        .no-print button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .no-print button:hover {
            background-color: #0056b3;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm; /* Garante que a impressão se ajuste à largura da impressora térmica */
            }

            .no-print {
                display: none; /* Esconde elementos desnecessários para impressão */
            }

            h1, h2 {
                font-size: 14px;
            }

            table {
                margin: 0;
            }

            table th, table td {
                font-size: 12px;
            }

            .total {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>
                <h1>Vivati Sabores</h1>
                <h2>Rua miguel Burnier 36 - Higienópoli RJ</h2>
                <h2>21 97593-8155</h2>
                <h2>CNPJ: 31000539/0001-74</h2>
            </th>
        </tr>
        <tr>
            <th>
                <h1>Nº da Mesa: <?= htmlspecialchars($comanda['mesa']) ?></h1>
                <h1>Comanda Nº <?= htmlspecialchars($comanda['numero']) ?></h1>
                <h2>Status: <?= htmlspecialchars($comanda['status']) ?></h2>
            </th>
        </tr>
        <p class="datetime">Impresso em: <?= date('d/m/Y H:i:s') ?></p>
    </table>

    <table>
        <tr>
            <th>Produto</th>
            <th>Preço (R$)</th>
        </tr>
        <?php 
        $total = 0;
        foreach ($itens as $item): 
            $total += $item['preco'];
        ?>
        <tr>
            <td><?= htmlspecialchars($item['produto']) ?></td>
            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p class="total">Total: R$ <?= number_format($total, 2, ',', '.') ?></p>

    <div class="no-print">
        <button onclick="window.print()">Imprimir</button>
        <a href="abrir_comanda.php" style="text-decoration: none;">
            <button style="background-color: #6c757d;">Voltar</button>
        </a>
    </div>
</body>
</html>
