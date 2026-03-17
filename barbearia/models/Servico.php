<?php

require_once __DIR__ . '/../config/database.php';

class Servico
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar($barbeariaId)
    {
        $sql = "SELECT * FROM servicos WHERE barbearia_id = ? ORDER BY nome ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);

        return $stmt->fetchAll();
    }

    public function cadastrar($barbeariaId, $nome, $preco, $duracaoMin)
    {
        $sql = "INSERT INTO servicos (barbearia_id, nome, preco, duracao_min)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$barbeariaId, $nome, $preco, $duracaoMin]);
    }

    public function atualizar($id, $barbeariaId, $nome, $preco, $duracaoMin)
    {
        $sql = "UPDATE servicos
                SET nome = ?, preco = ?, duracao_min = ?
                WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$nome, $preco, $duracaoMin, $id, $barbeariaId]);
    }

    public function excluir($id, $barbeariaId)
    {
        $sql = "DELETE FROM servicos WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id, $barbeariaId]);
    }

    public function buscarPorId($id, $barbeariaId)
    {
        $sql = "SELECT * FROM servicos WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id, $barbeariaId]);

        return $stmt->fetch();
    }

    public function countPorBarbearia($barbeariaId): int
    {
        $sql = "SELECT COUNT(*) AS total FROM servicos WHERE barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);
        $result = $stmt->fetch();

        return (int) ($result['total'] ?? 0);
    }
}
