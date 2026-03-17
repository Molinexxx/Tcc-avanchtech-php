<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/UsuarioController.php';

requireAdmin();

$controller = new UsuarioController();
$action = $_GET['action'] ?? '';
$rolesPermitidos = ['admin', 'barbeiro', 'recepcao'];

if ($action === 'salvar') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $role = trim($_POST['role'] ?? 'barbeiro');

    if ($nome === '' || $email === '' || $senha === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, $rolesPermitidos, true)) {
        header('Location: ../views/user/index.php?status=erro');
        exit;
    }

    if ($controller->emailExiste($email)) {
        header('Location: ../views/user/index.php?status=email');
        exit;
    }

    $controller->salvar($nome, $email, $senha, $role);
    header('Location: ../views/user/index.php?status=cadastrado');
    exit;
}

if ($action === 'atualizar') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $role = trim($_POST['role'] ?? 'barbeiro');

    if ($id <= 0 || $nome === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, $rolesPermitidos, true)) {
        header('Location: ../views/user/index.php?status=erro');
        exit;
    }

    if ($controller->emailExiste($email, $id)) {
        header('Location: ../views/user/index.php?status=email');
        exit;
    }

    $controller->atualizar($id, $nome, $email, $role, $senha);
    header('Location: ../views/user/index.php?status=atualizado');
    exit;
}

if ($action === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);

    if ($id > 0 && $id !== (int) $_SESSION['user_id']) {
        $controller->excluir($id);
    }

    header('Location: ../views/user/index.php?status=excluido');
    exit;
}

echo 'Acao invalida.';
