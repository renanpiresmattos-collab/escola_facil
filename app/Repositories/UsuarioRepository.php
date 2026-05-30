<?php

namespace App\Repositories;

use App\Models\UsuarioModel;

class UsuarioRepository
{
    public function __construct(private ?UsuarioModel $usuarioModel = null)
    {
        $this->usuarioModel ??= new UsuarioModel();

        helper('password');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        $usuario = $this->usuarioModel
            ->select('id, cpf, apelido, email, senha, status, nivel')
            ->where('email', $email)
            ->first();

        return is_array($usuario) ? $usuario : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->usuarioModel
            ->select('id, cpf, apelido, email, status, nivel')
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
