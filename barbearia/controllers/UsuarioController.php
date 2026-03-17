<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function listar()
    {
        return $this->usuario->listarTodos((int) $_SESSION['barbearia_id']);
    }

    public function buscarPorId($id)
    {
        return $this->usuario->buscarPorId($id, (int) $_SESSION['barbearia_id']);
    }

    public function emailExiste($email, $ignorarId = null): bool
    {
        return $this->usuario->emailExiste($email, (int) $_SESSION['barbearia_id'], $ignorarId);
    }

    public function salvar($nome, $email, $senha, $role)
    {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        return $this->usuario->cadastrar((int) $_SESSION['barbearia_id'], $nome, $email, $senhaHash, $role);
    }

    public function atualizar($id, $nome, $email, $role, $senha = null)
    {
        $senhaHash = null;

        if ($senha !== null && $senha !== '') {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        }

        return $this->usuario->atualizar($id, (int) $_SESSION['barbearia_id'], $nome, $email, $role, $senhaHash);
    }

    public function excluir($id)
    {
        return $this->usuario->excluir($id, (int) $_SESSION['barbearia_id']);
    }
}
