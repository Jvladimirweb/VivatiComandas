<?php
// ======= db.php deve estar incluso antes =======
require __DIR__ . '/vendor/autoload.php';
include 'db.php';

date_default_timezone_set('America/Sao_Paulo'); // Corrige o horário

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$mensagem = "";
$sucesso = false;

$comanda_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($comanda_id <= 0) {
    $mensagem = "❌ ID da comanda inválido.";
} else {
    try {
        // Buscar detalhes da comanda
        $stmt = $pdo->prepare("SELECT * FROM comandas WHERE id = ?");
        $stmt->execute([$comanda_id]);
        $comanda = $stmt->fetch();

        if (!$comanda) {
            throw new Exception("Comanda não encontrada.");
        }

        // Buscar itens da comanda
        $stmt = $pdo->prepare("SELECT * FROM itens WHERE comanda_id = ?");
        $stmt->execute([$comanda_id]);
        $itens = $stmt->fetchAll();

        // Iniciar impressão
        $ip_impressora = "192.168.0.8";
        $porta = 9100;

        $connector = new NetworkPrintConnector($ip_impressora, $porta);
        $printer = new Printer($connector);

        // Cabeçalho
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Vivati Sabores\n");
        $printer->text("Rua Miguel Burnier 36 - RJ\n");
        $printer->text("CNPJ: 31000539/0001-74\n");
        $printer->text("--------------------------------\n");

        // Info da comanda
        $printer->text("Mesa: {$comanda['mesa']} - Comanda: {$comanda['numero']}\n");
        $printer->text("Status: {$comanda['status']}\n");
        $printer->text("Impresso em: " . date('d/m/Y H:i:s') . "\n");
        $printer->text("--------------------------------\n");

        // Itens
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $total = 0;
        foreach ($itens as $item) {
            $quantidade = intval($item['quantidade']);
            $valor_total_item = floatval($item['preco']); // Preço já é o total

            $produto = mb_strimwidth($item['produto'], 0, 20);
            $valor_formatado = "R$ " . number_format($valor_total_item, 2, ',', '.');

            $printer->text("{$produto} x{$quantidade} {$valor_formatado}\n");

            if (!empty($item['descricao'])) {
                $printer->text("  > " . $item['descricao'] . "\n");
            }

            $total += $valor_total_item;
        }

        $printer->text("--------------------------------\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("Total: R$ " . number_format($total, 2, ',', '.') . "\n");

        $printer->feed(3);
        $printer->cut();
        $printer->close();

        $mensagem = "✅ Comanda impressa com sucesso!";
        $sucesso = true;
    } catch (Exception $e) {
        $mensagem = "❌ Erro ao imprimir: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Status da Impressão</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        .success {
            color: green;
            font-size: 18px;
        }

        .error {
            color: red;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="<?= $sucesso ? 'success' : 'error' ?>">
        <?= htmlspecialchars($mensagem) ?>
    </div>

    <?php if ($sucesso): ?>
    <script>
        setTimeout(function() {
            window.location.href = "index.php";
        }, 1000);
    </script>
    <?php endif; ?>
</body>
</html>
