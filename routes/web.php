<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/teste', 'TestController@index');    

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/admin/home', 'HomeController@index')->name('home');
});

Route::group(['middleware' => ['web', 'auth', 'can:manage-stock']], function () {

    // Categorias
    Route::get('/admin/categorias/data',   ['as' => 'categorias.data',   'uses' => 'CategoriaController@data']);
    Route::get('/admin/categorias/search', ['as' => 'categorias.search', 'uses' => 'CategoriaController@search']);
    Route::resource('/admin/categorias', 'CategoriaController');
    
    // Produtos
    Route::get('/admin/produtos/data', ['as' => 'produtos.data', 'uses' => 'ProdutoController@data']);    
    Route::post('/admin/produtos/uploadImage', ['as' => 'produtos.uploadimage', 'uses' => 'ProdutoController@upImage']);
    Route::resource('/admin/produtos', 'ProdutoController');
});

Route::group(['middleware' => ['web', 'auth', 'can:manage-users']], function () {
    // Usuarios
    Route::get('/admin/usuarios/data', ['as' => 'usuarios.data', 'uses' => 'UserController@data']);
    Route::get('/admin/usuarios/{usuario}/restore', ['as' => 'usuarios.restore', 'uses' => 'UserController@restore']);
    Route::resource('/admin/usuarios', 'UserController');

    // Roles
    Route::get('/admin/roles/search', ['as' => 'roles.search', 'uses' => 'RoleController@search']);
    Route::resource('/admin/roles', 'RoleController');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/home', function() {
        \Auth::logout();
        return "Home";
    });
});
