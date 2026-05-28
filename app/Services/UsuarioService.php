<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;

class UsuarioService
{
    public function __construct(private ?UsuarioRepository $usuarioRepository = null)
    {
        $this->usuarioRepository ??= new UsuarioRepository();
    }

    public function listarTodos(): array
    {
        return [
            'message' => 'Usuarios listados com sucesso.',
            'data' => $this->usuarioRepository->findAll(),
        ];
    }
}
