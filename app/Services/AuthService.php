<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;
use CodeIgniter\HTTP\IncomingRequest;

class AuthService
{
    public function __construct(private ?UsuarioRepository $usuarioRepository = null)
    {
        $this->usuarioRepository ??= new UsuarioRepository();
    }

    public function login(IncomingRequest $request): array
    {
        $payload = $request->getJSON(true);

        if (! is_array($payload)) {
            $payload = $request->getPost();
        }

        $email = trim((string) ($payload['email'] ?? ''));
        $password = (string) ($payload['password'] ?? '');

        if ($email === '' || $password === '') {
            throw new \InvalidArgumentException('Informe email e senha.');
        }

        if (! $this->credentialsAreValid($email, $password)) {
            throw new \DomainException('Credenciais invalidas.');
        }

        $usuario = $this->usuarioRepository->findByEmail($email);

        if ($usuario === null || ! isset($usuario['id'])) {
            throw new \OutOfBoundsException('Usuario nao encontrado.');
        }

        session()->set([
            'user_id' => $usuario['id'],
            'logged_in' => true,
        ]);

        return [
            'message' => 'Login realizado com sucesso.',
        ];
    }

    public function logout(): array
    {
        session()->destroy();

        return [
            'message' => 'Logout realizado com sucesso.',
        ];
    }

    public function credentialsAreValid(string $email, string $password): bool
    {
        $usuario = $this->usuarioRepository->findByEmail($email);

        if ($usuario === null || ! isset($usuario['senha'])) {
            return false;
        }

        return check_password_hash($password, (string) $usuario['senha']);
    }
}
