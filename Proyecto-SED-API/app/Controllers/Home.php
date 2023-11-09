<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }

    public function register(): string
    {
        return view('register');
    }

    public function login(): string
    {
        return view('login');
    }

    public function user(): string
    {
        return view('usermarcacion');
    }

    public function userEdit(): string
    {
        return view('users-profile');
    }

    public function adminregistros(): string
    {
        return view('admin-registros');
    }

    public function swalcss(): string
    {
        return view('swal.css');
    }

    public function swaljs(): string
    {
        return view('swal.js');
    }
}
