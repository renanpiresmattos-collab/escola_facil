<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url());
    }
}
