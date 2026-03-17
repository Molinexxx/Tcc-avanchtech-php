<?php

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

requireRoles(['admin', 'recepcao', 'barbeiro']);

$controller = new ClienteController();
$clientes = $controller->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Clientes - BarberPro</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="app-shell">
    <div class="page-container">
        <div class="page-header">
            <div>
                <span class="eyebrow">CRM</span>
                <h1>Clientes</h1>
                <p class="section-copy">Gerencie a base de clientes da sua barbearia.</p>
            </div>
            <div class="header-actions">
                <a href="../../dashboard.php" class="btn btn-outline-light">Dashboard</a>
                <?php if (in_array(currentUserRole(), ['admin', 'recepcao'], true)) { ?>
                    <a href="cadastrar.php" class="btn btn-brand">Novo cliente</a>
                <?php } ?>
            </div>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'cadastrado') { ?>
            <div class="alert alert-success">Cliente cadastrado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'excluido') { ?>
            <div class="alert alert-danger">Cliente excluido com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'atualizado') { ?>
            <div class="alert alert-primary">Cliente atualizado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'erro') { ?>
            <div class="alert alert-warning">Revise os dados informados e tente novamente.</div>
        <?php } ?>

        <div class="panel-card">
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Servico</th>
                            <th class="text-end">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $item) { ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td><?php echo htmlspecialchars($item['telefone']); ?></td>
                                <td><?php echo htmlspecialchars($item['servico']); ?></td>
                                <td class="text-end">
                                    <?php if (in_array(currentUserRole(), ['admin', 'recepcao'], true)) { ?>
                                        <a href="editar.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-brand-soft">Editar</a>
                                        <a href="../../controllers/cliente_actions.php?action=excluir&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
                                    <?php } ?>
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
