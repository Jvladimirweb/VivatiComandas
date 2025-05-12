<?php
include 'db.php';
include 'autenticar.php'
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
    <a href="index.php" style="width: 50%" class="butt">Menu</a>
    <form method="post" action="produtos.php">
        <h1 id="titlulo" style="margin-top: -5px">Cadastrar produtos</h1>
        <label for="nome" style="margin-left: 40px">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" style="margin-left: -5px" required></br>
        <label for="preco" style="margin-left: 80px">Preço (R$):</label>
        <input type="number" id="preco" name="preco" step="0.01" style="margin-left: -5px" required>
        <button type="submit"style="margin-left: 180px; width: 50%">Cadastrar</button>
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
