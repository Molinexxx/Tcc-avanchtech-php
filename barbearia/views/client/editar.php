<?php

require_once("../../models/Cliente.php");

$model = new Cliente();
$cliente = $model->buscarPorId($_GET['id']);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
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
                                <input type="text" name="nome" class="form-control" value="<?php echo $cliente['nome']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" name="telefone" class="form-control" value="<?php echo $cliente['telefone']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Serviço</label>
                                <input type="text" name="servico" class="form-control" value="<?php echo $cliente['servico']; ?>" required>
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