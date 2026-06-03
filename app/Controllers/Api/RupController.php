<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RupController extends BaseController
{
    private $rupService;

    public function __construct()
    {
        helper('response_helper');
        $this->rupService = service('rupService');
    }

    public function index(): ResponseInterface
    {
        return api_service_response(
            $this->response,
            fn () => $this->rupService->listar([
                'q' => (string) $this->request->getGet('q'),
                'field' => (string) $this->request->getGet('field'),
                'page' => (int) $this->request->getGet('page'),
                'per_page' => (int) $this->request->getGet('per_page'),
            ])
        );
    }

    public function show(int $id): ResponseInterface
    {
        return api_service_response(
            $this->response, 
            fn () => $this->rupService->findById($id)
        );
    }

    public function store(): ResponseInterface
    {
        return api_service_response(
            $this->response, 
            fn () => $this->rupService->salvar($this->request->getJSON(true) ?? $this->request->getPost())
        );
    }

    public function update(int $id): ResponseInterface
    {
        return api_service_response(
            $this->response, 
            fn () => $this->rupService->salvar($this->request->getJSON(true) ?? $this->request->getPost(), $id)
        );
    }
}
