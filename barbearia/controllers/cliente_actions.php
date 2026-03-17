<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/ClienteController.php';

requireRoles(['admin', 'recepcao']);

$controller = new ClienteController();
$action = $_GET['action'] ?? '';

if ($action === 'salvar') {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $servico = trim($_POST['servico'] ?? '');

    if ($nome === '' || $telefone === '' || $servico === '') {
        header("Location: ../views/client/cadastrar.php?status=erro");
        exit;
    }

    $controller->salvar($nome, $telefone, $servico);
    header("Location: ../views/client/index.php?status=cadastrado");
    exit;
}

if ($action === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);

    if ($id > 0) {
        $controller->excluir($id);
    }

    header("Location: ../views/client/index.php?status=excluido");
    exit;
}

if ($action === 'atualizar') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $servico = trim($_POST['servico'] ?? '');

    if ($id <= 0 || $nome === '' || $telefone === '' || $servico === '') {
        header("Location: ../views/client/index.php?status=erro");
        exit;
    }

    $controller->atualizar($id, $nome, $telefone, $servico);
    header("Location: ../views/client/index.php?status=atualizado");
    exit;
}

echo "Acao invalida.";
