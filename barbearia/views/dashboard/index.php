<?php
$userName = $userName ?? 'Usuário';
$totalHoje = $totalHoje ?? 0;
$confirmadosHoje = $confirmadosHoje ?? 0;
$pendentesHoje = $pendentesHoje ?? 0;
$concluidosHoje = $concluidosHoje ?? 0;
$canceladosHoje = $canceladosHoje ?? 0;
$proximosAgendamentos = $proximosAgendamentos ?? [];

function formatarStatusClasse($status)
{
    return match ($status) {
        'confirmado' => 'badge-confirmado',
        'pendente'   => 'badge-pendente',
        'concluido'  => 'badge-concluido',
        'cancelado'  => 'badge-cancelado',
        default      => 'badge-default'
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
                <p class="subtitle">Painel da barbearia</p>

                <nav class="menu">
                    <a href="/barbearia/dashboard.php" class="menu-item active">Dashboard</a>
                    <a href="#" class="menu-item">Agenda</a>
                    <a href="/barbearia/views/client/index.php" class="menu-item">Clientes</a>
                    <a href="#" class="menu-item">Serviços</a>
                    <a href="#" class="menu-item">Usuários</a>
                </nav>
            </div>

            <a href="/barbearia/controllers/auth/logout.php" class="logout-btn">Sair</a>
        </aside>

        <main class="content">
            <header class="header">
                <div>
                    <h1>Dashboard da Agenda</h1>
                    <p>Bem-vindo, <?= htmlspecialchars($userName) ?></p>
                </div>

                <a href="#" class="primary-btn">+ Novo agendamento</a>
            </header>

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
                    <span>Concluídos</span>
                    <strong><?= $concluidosHoje ?></strong>
                </div>

                <div class="stat-card">
                    <span>Cancelados</span>
                    <strong><?= $canceladosHoje ?></strong>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header">
                    <h2>Próximos agendamentos</h2>
                    <a href="#" class="link-btn">Ver todos</a>
                </div>

                <?php if (!empty($proximosAgendamentos)): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Horário</th>
                                    <th>Cliente</th>
                                    <th>Serviço</th>
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
                        <p>Nenhum agendamento encontrado.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>