<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __construct(){
        $this->middleware('guest');
    }

    public function index()
    {
        return \Voyager::view('voyager::login');
    }
    public function register()
    {
        return \Voyager::view('voyager::register');
    }
}
