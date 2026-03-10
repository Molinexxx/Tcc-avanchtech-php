<?php

require_once __DIR__ . '/../config/database.php';

class Cliente {

    public function listar(){

        global $conn;

        $sql = "SELECT * FROM clientes";
        $result = mysqli_query($conn, $sql);
        $clientes = [];

        while($row = mysqli_fetch_assoc($result)){
            $clientes[] = $row;
        }

        return $clientes;
    }

    public function buscarPorId($id){

        global $conn;

        $sql = "SELECT * FROM clientes WHERE id=$id";
        $result = mysqli_query($conn,$sql);

        return mysqli_fetch_assoc($result);
    }

    public function cadastrar($nome,$telefone,$servico){

        global $conn;
        $sql = "INSERT INTO clientes (nome,telefone,servico)
                VALUES ('$nome','$telefone','$servico')";

        return mysqli_query($conn,$sql);
    }

    public function atualizar($id,$nome,$telefone,$servico){

        global $conn;

        $sql = "UPDATE clientes 
                SET nome='$nome',
                    telefone='$telefone',
                    servico='$servico'
                WHERE id=$id";

        return mysqli_query($conn,$sql);
    }

    public function excluir($id){

        global $conn;

        $sql = "DELETE FROM clientes WHERE id=$id";

        return mysqli_query($conn,$sql);
    }

}