<?php

require_once __DIR__ . '/../config/database.php';

class Pausa
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar($barbeariaId)
    {
        $sql = "SELECT p.id, p.usuario_id, p.data_inicio, p.data_fim, p.motivo, u.nome AS profissional
                FROM pausas_usuarios p
                INNER JOIN usuarios u ON u.id = p.usuario_id
                WHERE p.barbearia_id = ?
                ORDER BY p.data_inicio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);

        return $stmt->fetchAll();
    }

    public function cadastrar($barbeariaId, $usuarioId, $dataInicio, $dataFim, $motivo)
    {
        $sql = "INSERT INTO pausas_usuarios (barbearia_id, usuario_id, data_inicio, data_fim, motivo)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$barbeariaId, $usuarioId, $dataInicio, $dataFim, $motivo]);
    }

    public function excluir($id, $barbeariaId)
    {
        $sql = "DELETE FROM pausas_usuarios WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id, $barbeariaId]);
    }

    public function existePausaNoPeriodo($barbeariaId, $usuarioId, $inicio, $fim): bool
    {
        $sql = "SELECT id
                FROM pausas_usuarios
                WHERE barbearia_id = ?
                  AND usuario_id = ?
                  AND data_inicio < ?
                  AND data_fim > ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId, $usuarioId, $fim, $inicio]);

        return (bool) $stmt->fetch();
    }
}
