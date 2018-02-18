<?php

namespace CoreTecs\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index() {      
        return \Auth::user()->role;
        return \CoreTecs\Models\User::query()->with('roles')->get();
        $categoria = new \CoreTecs\Models\Categoria();
        $categoria->nome = "Teste";
        $categoria->save();
    }
}
