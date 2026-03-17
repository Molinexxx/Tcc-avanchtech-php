<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Agendamento.php';
require_once __DIR__ . '/../models/cliente.php';
require_once __DIR__ . '/../models/Servico.php';
require_once __DIR__ . '/../models/Usuario.php';

class DashboardController
{
    public function index()
    {
        $barbeariaId = (int) ($_SESSION['barbearia_id'] ?? 0);
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $userName = $_SESSION['user_name'] ?? 'Usuario';
        $userRole = currentUserRole();

        $agendamentoModel = new Agendamento();
        $clienteModel = new Cliente();
        $servicoModel = new Servico();
        $usuarioModel = new Usuario();

        if ($userRole === 'barbeiro') {
            $totalHoje = $agendamentoModel->countHojePorUsuario($barbeariaId, $userId);
            $confirmadosHoje = $agendamentoModel->countPorStatusHojePorUsuario($barbeariaId, $userId, 'confirmado');
            $pendentesHoje = $agendamentoModel->countPorStatusHojePorUsuario($barbeariaId, $userId, 'pendente');
            $concluidosHoje = $agendamentoModel->countPorStatusHojePorUsuario($barbeariaId, $userId, 'concluido');
            $canceladosHoje = $agendamentoModel->countPorStatusHojePorUsuario($barbeariaId, $userId, 'cancelado');
            $proximosAgendamentos = $agendamentoModel->listarProximosPorUsuario($barbeariaId, $userId);
        } else {
            $totalHoje = $agendamentoModel->countHoje($barbeariaId);
            $confirmadosHoje = $agendamentoModel->countPorStatusHoje($barbeariaId, 'confirmado');
            $pendentesHoje = $agendamentoModel->countPorStatusHoje($barbeariaId, 'pendente');
            $concluidosHoje = $agendamentoModel->countPorStatusHoje($barbeariaId, 'concluido');
            $canceladosHoje = $agendamentoModel->countPorStatusHoje($barbeariaId, 'cancelado');
            $proximosAgendamentos = $agendamentoModel->listarProximos($barbeariaId);
        }

        $totalClientes = $clienteModel->countPorBarbearia($barbeariaId);
        $totalServicos = $servicoModel->countPorBarbearia($barbeariaId);
        $totalBarbeiros = $usuarioModel->countBarbeiros($barbeariaId);
        $dashboardTitle = $userRole === 'barbeiro' ? 'Minha agenda de hoje' : 'Dashboard da Agenda';
        $dashboardSubtitle = match ($userRole) {
            'admin' => 'Visao geral da operacao da barbearia',
            'recepcao' => 'Acompanhe clientes e horarios do dia',
            'barbeiro' => 'Seus atendimentos e proximos horarios',
            default => 'Painel da barbearia',
        };
        $dashboardCta = $userRole === 'barbeiro' ? 'Minha agenda' : '+ Novo agendamento';
        $dashboardCtaLink = '/barbearia/views/schedule/index.php' . ($userRole === 'barbeiro' ? '?scope=meus' : '');
        $showSecondaryStats = $userRole !== 'barbeiro';

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}
