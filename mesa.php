<?php
include 'db.php';


// Obter ID da comanda
$comanda_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $stmt = $pdo->prepare("SELECT * FROM comandas WHERE id = ?");
    $stmt->execute([$comanda_id]);
    $comanda = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Comanda Nº <?= htmlspecialchars($comanda['numero']) ?></title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>
        Comanda Nº <?= htmlspecialchars($comanda['numero']) ?></br>Numero da mesa:
        <?= htmlspecialchars($comanda['mesa']) ?>
    </h1>
    <p>Status: <?= htmlspecialchars($comanda['status']) ?> </p>
    <p>Mude o numero da mesa aqui:</p>

    <?php if ($comanda['status'] === 'aberta'): ?>
        <form method="post" action="incluirMesa.php">
            <input type="hidden" name="comanda_id" value="<?= htmlspecialchars($comanda_id) ?>">
            <label for="preco">Nova Mesa:</label>
            <input type="number" name="mesa" required>
            <button type="submit">Mudar mesa</button>
        </form>
    <?php endif; ?>
    <nav class="navbar">
        <a href="abrir_comanda.php" class="butt">Voltar</a>
    </nav>
</body>
</html>
