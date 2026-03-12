<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/cliente.php';

class ClienteController {

    private $cliente;

    public function __construct(){
        $this->cliente = new Cliente();
    }

    public function listar(){
        $barbearia_id = $_SESSION['barbearia_id'];
        return $this->cliente->listar($barbearia_id);
    }

    public function salvar($nome, $telefone, $servico){
        $barbearia_id = $_SESSION['barbearia_id'];
        return $this->cliente->cadastrar($nome, $telefone, $servico, $barbearia_id);
    }

    public function excluir($id){
        return $this->cliente->excluir($id);
    }

    public function atualizar($id, $nome, $telefone, $servico){
        return $this->cliente->atualizar($id, $nome, $telefone, $servico);
    }
}