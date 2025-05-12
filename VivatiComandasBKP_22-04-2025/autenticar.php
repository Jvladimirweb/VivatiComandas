<?php
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

</body>
</html>
