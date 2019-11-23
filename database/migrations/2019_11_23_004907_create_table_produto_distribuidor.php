<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdutoDistribuidor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_distribuidor', function (Blueprint $table) {
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('distribuidor_id');
            $table->float('preco');
            $table->boolean('em_estoque');

            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('distribuidor_id')->references('id')->on('distribuidors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_distribuidor');
    }
}
