<?php
include 'db.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=relatorio_vendas_{$mes}_{$ano}.csv");

$output = fopen('php://output', 'w');

// Cabeçalhos
fputcsv($output, ['Comanda Nº', 'Data de Fechamento', 'Total da Comanda (R$)']);

// Buscar dados
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

// Conteúdo
foreach ($comandas as $comanda) {
    fputcsv($output, [
        $comanda['comanda_numero'],
        $comanda['data_fechamento'],
        number_format($comanda['total_comanda'], 2, ',', '.')
    ]);
}
fclose($output);
exit;
?>
