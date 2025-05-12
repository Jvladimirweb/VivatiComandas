<?php
session_start();
include 'db.php';
include "autenticar.php";
// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = ''; // Altere conforme a senha do seu banco
$dbname = 'bar'; // Altere se o nome do banco for diferente

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta para obter comandas abertas com seus itens
$sql = "SELECT 
            c.numero AS numero_comanda, 
            c.mesa, 
            i.id AS item_id,
            i.produto, 
            i.descricao, 
            i.quantidade, 
            i.criado_em, 
            i.atendido
        FROM comandas c
        JOIN itens i ON c.id = i.comanda_id
        WHERE c.status = aberta
        ORDER BY c.numero, i.criado_em ASC";

$result = $conn->query($sql);

$comandas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comandas[$row['numero_comanda']]['mesa'] = $row['mesa'];
        $comandas[$row['numero_comanda']]['itens'][] = [
            'item_id' => $row['item_id'],
            'produto' => $row['produto'],
            'descricao' => $row['descricao'],
            'quantidade' => $row['quantidade'],
            'criado_em' => $row['criado_em'],
            'atendido' => $row['atendido'],
        ];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Monitoramento de Comandas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .novo { background-color: #dff0d8; } /* Verde claro */
        .atendido { background-color: #f0f0f0; text-decoration: line-through; } /* Cinza claro */
    </style>
    <script>
        async function marcarAtendido(itemId) {
            const response = await fetch('marcar_atendido.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ item_id: itemId })
            });

            if (response.ok) {
                document.getElementById('item-' + itemId).classList.remove('novo');
                document.getElementById('item-' + itemId).classList.add('atendido');
            } else {
                alert('Falha ao atualizar o item.');
            }
        }
    </script>
</head>
<body>
    <?php if ($papel_usuario === 'admin'): ?>
            <a href="index.php" class="butt">Menu</a>
    <?php endif; ?>
    <?php if ($papel_usuario === 'user'): ?>
        <a href="index_user.php" class="butt">Menu</a>
    <?php endif; ?>
    <?php foreach ($comandas as $numero_comanda => $comanda): ?>
        <h2>Comanda #<?php echo $numero_comanda; ?> - Mesa <?php echo $comanda['mesa']; ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comanda['itens'] as $item): ?>
                    <tr id="item-<?php echo $item['item_id']; ?>" class="<?php echo $item['atendido'] ? 'atendido' : 'novo'; ?>">
                        <td><?php echo $item['produto']; ?></td>
                        <td><?php echo $item['descricao']; ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td>
                            <?php if (!$item['atendido']): ?>
                                <button onclick="marcarAtendido(<?php echo $item['item_id']; ?>)">Marcar como Atendido</button>
                            <?php else: ?>
                                Atendido
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</body>
</html>
