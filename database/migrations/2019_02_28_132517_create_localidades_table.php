<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localidades', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('id');
            $table->string("nombre",50)->nullable(false);
            $table->unsignedInteger("id_provincia")->index();
            $table->unsignedInteger("id_departamento")->index();

            $table->primary("id");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("localidades",function(Blueprint $table){
            $table->dropIndex('localidades_id_provincia_index');
            $table->dropIndex('localidades_id_departamento_index');
        });
        Schema::dropIfExists('localidades');
    }
}
