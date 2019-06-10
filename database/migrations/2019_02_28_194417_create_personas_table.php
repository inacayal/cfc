<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            //default table set 
            $table->engine = 'InnoDB';

            //table fields
            $table->string('dni',12)->unique();
            $table->string('nombre',100)->nullable(false);
            $table->string('apellido',100)->nullable(false);
            $table->string('sexo',1);
            $table->date('fecha_nacimiento')->nullable(false);
            $table->string('direccion');
            $table->string('codigo_postal',8);
            $table->unsignedInteger('id_departamento')->nullable(false);
            $table->unsignedInteger('id_localidad')->nullable(false);
            $table->unsignedInteger('id_provincia')->nullable(false);
            $table->string('email_alt',100)->nullable(false);
            $table->string('email')->unique();
            $table->string('password')->nullable(false);
            $table->string('telefono',20);
            $table->string('celular',20);
            $table->mediumText('curriculo')->nullable(false);
            $table->string('universidad',50);
            $table->unsignedInteger('id_estado')->nullable(false);
            $table->unsignedInteger('tipo_persona')->nullable(false);
            $table->rememberToken();

            $table->primary('dni');
            //foreign keys 

            $table->foreign('dni','personas_proyectos_id')
                ->references('id_persona')->on('proyectos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('dni','personas_miembros_proyecto_id')
                ->references('id_persona')->on('miembros_proyecto')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('id_estado')
                ->references('id')->on('estado_usuario')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('tipo_persona')
                ->references('id')->on('tipo_persona')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_localidad')
                ->references('id')->on('localidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_departamento')
                ->references('id')->on('departamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_provincia')
                ->references('id')->on('provincias')
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
        Schema::table('personas',function(Blueprint $table){
            $table->dropForeign('personas_proyectos_id');
            $table->dropForeign('personas_miembros_proyecto_id');
            $table->dropForeign('personas_id_estado_foreign');
            $table->dropForeign('personas_tipo_persona_foreign');
            $table->dropForeign('personas_id_localidad_foreign');
            $table->dropForeign('personas_id_departamento_foreign');
            $table->dropForeign('personas_id_provincia_foreign');
        });
        Schema::dropIfExists('personas');
    }
}
