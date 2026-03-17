<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - BarberPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="auth-card">
                    <span class="eyebrow">Comece agora</span>
                    <h1>Criar conta</h1>
                    <p class="auth-copy">Cadastre sua barbearia, crie o usuario administrador e receba servicos iniciais para operar rapidamente.</p>

                    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'senhas') { ?>
                        <div class="alert alert-danger">As senhas nao coincidem.</div>
                    <?php } ?>

                    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'email') { ?>
                        <div class="alert alert-danger">Este email ja esta cadastrado.</div>
                    <?php } ?>

                    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'email_invalido') { ?>
                        <div class="alert alert-danger">Informe um email valido.</div>
                    <?php } ?>

                    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'campos') { ?>
                        <div class="alert alert-danger">Preencha todos os campos.</div>
                    <?php } ?>

                    <form action="../../controllers/auth/register.php" method="POST" class="auth-form">
                        <div class="mb-3">
                            <label class="form-label">Nome do responsavel</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nome da barbearia</label>
                            <input type="text" name="nome_barbearia" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirmar senha</label>
                            <input type="password" name="confirmar_senha" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-brand w-100">Cadastrar</button>
                        <a href="login.php" class="btn btn-outline-light w-100 mt-3">Ja tenho conta</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
