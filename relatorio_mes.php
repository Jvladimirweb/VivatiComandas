<?php
include 'db.php'; // Conexão com o banco de dados

// Obter o mês atual e ano (ou receber via GET/POST para relatórios de meses específicos)
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

// Consulta SQL para calcular o total vendido no mês
try {
    $stmt = $pdo->prepare("
        SELECT 
            c.numero AS comanda_numero,
            DATE_FORMAT(c.data_fechamento, '%d/%m/%Y') AS data_fechamento,
            SUM(i.preco * i.quantidade) AS total_comanda
        FROM 
            comandas c
        INNER JOIN 
            itens i ON c.id = i.comanda_id
        WHERE 
            c.status = 'fechada' 
            AND MONTH(c.data_fechamento) = ? 
            AND YEAR(c.data_fechamento) = ?
        GROUP BY 
            c.id
        ORDER BY 
            c.data_fechamento ASC
    ");
    $stmt->execute([$mes, $ano]);
    $comandas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcular o total geral vendido no mês
    $total_geral = array_reduce($comandas, function ($carry, $item) {
        return $carry + $item['total_comanda'];
    }, 0);
} catch (PDOException $e) {
    die("Erro ao gerar relatório: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Relatório Mensal de Vendas</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <img  src="imgs/logo_mini.png" alt="Imagem de Login" class="login-image">
    <div class="container">
        <a href="index.php">
            <button style="background-color: #007BFF; color: white;">Menu</button>
        </a>
        <h1>Relatório de Vendas - <?= htmlspecialchars($mes . '/' . $ano) ?></h1>
        
        <!-- Formulário para selecionar outro mês/ano -->
        <form method="get" action="" style="width: 100%;">
            <label for="mes">Mês:</label>
            <select id="mes" name="mes"></br>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $mes ? 'selected' : '' ?>>
                        <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                    </option>
                <?php endfor; ?>
            </select>

            <label for="ano">Ano:</label>
            <select id="ano" name="ano">
                <?php for ($i = date('Y') - 5; $i <= date('Y'); $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $ano ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>

            <button type="submit" style="margin-top: 10px;">Gerar Relatório</button>
        </form>

        <!-- Tabela com resultados -->
        <?php if (!empty($comandas)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Comanda Nº</th>
                        <th>Data de Fechamento</th>
                        <th>Total da Comanda (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comandas as $comanda): ?>
                        <tr>
                            <td><?= htmlspecialchars($comanda['comanda_numero']) ?></td>
                            <td><?= htmlspecialchars($comanda['data_fechamento']) ?></td>
                            <td>R$ <?= number_format($comanda['total_comanda'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total Geral</th>
                        <th>R$ <?= number_format($total_geral, 2, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p style="color: red;">Nenhuma comanda fechada encontrada para este período.</p>
        <?php endif; ?>
    </div>
</body>
</html>
