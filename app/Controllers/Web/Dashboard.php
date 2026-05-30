<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
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
                ],
            ],
            [
                'title' => 'Configurações',
                'submenu' => [
                    ['title' => 'Geral', 'url' => site_url('config')],
                ],
            ],
        ];

        return view('dashboard', ['menuItems' => $menu]);
    }
}
