<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../models/Cliente.php';
require_once __DIR__ . '/../../models/Servico.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do cliente não informado.');
}

$model = new Cliente();
$cliente = $model->buscarPorId($_GET['id']);

if (!$cliente) {
    die('Cliente não encontrado.');
}

$servicoModel = new Servico();
$servicos = $servicoModel->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-secondary text-light">
                    <div class="card-header text-center">
                        <h3>Editar Cliente 💈</h3>
                    </div>

                    <div class="card-body">
                        <form action="../../controllers/cliente_actions.php?action=atualizar" method="POST">

                            <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Serviço</label>
                                <select name="servico" class="form-control" required>
                                    <?php foreach ($servicos as $servicoItem) { ?>
                                        <option value="<?php echo htmlspecialchars($servicoItem['nome']); ?>"
                                            <?php echo ($cliente['servico'] == $servicoItem['nome']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($servicoItem['nome']); ?> - R$ <?php echo number_format($servicoItem['preco'], 2, ',', '.'); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-warning">Atualizar</button>
                                <a href="index.php" class="btn btn-light">Voltar</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>