<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            //default table set 
            $table->engine = 'InnoDB';

            //table fields
            $table->increments('id',10);
            $table->string('id_persona',12)->index();
            $table->string('nombre',100)->nullable(false);
            $table->string('descripcion')->nullable(false);
            $table->date('inicio')->nullable(false);
            $table->unsignedInteger('duracion')->nullable(false);
            //gastos inicio
            $table->float('materiales',8,2);
            $table->float('alojamiento',8,2);
            $table->float('viaticos',8,2);
            $table->float('pasajes',8,2);
            $table->float('equipamento',8,2);
            $table->float('alquiler',8,2);
            $table->float('otros',8,2);
            //gastos fin
            $table->string('id_docente',10)->nullable(false);
            $table->string('nombre_docente',10)->nullable(false);
            $table->unsignedInteger('id_provincia')->nullable(false);
            $table->unsignedInteger('id_departamento')->nullable(false);
            $table->unsignedInteger('id_localidad')->nullable(false);
            $table->string('nombre_grupo',100);
            $table->longText('antecedentes');
            $table->longText('premios');
            $table->unsignedInteger('id_disciplina')->nullable(false);
            $table->unsignedInteger('id_categoria')->nullable(false);
            $table->unsignedInteger('id_estado')->nullable(false);

            //foreign keys            
            $table->foreign('id_disciplina')
                ->references('id')->on('disciplina_beca')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('id_categoria')
                ->references('id')->on('categoria_proyecto')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('id_estado')
                ->references('id')->on('estado_proyecto')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id','proyectos_proyectos_juzgados_id')
                ->references('id_proyecto')->on('proyectos_juzgados')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id','proyectos_miembros_proyecto_id')
                ->references('id_proyecto')->on('miembros_proyecto')
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
        Schema::table('proyectos',function(Blueprint $table){
            $table->dropForeign('proyectos_id_disciplina_foreign');
            $table->dropForeign('proyectos_id_categoria_foreign');
            $table->dropForeign('proyectos_id_estado_foreign');
            $table->dropForeign('proyectos_proyectos_juzgados_id');
            $table->dropForeign('proyectos_miembros_proyecto_id');
            $table->dropForeign('proyectos_id_localidad_foreign');
            $table->dropForeign('proyectos_id_departamento_foreign');
            $table->dropForeign('proyectos_id_provincia_foreign');
            
        });
        Schema::dropIfExists('proyectos');
    }
}
