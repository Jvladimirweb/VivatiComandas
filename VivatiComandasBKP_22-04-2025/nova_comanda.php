<?php
session_start();
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
        <div class="container">
        <div class="button-container">
            <a href="index.php" class="butt">Menu</a>
            <a href="abrir_comanda.php" class="butt">Comandas</a>
        </div>
    </div>
    <?php endif; ?> 
    <?php if ($papel_usuario === 'user'): ?>
        <div class="container">
        <div class="button-container">
            <a href="index.php" class="butt">Menu</a>
            <a href="abrir_comanda.php" class="butt">Comandas</a>
        </div>
    <?php endif; ?>
    <form method="post"s action="comanda.php">
        <h1 style="margin-top: -5px">Nova Comanda</h1>
        <label for="numero">Para qual mesa é a comanda?</label>
        <input type="number" id="mesa" name="mesa" style="margin-left: -5px" required placeholder="Digite o número da mesa">
        <button type="submit">Gerar comanda</button>
    </form>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
            .then(() => console.log('Service Worker registrado com sucesso.'))
            .catch((error) => console.error('Falha ao registrar o Service Worker:', error));
        }
    </script>
</body>
</html>
