<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class MenuController extends BaseController
{
    public function index(): ResponseInterface
    {
        $menu = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard'),
            ],
            [
                'title' => 'Cadastro',
                'submenu' => [
                    ['title' => 'RUP', 'url' => site_url('rup')],
                    ['title' => 'Alunos', 'url' => site_url('alunos')],
                ],
            ],
            [
                'title' => 'Configurações',
                'submenu' => [
                    ['title' => 'Geral', 'url' => site_url('config')],
                ],
            ],
        ];

        return $this->response->setJSON(['data' => $menu]);
    }
}
