<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Servico.php';

class ServicoController
{
    private Servico $servico;

    public function __construct()
    {
        $this->servico = new Servico();
    }

    public function listar()
    {
        return $this->servico->listar((int) $_SESSION['barbearia_id']);
    }

    public function salvar($nome, $preco, $duracaoMin)
    {
        return $this->servico->cadastrar((int) $_SESSION['barbearia_id'], $nome, $preco, $duracaoMin);
    }

    public function atualizar($id, $nome, $preco, $duracaoMin)
    {
        return $this->servico->atualizar($id, (int) $_SESSION['barbearia_id'], $nome, $preco, $duracaoMin);
    }

    public function excluir($id)
    {
        return $this->servico->excluir($id, (int) $_SESSION['barbearia_id']);
    }

    public function buscarPorId($id)
    {
        return $this->servico->buscarPorId($id, (int) $_SESSION['barbearia_id']);
    }
}
