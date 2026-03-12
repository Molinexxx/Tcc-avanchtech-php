<?php

require_once("../../controllers/ClienteController.php");

$controller = new ClienteController();
$clientes = $controller->listar();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Barbearia Sistema</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-dark text-light">

    <nav class="navbar navbar-dark bg-black">
        <div class="container">
            <span class="navbar-brand mb-0 h1">💈 Barbearia Sistema</span>
            <a href="cadastrar.php" class="btn btn-warning">Novo Cliente</a>
        </div>
    </nav>

    <div class="container mt-5">

        <?php if(isset($_GET['status']) && $_GET['status'] == 'cadastrado'){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Cliente cadastrado com sucesso 💈
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'excluido'){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Cliente excluído com sucesso
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'atualizado'){ ?>
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                Cliente atualizado com sucesso
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <h2 class="mb-4">Clientes</h2>

        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Serviço</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($clientes as $item){ ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo $item['nome']; ?></td>
                        <td><?php echo $item['telefone']; ?></td>
                        <td><?php echo $item['servico']; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $item['id']; ?>" class="btn btn-primary btn-sm">
                                Editar
                            </a>

                            <a href="../../controllers/cliente_actions.php?action=excluir&id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm"; onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

</body>
</html>