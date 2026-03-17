<?php

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../controllers/ServicoController.php';

requireRoles(['admin', 'recepcao']);

$controller = new ServicoController();
$servicos = $controller->listar();
$servicoEdicao = null;

if (isset($_GET['editar'])) {
    $servicoEdicao = $controller->buscarPorId((int) $_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Servicos - BarberPro</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="app-shell">
    <div class="page-container">
        <div class="page-header">
            <div>
                <span class="eyebrow">Catalogo</span>
                <h1>Servicos</h1>
                <p class="section-copy">Defina o menu de servicos e os precos da sua barbearia.</p>
            </div>
            <a href="../../dashboard.php" class="btn btn-outline-light">Dashboard</a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'cadastrado') { ?>
            <div class="alert alert-success">Servico cadastrado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'atualizado') { ?>
            <div class="alert alert-primary">Servico atualizado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'excluido') { ?>
            <div class="alert alert-danger">Servico excluido com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'erro') { ?>
            <div class="alert alert-warning">Revise os dados do servico e tente novamente.</div>
        <?php } ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="panel-card">
                    <span class="eyebrow"><?php echo $servicoEdicao ? 'Edicao' : 'Novo'; ?></span>
                    <h2 class="mb-4"><?php echo $servicoEdicao ? 'Editar servico' : 'Cadastrar servico'; ?></h2>

                    <form action="../../controllers/servico_actions.php?action=<?php echo $servicoEdicao ? 'atualizar' : 'salvar'; ?>" method="POST">
                        <?php if ($servicoEdicao) { ?>
                            <input type="hidden" name="id" value="<?php echo $servicoEdicao['id']; ?>">
                        <?php } ?>

                        <div class="mb-3">
                            <label class="form-label">Nome do servico</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($servicoEdicao['nome'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preco</label>
                            <input type="number" step="0.01" min="0.01" name="preco" class="form-control" value="<?php echo htmlspecialchars($servicoEdicao['preco'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Duracao em minutos</label>
                            <input type="number" min="5" name="duracao_min" class="form-control" value="<?php echo htmlspecialchars($servicoEdicao['duracao_min'] ?? ''); ?>" required>
                        </div>

                        <button type="submit" class="btn btn-brand"><?php echo $servicoEdicao ? 'Salvar alteracoes' : 'Cadastrar'; ?></button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="panel-card">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Servico</th>
                                    <th>Preco</th>
                                    <th>Duracao</th>
                                    <th class="text-end">Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($servicos as $servico) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($servico['nome']); ?></td>
                                        <td>R$ <?php echo number_format((float) $servico['preco'], 2, ',', '.'); ?></td>
                                        <td><?php echo (int) $servico['duracao_min']; ?> min</td>
                                        <td class="text-end">
                                            <a href="index.php?editar=<?php echo $servico['id']; ?>" class="btn btn-sm btn-brand-soft">Editar</a>
                                            <a href="../../controllers/servico_actions.php?action=excluir&id=<?php echo $servico['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir este servico?')">Excluir</a>
                                        </td>
                                    </tr>
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
