<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

include 'db.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

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

$total_geral = array_reduce($comandas, function($carry, $item) {
    return $carry + $item['total_comanda'];
}, 0);

// Monta HTML do PDF
$html = "<h2>Relatório de Vendas - {$mes}/{$ano}</h2>";
$html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>
            <thead>
                <tr>
                    <th>Comanda Nº</th>
                    <th>Data</th>
                    <th>Total (R$)</th>
                </tr>
            </thead>
            <tbody>";

foreach ($comandas as $comanda) {
    $html .= "<tr>
                <td>{$comanda['comanda_numero']}</td>
                <td>{$comanda['data_fechamento']}</td>
                <td>R$ " . number_format($comanda['total_comanda'], 2, ',', '.') . "</td>
              </tr>";
}

$html .= "</tbody>
          <tfoot>
              <tr>
                  <td colspan='2'><strong>Total Geral</strong></td>
                  <td><strong>R$ " . number_format($total_geral, 2, ',', '.') . "</strong></td>
              </tr>
          </tfoot>
        </table>";

// Gera PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_vendas_{$mes}_{$ano}.pdf", ["Attachment" => false]);
exit;
?>
