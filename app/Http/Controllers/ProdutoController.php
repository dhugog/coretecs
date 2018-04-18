<?php

namespace CoreTecs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Yajra\DataTables\Html\Builder;
use CoreTecs\Models\Produto;
use App\Repositories\Traits\ReportPDF;

class ProdutoController extends Controller
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
    public function index(Builder $builder = null)
    {        
        return view('produto.index');
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
            'id_categoria' => 'required',
            'nome'         => 'required|max:50',
            'marca'        => 'required|max:100',
            'descricao'    => 'max:190',
            'preco'        => 'required',            
        ];

        $messages = [
            'required' => 'Campo :attribute obrigatório.',            
            'nome.max'      => 'Campo :attribute deve ter no máximo 50 caracteres.',
            'marca.max'      => 'Campo :attribute deve ter no máximo 100 caracteres.',
            'descricao.max'      => 'Campo :attribute deve ter no máximo 190 caracteres.',
        ];

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        Produto::create($request->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Produto::where('id', $id)->with('categoria')->first();

        return response()->json($produto);
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
            'id_categoria' => 'required',
            'nome'         => 'required|max:50',
            'marca'        => 'required|max:100',
            'descricao'    => 'max:190',
            'preco'        => 'required',            
        ];

        $messages = [
            'required' => 'Campo :attribute obrigatório.',            
            'nome.max'      => 'Campo :attribute deve ter no máximo 50 caracteres.',
            'marca.max'      => 'Campo :attribute deve ter no máximo 100 caracteres.',
            'descricao.max'      => 'Campo :attribute deve ter no máximo 190 caracteres.',
        ];

        $validator = Validator::make($request->data, $rules, $messages);

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        $produto = Produto::where('id', $id)->first();

        $dir = 'uploads/produtos/' . $produto->imagem;

        if (file_exists($dir) && $produto->imagem != null && $request->data['imagem'] != $produto->imagem) {
            unlink($dir);
        }

        Produto::where('id', $id)->update($request->data);
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produto = Produto::where('id', $id)->first();
        
        $filename = 'uploads/produtos/' . $produto->imagem;

        if (file_exists($filename)) {
            unlink($filename);
        }

        $produto->delete();
        return response()->json();
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data() {
        return DataTables::of(Produto::query())
            ->addColumn('action', function($p) {
                $buttons = '<ul class="list-inline">';
                    $buttons .= '<li class="pull-left">';
                        $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-danger btn-delete" data-toggle="modal" data-target="#modal-default" action="' . route("produtos.destroy", $p->id)  . '"><i class="fa fa-trash"></i> Excluir</button>';
                    $buttons .= '</li>';
                    $buttons .= '<li class="pull-left">';
                        $buttons .= '<button type="submit" class="btn btn-xs btn-flat btn-warning btn-edit" data-toggle="modal" data-target="#modal-default" action="' . route("produtos.update", $p->id)  . '"><i class="fa fa-edit"></i> Alterar</button>';
                    $buttons .= '</li>';
                $buttons .= '</ul>';

                return $buttons;
            })
            ->editColumn('id_categoria', function($p) {                 
                return json_decode(\CoreTecs\Models\Categoria::select('nome')->where('id', $p->id_categoria)->first(), true)['nome'];
            })
            ->editColumn('preco', function($p) {
                return 'R$ ' . number_format($p->preco, 2, ',', '.');
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
            ->editColumn('imagem', function($p) {
                if(!empty($p->imagem)) {
                    return '<a href="#" class="image-see" data-toggle="modal" data-target="#modal-image" data-filedir="uploads/produtos/' . $p->imagem . '">Ver</a>';
                }
            })
            ->rawColumns(['imagem', 'action'])
            ->make(true);
    }

    /**
     * Uploads the selected product imagem
     */
    public function upImage( Request $request ){
        $validator = Validator::make($request->all(),
            [
                'file' => 'image',
            ],
            [                
                'file.image'    => 'O arquivo deve ser nos formatos jpeg, png, bmp, gif, ou svg.'
            ]
        );

        if($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        // $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
        // $filename = uniqid() . '_' . time() . '.' . $extension;

        if($request->file('file')) {
            $filename = strtolower($request->file('file')->getClientOriginalName());
    
            $dir = 'uploads/produtos/';
            
            $request->file('file')->move($dir, $filename);
            return $dir . $filename;
        }

        return null;
    }
}
