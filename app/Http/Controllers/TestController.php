<?php

namespace CoreTecs\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index() {      
        // return \Auth::user()->role;
        //\CoreTecs\Models\Pessoa::where('nome', 'like', 'daniel%')->update(['sexo' => 'M']);
        dd( \CoreTecs\Models\Pessoa::with('user')->get() );
        // return \CoreTecs\Models\User::with('pessoa')->get();
    }
}
