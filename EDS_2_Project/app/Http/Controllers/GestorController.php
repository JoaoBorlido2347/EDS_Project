<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestorController extends Controller
{
    public function dashboard()
    {
        return view('gestor.dashboard', [
            'user' => Auth::user()
        ]);
    }
}