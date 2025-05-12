<?php
include 'db.php';
include "autenticar.php";
// Buscar todas as comandas
$stmt = $pdo->query("SELECT * FROM comandas WHERE status = 'aberta'");
$comandas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Comandas</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#007BFF">
</head>
<body>
    <?php if ($papel_usuario === 'admin'): ?>
        <nav class="navbar">
            <a href="index.php" class="butt">Menu</a>
            <a href="nova_comanda.php" class="butt">Nova Comanda</a>
        </nav>
    <?php endif; ?>
    <?php if ($papel_usuario === 'user'): ?>
        <nav class="navbar">
            <a href="index_user.php" class="butt">Menu</a>
            <img  src="imgs/logo_mini.png" alt="Imagem de Login" class="login-image">
            <a href="nova_comanda.php" class="butt">Nova Comanda</a>
        </nav>
    <?php endif; ?>
    <h1 id="titlulo">Central de Comandas</h1>
    <table border="1">
        <tr>
            <th>Comanda</th>
            <th>Mesa</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($comandas as $comanda): ?>
        <tr>
            <td style="background-color: #d1f5da;"><?= htmlspecialchars($comanda['numero']) ?></td>
            <td style="background-color: #b9eec5;"><?= htmlspecialchars($comanda['mesa']) ?></td>
            <td style="background-color: #d1f5da;"><?= htmlspecialchars($comanda['status']) ?></td>
            <td>
                <a href="comanda.php?id=<?= htmlspecialchars($comanda['id']) ?>" class="butt">Adicionar item</a>
                <a href="mesa.php?id=<?= htmlspecialchars($comanda['id']) ?>" class="butt">Mudar Mesa</a>
                <?php if ($comanda['status'] === 'aberta'): ?>   
                <a href="fechar_comanda.php?id=<?= htmlspecialchars($comanda['id']) ?>" class="butt" style="background-color: red;">Fechar Comanda</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
            .then(() => console.log('Service Worker registrado com sucesso.'))
            .catch((error) => console.error('Falha ao registrar o Service Worker:', error));
        }
    </script>
</body>
</html>
