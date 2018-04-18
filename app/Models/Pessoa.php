<?php

namespace CoreTecs\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'rg', 'cpf', 'dtnascimento', 'sexo'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Mutators
     * Handle incoming value
     */
    public function setCpfAttribute($value) {
        $this->attributes['cpf'] = str_replace('.', '', $value);
    }

    public function setRgAttribute($value) {
        $this->attributes['rg'] = str_replace('.', '', $value);
    }

    public function user() 
    {
        return $this->hasOne('CoreTecs\Models\User', 'id_pessoa', 'id');
    }
}
