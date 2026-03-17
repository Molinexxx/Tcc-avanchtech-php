<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - BarberPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card">
                    <span class="eyebrow">SaaS para barbearias</span>
                    <h1>Entrar no BarberPro</h1>
                    <p class="auth-copy">Gerencie agenda, clientes, servicos e equipe da sua barbearia em um unico painel.</p>

                    <?php if (isset($_GET['sucesso'])) { ?>
                        <div class="alert alert-success">Conta criada com sucesso. Faca login.</div>
                    <?php } ?>

                    <?php if (isset($_GET['erro'])) { ?>
                        <div class="alert alert-danger">Email ou senha invalidos.</div>
                    <?php } ?>

                    <form action="../../controllers/auth/login.php" method="POST" class="auth-form">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>

                        <button class="btn btn-brand w-100">Entrar</button>
                    </form>

                    <a href="register.php" class="btn btn-outline-light w-100 mt-3">Criar conta</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
