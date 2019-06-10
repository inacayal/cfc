<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosJuzgadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_juzgados', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('id_proyecto')->index();
            $table->string('id_persona',12)->index();
            $table->unsignedInteger('proyecto_formacion');
            $table->unsignedInteger('plan_transferencia');
            $table->unsignedInteger('antecedentes');
            $table->unsignedInteger('total');
            $table->mediumText('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("proyectos_juzgados",function(Blueprint $table){
            $table->dropIndex('proyectos_juzgados_id_proyecto_index');
            $table->dropIndex('proyectos_juzgados_id_persona_index');
        });        
        Schema::dropIfExists('proyectos_juzgados');
    }
}
