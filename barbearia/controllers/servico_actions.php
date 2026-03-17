<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/ServicoController.php';

requireRoles(['admin', 'recepcao']);

$controller = new ServicoController();
$action = $_GET['action'] ?? '';

if ($action === 'salvar') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = (float) ($_POST['preco'] ?? 0);
    $duracaoMin = (int) ($_POST['duracao_min'] ?? 0);

    if ($nome === '' || $preco <= 0 || $duracaoMin <= 0) {
        header('Location: ../views/service/index.php?status=erro');
        exit;
    }

    $controller->salvar($nome, $preco, $duracaoMin);
    header('Location: ../views/service/index.php?status=cadastrado');
    exit;
}

if ($action === 'atualizar') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $preco = (float) ($_POST['preco'] ?? 0);
    $duracaoMin = (int) ($_POST['duracao_min'] ?? 0);

    if ($id <= 0 || $nome === '' || $preco <= 0 || $duracaoMin <= 0) {
        header('Location: ../views/service/index.php?status=erro');
        exit;
    }

    $controller->atualizar($id, $nome, $preco, $duracaoMin);
    header('Location: ../views/service/index.php?status=atualizado');
    exit;
}

if ($action === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id > 0) {
        $controller->excluir($id);
    }

    header('Location: ../views/service/index.php?status=excluido');
    exit;
}

echo 'Acao invalida.';
