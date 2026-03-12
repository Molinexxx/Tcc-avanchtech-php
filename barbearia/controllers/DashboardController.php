<?php
require_once __DIR__ . '/../config/auth.php';

class DashboardController
{
    public function index()
    {
        $userName = $_SESSION['user_name'] ?? 'Usuário';

        $totalHoje = 0;
        $confirmadosHoje = 0;
        $pendentesHoje = 0;
        $concluidosHoje = 0;
        $canceladosHoje = 0;

        $proximosAgendamentos = [];

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}