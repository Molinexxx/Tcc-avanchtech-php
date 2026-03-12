<?php

require_once __DIR__ . '/../config/database.php';

class Servico {

    public function listar(){

        global $conn;

        $sql = "SELECT * FROM servicos ORDER BY nome ASC";
        $result = mysqli_query($conn, $sql);

        $servicos = [];

        while($row = mysqli_fetch_assoc($result)){
            $servicos[] = $row;
        }

        return $servicos;
    }
}