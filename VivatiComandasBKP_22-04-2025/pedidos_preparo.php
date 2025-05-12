<?php
session_start();
include 'db.php';
include "autenticar.php";

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
        WHERE c.status = 'aberta'
        ORDER BY c.numero, i.criado_em ASC";

$result = $pdo->query($sql);

$comandas = [];
if ($result && $result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Monitoramento de Comandas</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 16px;
            position: fixed;
            z-index: 999;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s ease-in-out;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
        }

        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .novo { background-color: #dff0d8; }
        .atendido { background-color: #f0f0f0; text-decoration: line-through; }
        .butt { padding: 8px 12px; margin: 10px 0; display: inline-block; }
    </style>
    <script>
        async function atualizarStatus(itemId, novoStatus) {
            const response = await fetch('atualizar_atendimento.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ item_id: itemId, status: novoStatus })
            });

            if (response.ok) {
                location.reload(); // Atualiza a página para refletir as mudanças
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

    <h1>Pedidos em Preparo</h1>

    <?php foreach ($comandas as $numero_comanda => $comanda): ?>
        <table>
            <thead>
                <tr>
                    <th>Comanda #<?= $numero_comanda ?></th>
                    <th>Mesa <?= $comanda['mesa'] ?></th>
                </tr>
                <tr>
                    <th>Produto</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comanda['itens'] as $item): ?>
                    <tr id="item-<?= $item['item_id'] ?>" class="<?= $item['atendido'] ? 'atendido' : 'novo' ?>">
                        <td><?= htmlspecialchars($item['produto']) ?></td>
                        <td><?= htmlspecialchars($item['descricao']) ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>
                            <?php if (!$item['atendido']): ?>
                                <button onclick="atualizarStatus(<?= $item['item_id'] ?>, 1)">Marcar como Atendido</button>
                            <?php else: ?>
                                <button onclick="atualizarStatus(<?= $item['item_id'] ?>, 0)">Desfazer Atendimento</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
    <div id="toast" class="toast"></div>
    <script>
    function showToast(message) {
        const toast = document.getElementById("toast");
        toast.textContent = message;
        toast.className = "toast show";
        setTimeout(() => {
            toast.className = toast.className.replace("show", "");
        }, 3000);
    }

    async function atualizarStatus(itemId, novoStatus) {
        const response = await fetch('atualizar_atendimento.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ item_id: itemId, status: novoStatus })
        });

        if (response.ok) {
            if (novoStatus === 1) {
                showToast("✅ Item marcado como atendido!");
            } else {
                showToast("🔄 Atendimento desfeito!");
            }
            setTimeout(() => location.reload(), 1000); // Espera antes de recarregar
        } else {
            showToast("❌ Erro ao atualizar item.");
        }
    }
</script>
</body>
</html>
