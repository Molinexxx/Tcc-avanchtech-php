<?php

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../models/Servico.php';

requireRoles(['admin', 'recepcao']);

$servicoModel = new Servico();
$servicos = $servicoModel->listar((int) $_SESSION['barbearia_id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="app-shell">
    <div class="page-container page-narrow">
        <div class="page-header">
            <div>
                <span class="eyebrow">Clientes</span>
                <h1>Novo cliente</h1>
            </div>
            <a href="index.php" class="btn btn-outline-light">Voltar</a>
        </div>

        <div class="panel-card">
            <form action="../../controllers/cliente_actions.php?action=salvar" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Servico preferido</label>
                    <select name="servico" class="form-control" required>
                        <option value="">Selecione um servico</option>
                        <?php foreach ($servicos as $servicoItem) { ?>
                            <option value="<?php echo htmlspecialchars($servicoItem['nome']); ?>">
                                <?php echo htmlspecialchars($servicoItem['nome']); ?> - R$ <?php echo number_format((float) $servicoItem['preco'], 2, ',', '.'); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-brand">Cadastrar</button>
            </form>
        </div>
    </div>
</body>
</html>
