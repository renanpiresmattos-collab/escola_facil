<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\AuthService;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {   
        helper('response_helper');
        $this->authService = service('authService');
    }

    public function login(): ResponseInterface
    {      
        return api_service_response(
            $this->response, 
            fn () => $this->authService->login($this->request)
        );
    }

    public function logout(): ResponseInterface
    {
        return api_service_response(
            $this->response, 
            fn () => $this->authService->logout()
        );
    }
}
