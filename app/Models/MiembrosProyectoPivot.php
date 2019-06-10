<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class MiembrosProyecto
 * 
 * @property int $id_proyecto
 * @property string $id_persona
 * 
 * @property \App\Models\Persona $persona
 * @property \App\Models\Proyecto $proyecto
 *
 * @package App\Models
 */
class MiembrosProyectoPivot extends Pivot
{
	protected $table = 'miembros_proyecto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_proyecto' => 'int'
	];

	protected $fillable = [
		'proyecto_id',
		'user_id'
	];
}
