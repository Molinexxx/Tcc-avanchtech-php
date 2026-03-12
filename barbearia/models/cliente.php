<?php

require_once __DIR__ . '/../config/database.php';

class Cliente {

    public function listar(){

        global $conn;

        $sql = "SELECT * FROM clientes";

        $result = $conn->query($sql);

        $clientes = [];

        while($row = $result->fetch_assoc()){
            $clientes[] = $row;
        }

        return $clientes;
    }

   public function buscarPorId($id){

        global $conn;

        $sql = "SELECT * FROM clientes WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function cadastrar($nome, $telefone, $servico){
         global $conn;

        $sql = "INSERT INTO clientes (nome, telefone, servico)
            VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sss", $nome, $telefone, $servico);

        return $stmt->execute();
    }

    public function atualizar($id, $nome, $telefone, $servico){

        global $conn;

        $sql = "UPDATE clientes
                SET nome=?, telefone=?, servico=?
                WHERE id=?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sssi", $nome, $telefone, $servico, $id);

        return $stmt->execute();
    }

    public function excluir($id){

        global $conn;

        $sql = "DELETE FROM clientes WHERE id=?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}