<?php

namespace CoreTecs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produtos';    
    
    /**
     * The WhiteList
     *  
     * @var array
     */
    protected $fillable = [
        'id_categoria',
        'nome',
        'marca',
        'descricao',
        'preco',
        'imagem'
    ];

    /**
     * The BlackList
     * 
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function categoria() 
    {
        return $this->belongsTo('CoreTecs\Models\Categoria', 'id_categoria');
    }
}
