<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class UserController extends Controller
{
    public function login()
    {
        $validation = \Config\Services::validation();

        return view('user/login', [
            'validation' => $validation
        ]);
    }
}

