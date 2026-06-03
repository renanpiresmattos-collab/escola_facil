<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Rup extends BaseController
{
    public function index()
    {
        return view('rup/index', [
            'pageTitle' => 'Cadastro',
            'activeMenu' => 'Cadastro',
            'activeSubmenu' => 'RUP',
        ]);
    }

    public function create()
    {
        return view('rup/form', [
            'pageTitle' => 'Incluir Pessoa',
            'activeMenu' => 'Cadastro',
            'activeSubmenu' => 'RUP',
            'mode' => 'create',
        ]);
    }

    public function edit(int $id)
    {
        return view('rup/form', [
            'pageTitle' => 'Editar Pessoa',
            'activeMenu' => 'Cadastro',
            'activeSubmenu' => 'RUP',
            'mode' => 'edit',
            'recordId' => $id,
        ]);
    }
}
