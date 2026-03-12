<?php
session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: auth/login.php");
    exit;
}

require_once("../../config/database.php");

$sqlClientes = "SELECT COUNT(*) as total FROM clientes";
$resultClientes = mysqli_query($conn,$sqlClientes);
$clientes = mysqli_fetch_assoc($resultClientes);

$sqlServicos = "SELECT COUNT(*) as total FROM servicos";
$resultServicos = mysqli_query($conn,$sqlServicos);
$servicos = mysqli_fetch_assoc($resultServicos);

?>

<!DOCTYPE html>
<html lang="pt-br">

    <head>

        <meta charset="UTF-8">
        <title>Dashboard Barbearia</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/style.css">

    </head>

    <body>
        <div class="wrapper">
            <!-- SIDEBAR -->

            <div class="sidebar">
                <div class="menu-top">

                    <h3>💈 Barbearia</h3>

                    <a href="../dashboard.php">Dashboard</a>
                    <a href="index.php">Clientes</a>
                    <a href="../servicos/index.php">Serviços</a>

                </div>
                <div class="menu-bottom">

                    <a href="../../controllers/auth/logout.php" class="logout-btn">
                        Sair
                    </a>

                </div>
            </div>
            <!-- MAIN CONTENT -->

            <div class="main">
                <h2 class="mb-4">Dashboard</h2>
                <div class="row g-4">
                    <!-- CLIENTES -->

                    <div class="col-md-4">
                        <div class="card-dashboard shadow">

                            <h2><?php echo $clientes['total']; ?></h2>

                            <p>Total de Clientes</p>

                            <a href="index.php" class="btn btn-warning">
                            Gerenciar
                            </a>
                        </div>
                    </div>
                    <!-- SERVIÇOS -->

                    <div class="col-md-4">
                        <div class="card-dashboard shadow">

                            <h2><?php echo $servicos['total']; ?></h2>

                            <p>Serviços Disponíveis</p>

                            <a href="servicos/index.php" class="btn btn-warning">
                            Gerenciar
                            </a>
                        </div>
                    </div>
                    <!-- NOVO CLIENTE -->

                    <div class="col-md-4">
                        <div class="card-dashboard shadow">
                            <h2>+</h2>
                            <p>Novo Cliente</p>
                            <a href="cadastrar.php" class="btn btn-warning">
                            Cadastrar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>