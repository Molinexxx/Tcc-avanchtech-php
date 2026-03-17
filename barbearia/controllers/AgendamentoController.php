<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Agendamento.php';
require_once __DIR__ . '/../models/cliente.php';
require_once __DIR__ . '/../models/Servico.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Configuracao.php';
require_once __DIR__ . '/../models/Pausa.php';

class AgendamentoController
{
    private Agendamento $agendamento;
    private Cliente $cliente;
    private Servico $servico;
    private Usuario $usuario;
    private Configuracao $configuracao;
    private Pausa $pausa;

    public function __construct()
    {
        $this->agendamento = new Agendamento();
        $this->cliente = new Cliente();
        $this->servico = new Servico();
        $this->usuario = new Usuario();
        $this->configuracao = new Configuracao();
        $this->pausa = new Pausa();
    }

    public function listar()
    {
        $scope = $_GET['scope'] ?? 'geral';

        if (currentUserRole() === 'barbeiro' || $scope === 'meus') {
            return $this->agendamento->listarPorUsuario((int) $_SESSION['barbearia_id'], (int) $_SESSION['user_id']);
        }

        return $this->agendamento->listarTodos((int) $_SESSION['barbearia_id']);
    }

    public function listarClientes()
    {
        return $this->cliente->listar((int) $_SESSION['barbearia_id']);
    }

    public function listarServicos()
    {
        return $this->servico->listar((int) $_SESSION['barbearia_id']);
    }

    public function listarBarbeiros()
    {
        return $this->usuario->listarBarbeiros((int) $_SESSION['barbearia_id']);
    }

    public function buscarPorId($id)
    {
        return $this->agendamento->buscarPorId($id, (int) $_SESSION['barbearia_id']);
    }

    private function validarDisponibilidade($usuarioId, $dataHoraBanco, $duracaoMin, $ignorarId = null)
    {
        $diaSemana = (int) date('w', strtotime($dataHoraBanco));
        $horario = $this->configuracao->buscarHorarioPorDia((int) $_SESSION['barbearia_id'], $diaSemana);

        if (!$horario || (int) $horario['aberto'] !== 1) {
            return 'fechado';
        }

        $horaInicioAgendamento = date('H:i:s', strtotime($dataHoraBanco));
        $horaFimAgendamento = date('H:i:s', strtotime($dataHoraBanco . " +{$duracaoMin} minutes"));
        if ($horaInicioAgendamento < $horario['hora_inicio'] || $horaFimAgendamento > $horario['hora_fim']) {
            return 'fora_expediente';
        }

        $dataFimBanco = date('Y-m-d H:i:s', strtotime($dataHoraBanco . " +{$duracaoMin} minutes"));
        if ($this->pausa->existePausaNoPeriodo((int) $_SESSION['barbearia_id'], $usuarioId, $dataHoraBanco, $dataFimBanco)) {
            return 'pausa';
        }

        if ($this->agendamento->existeConflito((int) $_SESSION['barbearia_id'], $usuarioId, $dataHoraBanco, $duracaoMin, $ignorarId)) {
            return 'conflito';
        }

        return 'ok';
    }

    public function salvar($clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes)
    {
        if (currentUserRole() === 'barbeiro') {
            $usuarioId = (int) $_SESSION['user_id'];
        }

        $servico = $this->servico->buscarPorId($servicoId, (int) $_SESSION['barbearia_id']);
        $duracaoMin = (int) ($servico['duracao_min'] ?? 0);
        if ($duracaoMin <= 0) {
            return 'erro';
        }

        $dataHoraBanco = date('Y-m-d H:i:s', strtotime($dataHora));
        $validacao = $this->validarDisponibilidade($usuarioId, $dataHoraBanco, $duracaoMin);
        if ($validacao !== 'ok') {
            return $validacao;
        }

        $salvou = $this->agendamento->cadastrar(
            (int) $_SESSION['barbearia_id'],
            $clienteId,
            $servicoId,
            $usuarioId,
            $dataHoraBanco,
            $status,
            $observacoes
        );

        return $salvou ? 'ok' : 'erro';
    }

    public function atualizar($id, $clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes)
    {
        if (currentUserRole() === 'barbeiro') {
            return 'erro';
        }

        $servico = $this->servico->buscarPorId($servicoId, (int) $_SESSION['barbearia_id']);
        $duracaoMin = (int) ($servico['duracao_min'] ?? 0);
        if ($duracaoMin <= 0) {
            return 'erro';
        }

        $dataHoraBanco = date('Y-m-d H:i:s', strtotime($dataHora));
        $validacao = $this->validarDisponibilidade($usuarioId, $dataHoraBanco, $duracaoMin, $id);
        if ($validacao !== 'ok') {
            return $validacao;
        }

        $ok = $this->agendamento->atualizar(
            $id,
            (int) $_SESSION['barbearia_id'],
            $clienteId,
            $servicoId,
            $usuarioId,
            $dataHoraBanco,
            $status,
            $observacoes
        );

        return $ok ? 'ok' : 'erro';
    }

    public function excluir($id)
    {
        if (currentUserRole() === 'barbeiro') {
            return false;
        }

        return $this->agendamento->excluir($id, (int) $_SESSION['barbearia_id']);
    }

    public function atualizarStatus($id, $status)
    {
        if (currentUserRole() === 'barbeiro') {
            return $this->agendamento->atualizarStatusPorUsuario(
                $id,
                (int) $_SESSION['barbearia_id'],
                (int) $_SESSION['user_id'],
                $status
            );
        }

        return $this->agendamento->atualizarStatus($id, (int) $_SESSION['barbearia_id'], $status);
    }
}
