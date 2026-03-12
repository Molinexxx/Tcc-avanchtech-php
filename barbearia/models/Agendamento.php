<?php

require_once __DIR__ . '/../config/database.php';

class Agendamento
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function countHoje($barbeariaId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM agendamentos
                WHERE barbearia_id = :barbearia_id
                  AND DATE(data_hora) = CURDATE()";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public function countPorStatusHoje($barbeariaId, $status)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM agendamentos
                WHERE barbearia_id = :barbearia_id
                  AND status = :status
                  AND DATE(data_hora) = CURDATE()";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public function listarProximos($barbeariaId)
    {
        $sql = "SELECT
                    a.id,
                    a.data_hora,
                    a.status,
                    c.nome AS cliente,
                    c.telefone,
                    s.nome AS servico,
                    s.preco,
                    u.nome AS barbeiro
                FROM agendamentos a
                INNER JOIN clientes c ON a.cliente_id = c.id
                INNER JOIN servicos s ON a.servico_id = s.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.barbearia_id = :barbearia_id
                  AND a.data_hora >= NOW()
                ORDER BY a.data_hora ASC
                LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}