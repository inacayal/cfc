<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvinciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provincias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->unsignedInteger('id');
            $table->string("nombre",50)->nullable(false);
            
            //primary key
            $table->primary("id");
            
            //foreign key to id_provincia on localidades and id_provincia on departamentos
            $table->foreign('id','provincias_localidades_id')
                ->references('id_provincia')->on('localidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('id','provincias_departamentos_id')
                ->references('id_provincia')->on('departamentos')
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
        Schema::table("provincias",function(Blueprint $table){
            $table->dropForeign('provincias_localidades_id');
            $table->dropForeign('provincias_departamentos_id');
        });
        Schema::dropIfExists('provincias');
    }
}
