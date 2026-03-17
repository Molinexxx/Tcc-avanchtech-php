<?php

require_once __DIR__ . '/../config/database.php';

class Configuracao
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarHorarios($barbeariaId)
    {
        $sql = "SELECT dia_semana, aberto, hora_inicio, hora_fim
                FROM horarios_funcionamento
                WHERE barbearia_id = ?
                ORDER BY dia_semana ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);

        return $stmt->fetchAll();
    }

    public function buscarHorarioPorDia($barbeariaId, $diaSemana)
    {
        $sql = "SELECT dia_semana, aberto, hora_inicio, hora_fim
                FROM horarios_funcionamento
                WHERE barbearia_id = ? AND dia_semana = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId, $diaSemana]);

        return $stmt->fetch();
    }

    public function salvarHorario($barbeariaId, $diaSemana, $aberto, $horaInicio, $horaFim)
    {
        $sql = "INSERT INTO horarios_funcionamento (barbearia_id, dia_semana, aberto, hora_inicio, hora_fim)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    aberto = VALUES(aberto),
                    hora_inicio = VALUES(hora_inicio),
                    hora_fim = VALUES(hora_fim)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$barbeariaId, $diaSemana, $aberto, $horaInicio, $horaFim]);
    }
}
