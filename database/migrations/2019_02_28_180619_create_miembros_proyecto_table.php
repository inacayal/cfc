<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiembrosProyectoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miembros_proyecto', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('id_proyecto')->index();
            $table->string('id_persona',12)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("miembros_proyecto",function(Blueprint $table){
            $table->dropIndex('miembros_proyecto_id_proyecto_index');
            $table->dropIndex('miembros_proyecto_id_persona_index');
        });
        Schema::dropIfExists('miembros_proyecto');
    }
}
