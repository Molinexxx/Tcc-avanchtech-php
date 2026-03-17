<?php

$userName = $userName ?? 'Usuario';
$totalHoje = $totalHoje ?? 0;
$confirmadosHoje = $confirmadosHoje ?? 0;
$pendentesHoje = $pendentesHoje ?? 0;
$concluidosHoje = $concluidosHoje ?? 0;
$canceladosHoje = $canceladosHoje ?? 0;
$proximosAgendamentos = $proximosAgendamentos ?? [];
$totalClientes = $totalClientes ?? 0;
$totalServicos = $totalServicos ?? 0;
$totalBarbeiros = $totalBarbeiros ?? 0;
$dashboardTitle = $dashboardTitle ?? 'Dashboard da Agenda';
$dashboardSubtitle = $dashboardSubtitle ?? 'Painel operacional da barbearia';
$dashboardCta = $dashboardCta ?? '+ Novo agendamento';
$dashboardCtaLink = $dashboardCtaLink ?? '/barbearia/views/schedule/index.php';
$showSecondaryStats = $showSecondaryStats ?? true;

function formatarStatusClasse($status)
{
    return match ($status) {
        'confirmado' => 'badge-confirmado',
        'pendente' => 'badge-pendente',
        'concluido' => 'badge-concluido',
        'cancelado' => 'badge-cancelado',
        default => 'badge-default'
    };
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard da Agenda</title>
    <link rel="stylesheet" href="/barbearia/css/dashboard.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div>
                <h2 class="logo">BarberPro</h2>
                <p class="subtitle">Painel operacional da barbearia</p>

                <nav class="menu">
                    <a href="/barbearia/dashboard.php" class="menu-item active">Dashboard</a>
                    <?php if (in_array(currentUserRole(), ['admin', 'recepcao', 'barbeiro'], true)): ?>
                        <a href="/barbearia/views/schedule/index.php" class="menu-item">Agenda</a>
                    <?php endif; ?>
                    <?php if (in_array(currentUserRole(), ['admin', 'recepcao', 'barbeiro'], true)): ?>
                        <a href="/barbearia/views/client/index.php" class="menu-item">Clientes</a>
                    <?php endif; ?>
                    <?php if (in_array(currentUserRole(), ['admin', 'recepcao'], true)): ?>
                        <a href="/barbearia/views/service/index.php" class="menu-item">Servicos</a>
                    <?php endif; ?>
                    <?php if (in_array(currentUserRole(), ['admin', 'recepcao'], true)): ?>
                        <a href="/barbearia/views/settings/index.php" class="menu-item">Disponibilidade</a>
                    <?php endif; ?>
                    <?php if (currentUserRole() === 'admin'): ?>
                        <a href="/barbearia/views/user/index.php" class="menu-item">Usuarios</a>
                    <?php endif; ?>
                </nav>
            </div>

            <a href="/barbearia/controllers/auth/logout.php" class="logout-btn">Sair</a>
        </aside>

        <main class="content">
            <header class="header">
                <div>
                    <h1><?= htmlspecialchars($dashboardTitle) ?></h1>
                    <p><?= htmlspecialchars($dashboardSubtitle) ?>. Bem-vindo, <?= htmlspecialchars($userName) ?></p>
                </div>

                <a href="<?= htmlspecialchars($dashboardCtaLink) ?>" class="primary-btn"><?= htmlspecialchars($dashboardCta) ?></a>
            </header>

            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'acesso'): ?>
                <section class="panel" style="margin-bottom: 24px;">
                    <p class="empty-state">Voce nao tem permissao para acessar essa area.</p>
                </section>
            <?php endif; ?>

            <section class="stats">
                <div class="stat-card">
                    <span>Agendamentos hoje</span>
                    <strong><?= $totalHoje ?></strong>
                </div>

                <div class="stat-card">
                    <span>Confirmados</span>
                    <strong><?= $confirmadosHoje ?></strong>
                </div>

                <div class="stat-card">
                    <span>Pendentes</span>
                    <strong><?= $pendentesHoje ?></strong>
                </div>

                <div class="stat-card">
                    <span>Concluidos</span>
                    <strong><?= $concluidosHoje ?></strong>
                </div>

                <div class="stat-card">
                    <span>Cancelados</span>
                    <strong><?= $canceladosHoje ?></strong>
                </div>
            </section>

            <?php if ($showSecondaryStats): ?>
                <section class="stats stats-secondary">
                    <div class="stat-card soft-card">
                        <span>Clientes cadastrados</span>
                        <strong><?= $totalClientes ?></strong>
                    </div>

                    <div class="stat-card soft-card">
                        <span>Servicos ativos</span>
                        <strong><?= $totalServicos ?></strong>
                    </div>

                    <div class="stat-card soft-card">
                        <span>Profissionais</span>
                        <strong><?= $totalBarbeiros ?></strong>
                    </div>
                </section>
            <?php endif; ?>

            <section class="panel">
                <div class="panel-header">
                    <h2><?= currentUserRole() === 'barbeiro' ? 'Seus proximos agendamentos' : 'Proximos agendamentos' ?></h2>
                    <a href="/barbearia/views/schedule/index.php<?= currentUserRole() === 'barbeiro' ? '?scope=meus' : '' ?>" class="link-btn">Ver todos</a>
                </div>

                <?php if (!empty($proximosAgendamentos)): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Horario</th>
                                    <th>Cliente</th>
                                    <th>Servico</th>
                                    <th>Barbeiro</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proximosAgendamentos as $agendamento): ?>
                                    <tr>
                                        <td><?= date('d/m H:i', strtotime($agendamento['data_hora'])) ?></td>
                                        <td><?= htmlspecialchars($agendamento['cliente']) ?></td>
                                        <td><?= htmlspecialchars($agendamento['servico']) ?></td>
                                        <td><?= htmlspecialchars($agendamento['barbeiro']) ?></td>
                                        <td>
                                            <span class="badge <?= formatarStatusClasse($agendamento['status']) ?>">
                                                <?= ucfirst($agendamento['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Nenhum agendamento encontrado. Cadastre o primeiro para comecar a operar.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
