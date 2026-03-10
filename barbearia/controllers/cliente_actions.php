<?php

require_once __DIR__ . '/ClienteController.php';

$controller = new ClienteController();

$action = $_GET['action'] ?? '';

if ($action == 'salvar') {
    $controller->salvar(
        $_POST['nome'],
        $_POST['telefone'],
        $_POST['servico']
    );

    header("Location: ../views/client/index.php?status=cadastrado");
    exit;
}

if ($action == 'excluir') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $controller->excluir($id);
    }

    header("Location: ../views/client/index.php?status=excluido");
    exit;
}

if ($action == 'atualizar') {
    $controller->atualizar(
        $_POST['id'],
        $_POST['nome'],
        $_POST['telefone'],
        $_POST['servico']
    );

    header("Location: ../views/client/index.php?status=atualizado");
    exit;
}

echo "Ação inválida.";