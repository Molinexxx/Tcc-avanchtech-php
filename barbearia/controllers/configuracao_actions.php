<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/ConfiguracaoController.php';

requireRoles(['admin', 'recepcao']);

$controller = new ConfiguracaoController();
$action = $_GET['action'] ?? '';

if ($action === 'horarios') {
    $dias = range(0, 6);

    foreach ($dias as $dia) {
        $aberto = isset($_POST['aberto'][$dia]) ? 1 : 0;
        $horaInicio = $_POST['hora_inicio'][$dia] ?? null;
        $horaFim = $_POST['hora_fim'][$dia] ?? null;

        if ($aberto && ($horaInicio === '' || $horaFim === '' || $horaInicio >= $horaFim)) {
            header('Location: ../views/settings/index.php?status=erro_horario');
            exit;
        }

        $controller->salvarHorario($dia, $aberto, $aberto ? $horaInicio : null, $aberto ? $horaFim : null);
    }

    header('Location: ../views/settings/index.php?status=horarios_salvos');
    exit;
}

if ($action === 'pausa_salvar') {
    $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
    $dataInicio = trim($_POST['data_inicio'] ?? '');
    $dataFim = trim($_POST['data_fim'] ?? '');
    $motivo = trim($_POST['motivo'] ?? '');

    if ($usuarioId <= 0 || $dataInicio === '' || $dataFim === '' || $dataInicio >= $dataFim) {
        header('Location: ../views/settings/index.php?status=erro_pausa');
        exit;
    }

    $controller->salvarPausa($usuarioId, date('Y-m-d H:i:s', strtotime($dataInicio)), date('Y-m-d H:i:s', strtotime($dataFim)), $motivo);
    header('Location: ../views/settings/index.php?status=pausa_salva');
    exit;
}

if ($action === 'pausa_excluir') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id > 0) {
        $controller->excluirPausa($id);
    }

    header('Location: ../views/settings/index.php?status=pausa_excluida');
    exit;
}

echo 'Acao invalida.';
