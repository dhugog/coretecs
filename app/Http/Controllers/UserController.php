<?php

namespace CoreTecs\Http\Controllers;

use \CoreTecs\Http\Controllers\PessoaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Yajra\DataTables\Html\Builder;
use CoreTecs\Models\User;
use CoreTecs\Models\Pessoa;

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
        $rules = [
            'name'       => 'required|min:3|max:190',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|confirmed|min:6',
            'id_role'    => 'required', 
        ];

        $messages = [
            'required'           => 'Campo :attribute obrigatório.',
            'id_role.required'   => 'Campo cargo obrigatório',
            'name.min'           => 'Campo Nome deve ter no mínimo 3 caracteres.',
            'name.max'           => 'Campo Nome deve ter no máximo 190 caracteres.',
            'email.email'        => 'Formato de email inválido.',
            'email.unique'       => 'E-mail já cadastrado.',
            'password.confirmed' => 'Senhas não correspondentes.',                
            'password.min'       => 'Campo Senha deve ter no mínimo 6 dígitos.',
        ];

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        
        $requestData = $request->data;

        if(isset($request->data['pessoa'])) {
            $arr['data'] = $request->data['pessoa'];

            isset($arr['data']['cpf']) ? $arr['data']['cpf'] = str_replace('_', '', str_replace('-', '', str_replace('.', '', $arr['data']['cpf']))) : null;
            isset($arr['data']['rg']) ? $arr['data']['rg'] = str_replace('_', '', str_replace('-', '', str_replace('.', '', $arr['data']['rg']))) : null;
            $arr['data']['nome'] = $requestData['name'];

            $request->merge($arr);
            $pessoaController = new PessoaController();
            $pessoa = $pessoaController->store($request);

            if($pessoa['fail']) {
                foreach($pessoa['errors'] as $key => $error) {
                    $pessoa['errors']['pessoa[' . $key . ']'] = $error;
                    unset($pessoa['errors'][$key]);
                }

                return $pessoa;
            }

            $requestData['id_pessoa'] = $pessoa->id;
        }

        $requestData['password'] = bcrypt($requestData['password']);

        return User::create($requestData);
    }

    /**
     * Restore a deleted user
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id) 
    {
        User::where('id', $id)->restore();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->with('pessoa')->with('role')->first();

        return response()->json($user);
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
        $user = User::find($id);

        $rules = [
            'name'     => 'required|min:3|max:190',
            // 'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'id_role'  => 'required'
        ];

        if($user->email != $request->data['email']) {
            $rules['email'] = 'required|email|unique:users';
        }

        $messages = [
            'required'           => 'Campo :attribute obrigatório.',
            'id_role.required'   => 'Campo cargo obrigatório',
            'name.min'           => 'Campo Nome deve ter no mínimo 3 caracteres.',
            'name.max'           => 'Campo Nome deve ter no máximo 190 caracteres.',
            'email.email'        => 'Formato de email inválido.',
            'email.unique'       => 'E-mail já cadastrado.',
            'password.confirmed' => 'Senhas não correspondentes.',                
            'password.min'       => 'Campo Senha deve ter no mínimo 6 dígitos.',            
        ];        

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        $requestData = $request->data;
        
        if(isset($request->data['pessoa'])) {
            $arr['data'] = $request->data['pessoa'];

            isset($arr['data']['cpf']) ? $arr['data']['cpf'] = str_replace('_', '', str_replace('-', '', str_replace('.', '', $arr['data']['cpf']))) : null;
            isset($arr['data']['rg']) ? $arr['data']['rg'] = str_replace('_', '', str_replace('-', '', str_replace('.', '', $arr['data']['rg']))) : null;
            $arr['data']['nome'] = $requestData['name'];

            $request->merge($arr);
            $pessoaController = new PessoaController();
            $pessoa = $pessoaController->update($request, $requestData['id_pessoa']);

            if($pessoa['fail']) {
                foreach($pessoa['errors'] as $key => $error) {
                    $pessoa['errors']['pessoa[' . $key . ']'] = $error;
                    unset($pessoa['errors'][$key]);
                }

                return $pessoa;
            }            
        }

        $requestData['password'] = bcrypt($requestData['password']);

        unset($requestData['password_confirmation']);

        unset($requestData['pessoa']);

        User::where('id', $id)->update($requestData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();        

        if($user) {
            User::where('id', $id)->delete();
        } else {
            User::where('id', $id)->forceDelete();
        }
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data() {        
        return DataTables::of(User::query()->with('pessoa')->with('role')->withTrashed())
            ->addColumn('action', function($p) {
                $buttons = '<ul class="list-inline">';
                    if(empty($p->deleted_at)) {
                        $buttons .= '<li class="pull-left">';
                            $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-warning btn-edit" data-toggle="modal" data-target="#modal-default" action="' . route("usuarios.update", $p->id)  . '"><i class="fa fa-edit"></i> Alterar</button>';
                        $buttons .= '</li>';
                    } else {
                        $buttons .= '<li class="pull-left">';
                            $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-warning btn-action" data-no-toggle="" method="get" action="' . route("usuarios.restore", $p->id)  . '"><i class="fa fa-refresh"></i> Recuperar</button>';
                        $buttons .= '</li>';
                    }

                    $buttons .= '<li class="pull-left">';
                        $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-danger btn-delete" data-toggle="modal" data-target="#modal-default" action="' . route("usuarios.destroy", $p->id)  . '"><i class="fa fa-trash"></i> Excluir</button>';
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
