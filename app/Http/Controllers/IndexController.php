<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
