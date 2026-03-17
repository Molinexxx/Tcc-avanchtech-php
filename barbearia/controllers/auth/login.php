<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/auth/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    header('Location: ../../views/auth/login.php?erro=1');
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        header('Location: ../../views/auth/login.php?erro=1');
        exit;
    }

    $_SESSION['user_id'] = $usuario['id'];
    $_SESSION['user_name'] = $usuario['nome'];
    $_SESSION['barbearia_id'] = $usuario['barbearia_id'];
    $_SESSION['role'] = $usuario['role'] ?? 'admin';

    header('Location: ../../dashboard.php');
    exit;
} catch (Throwable $e) {
    echo 'Erro no login: ' . $e->getMessage();
    exit;
}
