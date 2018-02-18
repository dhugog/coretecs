<?php

namespace CoreTecs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categorias';    

    /**
     * The WhiteList
     *  
     * @var array
     */
    protected $fillable = [
        'nome'
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

    public function produtos() 
    {
        return $this->hasMany('CoreTecs\Models\Produto', 'id_categoria');
    }
}
