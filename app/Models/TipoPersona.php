<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TipoPersona
 * 
 * @property int $id
 * @property string $descripcion
 * @property string $permisos
 * 
 * @property \Illuminate\Database\Eloquent\Collection $personas
 *
 * @package App\Models
 */
class TipoPersona extends Eloquent
{
	protected $table = 'tipo_persona';
	public $timestamps = false;

	protected $fillable = [
		'descripcion',
		'permisos'
	];

	public function personas()
	{
		return $this->hasMany(\App\Models\Persona::class, 'tipo_persona');
	}
}
