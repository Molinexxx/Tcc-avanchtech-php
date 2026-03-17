<?php

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../controllers/ConfiguracaoController.php';

requireRoles(['admin', 'recepcao']);

$controller = new ConfiguracaoController();
$horarios = $controller->listarHorarios();
$pausas = $controller->listarPausas();
$profissionais = $controller->listarProfissionais();

$horariosPorDia = [];
foreach ($horarios as $horario) {
    $horariosPorDia[(int) $horario['dia_semana']] = $horario;
}

$diasSemana = [
    0 => 'Domingo',
    1 => 'Segunda',
    2 => 'Terca',
    3 => 'Quarta',
    4 => 'Quinta',
    5 => 'Sexta',
    6 => 'Sabado',
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Disponibilidade - BarberPro</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="app-shell">
    <div class="page-container">
        <div class="page-header">
            <div>
                <span class="eyebrow">Disponibilidade</span>
                <h1>Horarios e pausas</h1>
                <p class="section-copy">Defina o expediente da barbearia e os intervalos dos profissionais.</p>
            </div>
            <a href="../../dashboard.php" class="btn btn-outline-light">Dashboard</a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'horarios_salvos') { ?>
            <div class="alert alert-success">Horarios de funcionamento atualizados.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'pausa_salva') { ?>
            <div class="alert alert-success">Pausa cadastrada com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'pausa_excluida') { ?>
            <div class="alert alert-danger">Pausa removida com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && in_array($_GET['status'], ['erro_horario', 'erro_pausa'], true)) { ?>
            <div class="alert alert-warning">Revise os dados informados e tente novamente.</div>
        <?php } ?>

        <div class="row g-4">
            <div class="col-xl-7">
                <div class="panel-card">
                    <span class="eyebrow">Expediente</span>
                    <h2 class="mb-4">Horario de funcionamento</h2>

                    <form action="../../controllers/configuracao_actions.php?action=horarios" method="POST">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Dia</th>
                                        <th>Aberto</th>
                                        <th>Inicio</th>
                                        <th>Fim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($diasSemana as $dia => $label) {
                                        $horario = $horariosPorDia[$dia] ?? ['aberto' => 0, 'hora_inicio' => '09:00:00', 'hora_fim' => '19:00:00'];
                                    ?>
                                        <tr>
                                            <td><?php echo $label; ?></td>
                                            <td>
                                                <input type="checkbox" name="aberto[<?php echo $dia; ?>]" value="1" <?php echo !empty($horario['aberto']) ? 'checked' : ''; ?>>
                                            </td>
                                            <td><input type="time" name="hora_inicio[<?php echo $dia; ?>]" class="form-control" value="<?php echo substr((string) ($horario['hora_inicio'] ?? '09:00:00'), 0, 5); ?>"></td>
                                            <td><input type="time" name="hora_fim[<?php echo $dia; ?>]" class="form-control" value="<?php echo substr((string) ($horario['hora_fim'] ?? '19:00:00'), 0, 5); ?>"></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <button class="btn btn-brand mt-4" type="submit">Salvar horarios</button>
                    </form>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="panel-card">
                    <span class="eyebrow">Intervalos</span>
                    <h2 class="mb-4">Nova pausa</h2>

                    <form action="../../controllers/configuracao_actions.php?action=pausa_salvar" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Profissional</label>
                            <select name="usuario_id" class="form-control" required>
                                <option value="">Selecione</option>
                                <?php foreach ($profissionais as $profissional) { ?>
                                    <option value="<?php echo $profissional['id']; ?>"><?php echo htmlspecialchars($profissional['nome']); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Inicio</label>
                            <input type="datetime-local" name="data_inicio" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fim</label>
                            <input type="datetime-local" name="data_fim" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Motivo</label>
                            <input type="text" name="motivo" class="form-control" placeholder="Almoco, curso, atendimento externo...">
                        </div>

                        <button class="btn btn-brand" type="submit">Salvar pausa</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel-card" style="margin-top: 24px;">
            <span class="eyebrow">Pausas cadastradas</span>
            <h2 class="mb-4">Agenda de indisponibilidade</h2>

            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Profissional</th>
                            <th>Inicio</th>
                            <th>Fim</th>
                            <th>Motivo</th>
                            <th class="text-end">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pausas as $pausa) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pausa['profissional']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pausa['data_inicio'])); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pausa['data_fim'])); ?></td>
                                <td><?php echo htmlspecialchars($pausa['motivo'] ?: '-'); ?></td>
                                <td class="text-end">
                                    <a href="../../controllers/configuracao_actions.php?action=pausa_excluir&id=<?php echo $pausa['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja remover esta pausa?')">Excluir</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
