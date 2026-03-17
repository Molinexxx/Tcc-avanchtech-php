<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/cliente.php';

class ClienteController
{
    private Cliente $cliente;

    public function __construct()
    {
        $this->cliente = new Cliente();
    }

    public function listar()
    {
        return $this->cliente->listar((int) $_SESSION['barbearia_id']);
    }

    public function salvar($nome, $telefone, $servico)
    {
        return $this->cliente->cadastrar($nome, $telefone, $servico, (int) $_SESSION['barbearia_id']);
    }

    public function excluir($id)
    {
        return $this->cliente->excluir($id, (int) $_SESSION['barbearia_id']);
    }

    public function atualizar($id, $nome, $telefone, $servico)
    {
        return $this->cliente->atualizar($id, $nome, $telefone, $servico, (int) $_SESSION['barbearia_id']);
    }

    public function buscarPorId($id)
    {
        return $this->cliente->buscarPorId($id, (int) $_SESSION['barbearia_id']);
    }
}
