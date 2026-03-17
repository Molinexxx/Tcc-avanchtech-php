<?php

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../controllers/AgendamentoController.php';

requireRoles(['admin', 'recepcao', 'barbeiro']);

$controller = new AgendamentoController();
$agendamentos = $controller->listar();
$clientes = $controller->listarClientes();
$servicos = $controller->listarServicos();
$barbeiros = $controller->listarBarbeiros();
$scope = $_GET['scope'] ?? (currentUserRole() === 'barbeiro' ? 'meus' : 'geral');
$agendamentoEdicao = null;

if (isset($_GET['editar']) && currentUserRole() !== 'barbeiro') {
    $agendamentoEdicao = $controller->buscarPorId((int) $_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agenda - BarberPro</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="app-shell">
    <div class="page-container">
        <div class="page-header">
            <div>
                <span class="eyebrow">Operacao</span>
                <h1>Agenda</h1>
                <p class="section-copy">
                    <?php echo currentUserRole() === 'barbeiro'
                        ? 'Acompanhe seus atendimentos e atualize o status de cada horario.'
                        : 'Cadastre atendimentos e acompanhe o status de cada horario.'; ?>
                </p>
            </div>
            <div class="header-actions">
                <a href="../../dashboard.php" class="btn btn-outline-light">Dashboard</a>
                <?php if (in_array(currentUserRole(), ['admin', 'recepcao'], true)) { ?>
                    <a href="../client/cadastrar.php" class="btn btn-outline-light">Novo cliente</a>
                <?php } ?>
            </div>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'cadastrado') { ?>
            <div class="alert alert-success">Agendamento cadastrado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'atualizado') { ?>
            <div class="alert alert-primary">Agendamento atualizado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'excluido') { ?>
            <div class="alert alert-danger">Agendamento removido com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'conflito') { ?>
            <div class="alert alert-danger">Ja existe outro atendimento neste horario para o profissional selecionado.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'fechado') { ?>
            <div class="alert alert-warning">A barbearia esta fechada nesse dia.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'fora_expediente') { ?>
            <div class="alert alert-warning">O horario informado esta fora do expediente da barbearia.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'pausa') { ?>
            <div class="alert alert-warning">O profissional esta em pausa nesse periodo.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'erro') { ?>
            <div class="alert alert-warning">Revise os dados do agendamento e tente novamente.</div>
        <?php } ?>

        <?php if (currentUserRole() !== 'barbeiro') { ?>
            <div class="header-actions" style="margin-bottom: 24px;">
                <a href="index.php?scope=geral" class="btn <?php echo $scope === 'geral' ? 'btn-brand' : 'btn-outline-light'; ?>">Agenda geral</a>
                <a href="index.php?scope=meus" class="btn <?php echo $scope === 'meus' ? 'btn-brand' : 'btn-outline-light'; ?>">Minha agenda</a>
            </div>
        <?php } ?>

        <div class="row g-4">
            <div class="col-xl-4">
                <div class="panel-card">
                    <span class="eyebrow"><?php echo currentUserRole() === 'barbeiro' ? 'Meu horario' : 'Novo horario'; ?></span>
                    <h2 class="mb-4">
                        <?php
                        if (currentUserRole() === 'barbeiro') {
                            echo 'Registrar atendimento';
                        } else {
                            echo $agendamentoEdicao ? 'Editar agendamento' : 'Cadastrar agendamento';
                        }
                        ?>
                    </h2>

                    <?php if (empty($clientes) || empty($servicos) || empty($barbeiros)) { ?>
                        <div class="alert alert-warning mb-0">
                            Cadastre pelo menos um cliente, um servico e um profissional para criar agendamentos.
                        </div>
                    <?php } else { ?>
                        <form action="../../controllers/agendamento_actions.php?action=<?php echo $agendamentoEdicao ? 'atualizar' : 'salvar'; ?>" method="POST">
                            <input type="hidden" name="scope" value="<?php echo htmlspecialchars($scope); ?>">
                            <?php if ($agendamentoEdicao) { ?>
                                <input type="hidden" name="id" value="<?php echo (int) $agendamentoEdicao['id']; ?>">
                            <?php } ?>

                            <div class="mb-3">
                                <label class="form-label">Cliente</label>
                                <select name="cliente_id" class="form-control" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($clientes as $cliente) { ?>
                                        <option value="<?php echo $cliente['id']; ?>" <?php echo ((int) ($agendamentoEdicao['cliente_id'] ?? 0) === (int) $cliente['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cliente['nome']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Servico</label>
                                <select name="servico_id" class="form-control" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($servicos as $servico) { ?>
                                        <option value="<?php echo $servico['id']; ?>" <?php echo ((int) ($agendamentoEdicao['servico_id'] ?? 0) === (int) $servico['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($servico['nome']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Profissional</label>
                                <?php if (currentUserRole() === 'barbeiro') { ?>
                                    <input type="hidden" name="usuario_id" value="<?php echo (int) $_SESSION['user_id']; ?>">
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" disabled>
                                <?php } else { ?>
                                    <select name="usuario_id" class="form-control" required>
                                        <option value="">Selecione</option>
                                        <?php foreach ($barbeiros as $barbeiro) { ?>
                                            <option value="<?php echo $barbeiro['id']; ?>" <?php echo ((int) ($agendamentoEdicao['usuario_id'] ?? 0) === (int) $barbeiro['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($barbeiro['nome']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Data e hora</label>
                                <input type="datetime-local" name="data_hora" class="form-control" value="<?php echo !empty($agendamentoEdicao['data_hora']) ? date('Y-m-d\TH:i', strtotime($agendamentoEdicao['data_hora'])) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status inicial</label>
                                <select name="status" class="form-control" required>
                                    <?php foreach (['pendente', 'confirmado', 'concluido', 'cancelado'] as $statusForm) { ?>
                                        <option value="<?php echo $statusForm; ?>" <?php echo (($agendamentoEdicao['status'] ?? 'pendente') === $statusForm) ? 'selected' : ''; ?>>
                                            <?php echo ucfirst($statusForm); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Observacoes</label>
                                <textarea name="observacoes" class="form-control" rows="4"><?php echo htmlspecialchars($agendamentoEdicao['observacoes'] ?? ''); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-brand"><?php echo $agendamentoEdicao ? 'Salvar alteracoes' : 'Salvar agendamento'; ?></button>
                            <?php if ($agendamentoEdicao) { ?>
                                <a href="index.php?scope=<?php echo urlencode($scope); ?>" class="btn btn-outline-light">Cancelar edicao</a>
                            <?php } ?>
                        </form>
                    <?php } ?>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="panel-card">
                    <div class="page-header" style="margin-bottom: 18px;">
                        <div>
                            <span class="eyebrow"><?php echo $scope === 'meus' ? 'Filtro ativo' : 'Visao geral'; ?></span>
                            <h2 style="margin: 0;"><?php echo $scope === 'meus' ? 'Meus agendamentos' : 'Agenda completa'; ?></h2>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Servico</th>
                                    <th>Profissional</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agendamentos as $agendamento) { ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i', strtotime($agendamento['data_hora'])); ?></td>
                                        <td><?php echo htmlspecialchars($agendamento['cliente']); ?></td>
                                        <td><?php echo htmlspecialchars($agendamento['servico']); ?></td>
                                        <td><?php echo htmlspecialchars($agendamento['barbeiro']); ?></td>
                                        <td>
                                            <form action="../../controllers/agendamento_actions.php?action=status" method="POST" class="d-flex gap-2">
                                                <input type="hidden" name="id" value="<?php echo $agendamento['id']; ?>">
                                                <input type="hidden" name="scope" value="<?php echo htmlspecialchars($scope); ?>">
                                                <select name="status" class="form-control form-control-sm">
                                                    <?php foreach (['pendente', 'confirmado', 'concluido', 'cancelado'] as $status) { ?>
                                                        <option value="<?php echo $status; ?>" <?php echo $agendamento['status'] === $status ? 'selected' : ''; ?>>
                                                            <?php echo ucfirst($status); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <button class="btn btn-sm btn-brand-soft" type="submit">Salvar</button>
                                            </form>

                                            <?php if (currentUserRole() !== 'barbeiro') { ?>
                                                <div class="d-flex gap-2 mt-2">
                                                    <a href="index.php?scope=<?php echo urlencode($scope); ?>&editar=<?php echo $agendamento['id']; ?>" class="btn btn-sm btn-brand-soft">Editar</a>
                                                    <a href="../../controllers/agendamento_actions.php?action=excluir&id=<?php echo $agendamento['id']; ?>&scope=<?php echo urlencode($scope); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir este agendamento?')">Excluir</a>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php if (!empty($agendamento['observacoes'])) { ?>
                                        <tr>
                                            <td colspan="5" class="text-muted">Obs.: <?php echo htmlspecialchars($agendamento['observacoes']); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
