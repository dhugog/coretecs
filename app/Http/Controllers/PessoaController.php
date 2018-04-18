<?php

namespace CoreTecs\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \CoreTecs\Models\Pessoa;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
            'nome'       => 'required|min:3|max:190',
            'cpf'        => 'required|min:11|unique:pessoas,cpf',
            'rg'         => 'required|min:9|unique:pessoas,rg',
        ];

        $messages = [
            'required'   => 'Campo :attribute obrigatório.',
            'rg.unique'  => 'RG já cadastrado.',
            'cpf.unique' => 'CPF já cadastrado.',
            'cpf.min'    => 'O campo CPF deve possuir no mínimo 11 caracteres',
            'rg.min'     => 'O campo RG deve possuir no mínimo 9 caracteres',
        ];

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        return Pessoa::create($request->data);
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
        $pessoa = Pessoa::find($id);

        $rules = [
            'nome'       => 'required|min:3|max:190',
            'cpf'        => 'required|min:11',
            'rg'         => 'required|min:9',
        ];

        if($pessoa->cpf != $request->data['cpf']) {
            $rules['cpf'] = 'required|min:11|unique:pessoas,cpf';
        }

        if($pessoa->rg != $request->data['rg']) {
            $rules['rg'] = 'required|min:9|unique:pessoas,rg';
        }

        $messages = [
            'required'   => 'Campo :attribute obrigatório.',
            'rg.unique'  => 'RG já cadastrado.',
            'cpf.unique' => 'CPF já cadastrado.',
            'cpf.min'    => 'O campo CPF deve possuir no mínimo 11 caracteres',
            'rg.min'     => 'O campo RG deve possuir no mínimo 9 caracteres',
        ];

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        return Pessoa::where('id', $id)->update($request->data);
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
}
