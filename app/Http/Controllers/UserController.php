<?php

namespace CoreTecs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Yajra\DataTables\Html\Builder;
use CoreTecs\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('usuario.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $rules = [
            'name'     => 'required|min:3|max:190',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ];

        $messages = [
            'required'           => 'Campo :attribute obrigatório.',
            'name.min'           => 'Campo Nome deve ter no mínimo 3 caracteres.',
            'name.max'           => 'Campo Nome deve ter no máximo 190 caracteres.',
            'email.email'        => 'Formato de email inválido.',
            'email.unique'       => 'E-mail já cadastrado.',
            'password.confirmed' => 'Senhas não correspondentes.',                
            'password.min'       => 'Campo Senha deve ter no mínimo 6 dígitos.',
        ];        

        $validator = Validator::make($request->data, $rules, $messages);

        // return dd($validator);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        $requestData = $request->data;

        $requestData['password'] = bcrypt($requestData['password']);

        User::create($requestData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data() {        
        return DataTables::of(User::query()->with('role'))
            ->addColumn('action', function($p) {
                $buttons = '<ul class="list-inline">';
                    $buttons .= '<li class="pull-left">';
                        $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-danger btn-delete" data-toggle="modal" data-target="#modal-default" action="' . route("usuarios.destroy", $p->id)  . '"><i class="fa fa-trash"></i> Excluir</button>';
                    $buttons .= '</li>';
                    $buttons .= '<li class="pull-left">';
                        $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-warning btn-edit" data-toggle="modal" data-target="#modal-default" action="' . route("usuarios.update", $p->id)  . '"><i class="fa fa-edit"></i> Alterar</button>';
                    $buttons .= '</li>';    
                $buttons .= '</ul>';

                return $buttons;
            })
            ->editColumn('role.name', function($p) {                
                return trans('adminlte::adminlte.' . $p->role->name);
            })
            ->editColumn('created_at', function($p) { 
                if(!empty($p->created_at)) {                               
                    return \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i:s');
                }
            })
            ->editColumn('updated_at', function($p) {  
                if(!empty($p->updated_at)) {
                    return \Carbon\Carbon::parse($p->updated_at)->format('d/m/Y H:i:s');
                }              
            })
            ->make(true);
    }
}
