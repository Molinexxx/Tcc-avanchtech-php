<!DOCTYPE html>
<html lang="pt-br">
    <head>

        <meta charset="UTF-8">
        <title>Cadastrar Cliente</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    </head>

    <body class="bg-dark text-light">

        <div class="container mt-5">

            <h2>Cadastrar Cliente</h2>

            <form action="../../controllers/cliente_actions.php?action=salvar" method="POST">

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Serviço</label>
                    <input type="text" name="servico" class="form-control">
                </div>

                <button type="submit" class="btn btn-warning">
                Cadastrar
                </button>

                <a href="index.php" class="btn btn-secondary">
                Voltar
                </a>

            </form>

        </div>

    </body>
</html>