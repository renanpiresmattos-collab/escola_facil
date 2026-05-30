<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('login');
    }
}
