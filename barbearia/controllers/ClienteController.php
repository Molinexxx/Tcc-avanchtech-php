<?php

require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {

    private $cliente;

    public function __construct(){
        $this->cliente = new Cliente();
    }

    public function listar(){
        return $this->cliente->listar();
    }

    public function salvar($nome, $telefone, $servico){
        return $this->cliente->cadastrar($nome, $telefone, $servico);
    }

    public function excluir($id){
        return $this->cliente->excluir($id);
    }

    public function atualizar($id, $nome, $telefone, $servico){
        return $this->cliente->atualizar($id, $nome, $telefone, $servico);
    }
}