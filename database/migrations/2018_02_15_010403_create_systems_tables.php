<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            //$table->jsonb('permissions')->nullable(); //->default('{}'); // jsonb deletes duplicates
            $table->text('permissions')->nullable(); //->default('{}'); // jsonb deletes duplicates            
        });

        Schema::create('pessoas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('cpf', 30)->nullable()->unique();
            $table->string('rg', 30)->nullable()->unique();
            $table->date('dtnascimento');
            $table->enum('sexo', ['M', 'F']);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('id_role')->unsigned()->default(1);
            $table->integer('permission')->nullable();
            $table->string('password');
            $table->integer('id_pessoa')->nullable()->unsigned();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_role')->references('id')->on('roles');
            $table->foreign('id_pessoa')->references('id')->on('pessoas');
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 50);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_categoria')->unsigned();
            $table->string('nome', 50);
            $table->string('marca', 100);
            $table->string('descricao')->nullable();
            $table->float('preco', 8,2);
            $table->string('imagem', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_categoria')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
        Schema::dropIfExists('pessoas');
        Schema::dropIfExists('roles');
    }
}
