<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->unsignedInteger('id');
            $table->string("nombre",50)->nullable(false);
            $table->unsignedInteger("id_provincia")->index();
            
            //primary key
            $table->primary("id");
            
            //foreign key to id_departamento on localidades
            $table->foreign('id')
                ->references('id_departamento')->on('localidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("departamentos",function(Blueprint $table){
            $table->dropForeign('departamentos_id_foreign');
            $table->dropIndex('departamentos_id_provincia_index');
        });
        Schema::dropIfExists('departamentos');
    }
}