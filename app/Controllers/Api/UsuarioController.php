<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\UsuarioService;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class UsuarioController extends BaseController
{
    private UsuarioService $usuarioService;

    public function __construct()
    {
        helper('response_helper');
        $this->usuarioService = service('usuarioService');
    }

    public function index(): ResponseInterface
    {
        return api_service_response($this->response, fn () => $this->usuarioService->listarTodos());
    }
}
