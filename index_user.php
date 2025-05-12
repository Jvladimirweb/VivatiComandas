<?php

session_start();
include 'db.php';
include "autenticar.php";

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar - Menu Inicial</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="style/styleInicio.css">
</head>
<body>
    <br>
    <div class="container">
        <h1>Bem-vindo ao Sistema do Bar</h1>
        <div class="button-container">
            <a href="abrir_comanda.php" class="btn">Abrir Comanda</a>
            <a href="pedidos_preparo.php" class="btn">Controle de Pedidos</a>
        </div>
    </div>
</body>
</html>
