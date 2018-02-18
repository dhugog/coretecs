<?php

namespace CoreTecs\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct() {    
        $this->middleware(function ($request, $next) {
            if(!authorized(2)) return redirect('/home');

            return $next($request);
        });
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
