<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Configuracao.php';
require_once __DIR__ . '/../models/Pausa.php';
require_once __DIR__ . '/../models/Usuario.php';

class ConfiguracaoController
{
    private Configuracao $configuracao;
    private Pausa $pausa;
    private Usuario $usuario;

    public function __construct()
    {
        $this->configuracao = new Configuracao();
        $this->pausa = new Pausa();
        $this->usuario = new Usuario();
    }

    public function listarHorarios()
    {
        return $this->configuracao->listarHorarios((int) $_SESSION['barbearia_id']);
    }

    public function salvarHorario($diaSemana, $aberto, $horaInicio, $horaFim)
    {
        return $this->configuracao->salvarHorario((int) $_SESSION['barbearia_id'], $diaSemana, $aberto, $horaInicio, $horaFim);
    }

    public function listarPausas()
    {
        return $this->pausa->listar((int) $_SESSION['barbearia_id']);
    }

    public function salvarPausa($usuarioId, $dataInicio, $dataFim, $motivo)
    {
        return $this->pausa->cadastrar((int) $_SESSION['barbearia_id'], $usuarioId, $dataInicio, $dataFim, $motivo);
    }

    public function excluirPausa($id)
    {
        return $this->pausa->excluir($id, (int) $_SESSION['barbearia_id']);
    }

    public function listarProfissionais()
    {
        return $this->usuario->listarBarbeiros((int) $_SESSION['barbearia_id']);
    }
}
