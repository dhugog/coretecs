<?php

use Illuminate\Database\Seeder;
use CoreTecs\Models\Pessoa;

class PessoaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pessoa::create([
            'nome'          => 'Daniel Hugo Gasparotto',
            'cpf'           => '44337923810',
            'rg'            => '509789699',
            'dtnascimento'  => '2001-12-02',
            'sexo'          => 'M'
        ]);
    }
}
