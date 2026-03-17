<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/AgendamentoController.php';

requireRoles(['admin', 'recepcao', 'barbeiro']);

$controller = new AgendamentoController();
$action = $_GET['action'] ?? '';
$scope = trim($_POST['scope'] ?? $_GET['scope'] ?? '');
$redirectScope = $scope !== '' ? '&scope=' . urlencode($scope) : '';

if ($action === 'salvar') {
    $clienteId = (int) ($_POST['cliente_id'] ?? 0);
    $servicoId = (int) ($_POST['servico_id'] ?? 0);
    $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
    $dataHora = trim($_POST['data_hora'] ?? '');
    $status = trim($_POST['status'] ?? 'pendente');
    $observacoes = trim($_POST['observacoes'] ?? '');

    if ($clienteId <= 0 || $servicoId <= 0 || $usuarioId <= 0 || $dataHora === '') {
        header('Location: ../views/schedule/index.php?status=erro' . $redirectScope);
        exit;
    }

    $resultado = $controller->salvar($clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes);
    if (in_array($resultado, ['conflito', 'fechado', 'fora_expediente', 'pausa'], true)) {
        header('Location: ../views/schedule/index.php?status=' . $resultado . $redirectScope);
        exit;
    }

    if ($resultado !== 'ok') {
        header('Location: ../views/schedule/index.php?status=erro' . $redirectScope);
        exit;
    }

    header('Location: ../views/schedule/index.php?status=cadastrado' . $redirectScope);
    exit;
}

if ($action === 'atualizar') {
    $id = (int) ($_POST['id'] ?? 0);
    $clienteId = (int) ($_POST['cliente_id'] ?? 0);
    $servicoId = (int) ($_POST['servico_id'] ?? 0);
    $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
    $dataHora = trim($_POST['data_hora'] ?? '');
    $status = trim($_POST['status'] ?? 'pendente');
    $observacoes = trim($_POST['observacoes'] ?? '');

    if ($id <= 0 || $clienteId <= 0 || $servicoId <= 0 || $usuarioId <= 0 || $dataHora === '') {
        header('Location: ../views/schedule/index.php?status=erro' . $redirectScope);
        exit;
    }

    $resultado = $controller->atualizar($id, $clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes);
    if (in_array($resultado, ['conflito', 'fechado', 'fora_expediente', 'pausa'], true)) {
        header('Location: ../views/schedule/index.php?status=' . $resultado . $redirectScope);
        exit;
    }

    if ($resultado !== 'ok') {
        header('Location: ../views/schedule/index.php?status=erro' . $redirectScope);
        exit;
    }

    header('Location: ../views/schedule/index.php?status=atualizado' . $redirectScope);
    exit;
}

if ($action === 'status') {
    $id = (int) ($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if ($id <= 0 || $status === '') {
        header('Location: ../views/schedule/index.php?status=erro' . $redirectScope);
        exit;
    }

    $controller->atualizarStatus($id, $status);
    header('Location: ../views/schedule/index.php?status=atualizado' . $redirectScope);
    exit;
}

if ($action === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        header('Location: ../views/schedule/index.php?status=erro' . $redirectScope);
        exit;
    }

    $controller->excluir($id);
    header('Location: ../views/schedule/index.php?status=excluido' . $redirectScope);
    exit;
}

echo 'Acao invalida.';
