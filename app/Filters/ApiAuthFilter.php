<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica se existe um cookie de sessão válido antes de inicializar a sessão
        $cookieName = config('Session')->cookieName;
        $sessionCookie = $_COOKIE[$cookieName] ?? null;
        
        if (!$sessionCookie || !session()->get('logged_in')) {
            $response = service('response');
            
            return $response
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Acesso negado. Faça login primeiro.',
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}