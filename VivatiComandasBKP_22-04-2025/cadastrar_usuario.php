<?php
include "autenticar.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $papel = in_array($_POST['papel'], ['admin', 'user']) ? $_POST['papel'] : 'user';

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, senha, papel) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $senha, $papel]);

    echo "Usuário cadastrado com sucesso!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="manifest" href="/manifest.json">
</head>
<body>
<form method="post" style="width: 50%">
    <h1 style="margin-top: -5px">Cadastrar Usuário</h1>
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required style="margin-left: -2px;">
    <br>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required style="margin-left: -2px;">
    <br>
    <label for="papel">Papel:</label>
    <select id="papel" name="papel">
        <option value="user">Usuário</option>
        <option value="admin">Administrador</option>
    </select>
    <button type="submit">Cadastrar</button>
    <a href="index.php" class="butt">Menu</a>
</form>
</body>
</html>
