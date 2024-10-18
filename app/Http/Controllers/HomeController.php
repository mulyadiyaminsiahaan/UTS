<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $auth = Auth::user();

        $data = [
            'auth' => $auth,
        ];
        
        return view('app.home', $data);
    }
}
