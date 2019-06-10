<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EstadoUsuario
 * 
 * @property int $id
 * @property string $descripcion
 * 
 * @property \Illuminate\Database\Eloquent\Collection $personas
 *
 * @package App\Models
 */
class EstadoUsuario extends Eloquent
{
	protected $table = 'estado_usuario';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'descripcion'
	];

	public function personas()
	{
		return $this->hasMany(\App\Models\Persona::class, 'id_estado');
	}
}
