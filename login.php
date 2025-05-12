<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $senha = trim($_POST['senha']);

    // Verifica se o nome e a senha são válidos
    $stmt = $pdo->prepare("SELECT id, senha, papel FROM usuarios WHERE nome = ?");
    $stmt->execute([$nome]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Salva o ID do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $papel = $usuario['papel'];
        if ($papel === 'admin'){
            header('Location: index.php');
        }else{
            header('Location: index_user.php');
        }exit;
    } else {
        $erro = "Nome ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="manifest" href="/manifest.json">
</head>
<body>
<img  src="imgs/logo.png" alt="Imagem de Login" class="login-image">
<h1>Login</h1>
<?php if (!empty($erro)): ?>
    <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>
<form method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>
    <br>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>
    <br>
    <button type="submit">Entrar</button>
</form>
</body>
</html>

