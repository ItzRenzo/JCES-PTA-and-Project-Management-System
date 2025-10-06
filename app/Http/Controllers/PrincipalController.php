<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    /**
     * Display the principal dashboard.
     */
    public function index()
    {
        return view('principal.dashboard');
    }
}
