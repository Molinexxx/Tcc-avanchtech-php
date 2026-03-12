<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Login - Barbearia</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/style.css">
    </head>

    <body class="bg-dark">
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-4">
                    <div class="card p-4">
                        <?php if(isset($_GET['sucesso'])){ ?>
                            <div class="alert alert-success">
                                Conta criada com sucesso. Faça login.
                            </div>
                        <?php } ?>
                        <?php if(isset($_GET['erro'])){ ?>
                            <div class="alert alert-danger">
                                Email ou senha inválidos.
                            </div>
                        <?php } ?>
                        <h3 class="text-center mb-4">💈 Login</h3>
                        <form action="../../controllers/auth/login.php" method="POST">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Senha</label>
                                <input type="password" name="senha" class="form-control" required>
                            </div>

                            <button class="btn btn-warning w-100">
                            Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>