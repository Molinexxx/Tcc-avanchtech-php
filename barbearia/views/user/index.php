<?php

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../controllers/UsuarioController.php';

requireAdmin();

$controller = new UsuarioController();
$usuarios = $controller->listar();
$usuarioEdicao = null;

if (isset($_GET['editar'])) {
    $usuarioEdicao = $controller->buscarPorId((int) $_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Equipe - BarberPro</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="app-shell">
    <div class="page-container">
        <div class="page-header">
            <div>
                <span class="eyebrow">Equipe</span>
                <h1>Usuarios</h1>
                <p class="section-copy">Cadastre barbeiros, recepcionistas e administradores da sua barbearia.</p>
            </div>
            <a href="../../dashboard.php" class="btn btn-outline-light">Dashboard</a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'cadastrado') { ?>
            <div class="alert alert-success">Usuario cadastrado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'atualizado') { ?>
            <div class="alert alert-primary">Usuario atualizado com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'excluido') { ?>
            <div class="alert alert-danger">Usuario removido com sucesso.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'email') { ?>
            <div class="alert alert-warning">Ja existe um usuario com este email.</div>
        <?php } ?>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'erro') { ?>
            <div class="alert alert-warning">Revise os dados do usuario e tente novamente.</div>
        <?php } ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="panel-card">
                    <span class="eyebrow"><?php echo $usuarioEdicao ? 'Edicao' : 'Novo'; ?></span>
                    <h2 class="mb-4"><?php echo $usuarioEdicao ? 'Editar usuario' : 'Cadastrar usuario'; ?></h2>

                    <form action="../../controllers/usuario_actions.php?action=<?php echo $usuarioEdicao ? 'atualizar' : 'salvar'; ?>" method="POST">
                        <?php if ($usuarioEdicao) { ?>
                            <input type="hidden" name="id" value="<?php echo $usuarioEdicao['id']; ?>">
                        <?php } ?>

                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($usuarioEdicao['nome'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuarioEdicao['email'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Perfil</label>
                            <select name="role" class="form-control" required>
                                <?php foreach (['admin' => 'Administrador', 'barbeiro' => 'Barbeiro', 'recepcao' => 'Recepcao'] as $valor => $label) { ?>
                                    <option value="<?php echo $valor; ?>" <?php echo (($usuarioEdicao['role'] ?? 'barbeiro') === $valor) ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><?php echo $usuarioEdicao ? 'Nova senha (opcional)' : 'Senha'; ?></label>
                            <input type="password" name="senha" class="form-control" <?php echo $usuarioEdicao ? '' : 'required'; ?>>
                        </div>

                        <button type="submit" class="btn btn-brand"><?php echo $usuarioEdicao ? 'Salvar alteracoes' : 'Cadastrar'; ?></button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="panel-card">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfil</th>
                                    <th class="text-end">Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($usuario['role'])); ?></td>
                                        <td class="text-end">
                                            <a href="index.php?editar=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-brand-soft">Editar</a>
                                            <?php if ((int) $usuario['id'] !== (int) $_SESSION['user_id'] && $usuario['role'] !== 'admin') { ?>
                                                <a href="../../controllers/usuario_actions.php?action=excluir&id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja remover este usuario?')">Excluir</a>
                                            <?php } ?>
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
