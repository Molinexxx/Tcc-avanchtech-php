<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Barbearia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="bg-dark">

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card p-4">

                    <h3 class="text-center mb-4">💈 Criar Conta</h3>

                    <?php if(isset($_GET['erro']) && $_GET['erro'] == 'senhas'){ ?>
                        <div class="alert alert-danger">
                            As senhas não coincidem.
                        </div>
                    <?php } ?>

                    <?php if(isset($_GET['erro']) && $_GET['erro'] == 'email'){ ?>
                        <div class="alert alert-danger">
                            Este email já está cadastrado.
                        </div>
                    <?php } ?>

                    <form action="../../controllers/auth/register.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar senha</label>
                            <input type="password" name="confirmar_senha" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            Cadastrar
                        </button>

                        <a href="login.php" class="btn btn-outline-light w-100 mt-2">
                            Já tenho conta
                        </a>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>