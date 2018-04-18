<?php

namespace CoreTecs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Yajra\DataTables\Html\Builder;
use \CoreTecs\Models\Categoria;

class CategoriaController extends Controller
{
    public function __construct() {    
        $this->middleware(function ($request, $next) {
            if(!authorized(2)) return redirect('/home');

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {    
        return view('categoria.index');
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
            'nome'     => 'required|min:3|max:50',
        ];

        $messages = [
            'required'           => 'Campo :attribute obrigatório.',
            'nome.min'           => 'Campo Nome deve ter no mínimo 3 caracteres.',
            'nome.max'           => 'Campo Nome deve ter no máximo 50 caracteres.',
        ];        

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        Categoria::create($request->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);

        return response()->json($categoria);
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
        $rules = [
            'nome'     => 'required|min:3|max:50',
        ];

        $messages = [
            'required'           => 'Campo :attribute obrigatório.',
            'nome.min'           => 'Campo Nome deve ter no mínimo 3 caracteres.',
            'nome.max'           => 'Campo Nome deve ter no máximo 50 caracteres.',
        ];        

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        
        Categoria::where('id', $id)->update($request->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Categoria::find($id)->delete();
        return response()->json();
    }
    
    /**
     * List categories by name
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $search = '';

        if($request->has('search')) {   
            $search = $request->search;                     
        }

        $categorias = Categoria::where('nome', 'like', $search . '%')->get();

        return response()->json($categorias);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data() {        
        return DataTables::of(Categoria::query())
            ->addColumn('action', function($p) {
                $buttons = '<ul class="list-inline">';
                    $buttons .= '<li class="pull-left">';
                        $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-danger btn-delete" data-toggle="modal" data-target="#modal-default" action="' . route("categorias.destroy", $p->id)  . '"><i class="fa fa-trash"></i> Excluir</button>';
                    $buttons .= '</li>';
                    $buttons .= '<li class="pull-left">';
                        $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-warning btn-edit" data-toggle="modal" data-target="#modal-default" action="' . route("categorias.update", $p->id)  . '"><i class="fa fa-edit"></i> Alterar</button>';
                    $buttons .= '</li>';    
                $buttons .= '</ul>';

                return $buttons;
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
