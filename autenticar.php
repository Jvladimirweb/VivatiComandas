<?php
session_start();
include 'db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Obtém os dados do usuário autenticado
$stmt = $pdo->prepare("SELECT id, nome, papel FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe
if (!$usuario) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Define as informações do usuário
$nome_usuario = htmlspecialchars($usuario['nome']);
$papel_usuario = htmlspecialchars($usuario['papel']);

// Páginas permitidas para usuários comuns (tipo 'user')
$paginas_permitidas_user = [
    'login.php',
    'index_user.php',
    'abrir_comanda.php',
    'add_item.php',
    'mesa.php',
    'fechar_comanda.php',
    'pedidos_preparo.php',
    'nova_comanda.php',
];

$paginas_permitidas_admin = [
    'login.php',
    'index_user.php',
    'abrir_comanda.php',
    'add_item.php',
    'mesa.php',
    'fechar_comanda.php',
    'cadastrar_user.php',
    'cadastrar_produto.php',
    'comanda.php',
    'fechar_comanda.php',
    'imprimir_comanda.php',
    'incluirMesa.php',
    'index.php',
    'logout.php',
    'produtos.php',
    'reabrir_comanda.php',
    'relatorio_mes.php',
    'pedidos_preparo.php',
    'nova_comanda.php',
];

// Obtém o nome do arquivo atual
$pagina_atual = basename($_SERVER['PHP_SELF']);

// Verifica permissões para usuários do tipo 'user'
if ($papel_usuario === 'user' && !in_array($pagina_atual, $paginas_permitidas_user)) {
    echo "Acesso negado. Você não tem permissão para acessar esta página.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema Protegido</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#007BFF">
</head>
<body>
<header>
    <img  src="imgs/logo_mini.png" alt="Imagem de Login" class="login-image">
        <h1>Bem-vindo(a), <?= $nome_usuario ?> (<?= $papel_usuario ?>)</h1>
        <nav class="navbar">
        <?php if ($papel_usuario === 'admin'): ?>
            <a href="cadastrar_usuario.php" class="butt">Gerenciar Usuários</a>
        <?php endif; ?>
        <a href="logout.php" class="butt">Sair</a> 
    </nav>
</header>
</body>
</html>
