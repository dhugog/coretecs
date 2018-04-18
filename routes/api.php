<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Exemplo de rota
 * http://localhost/CoreTecs/public/index.php/api/pessoa
 */

Route::group(['namespace' => 'Api', 'middleware' => ['api']], function(){
    Route::resource('pessoa', 'PessoaApiController');
});