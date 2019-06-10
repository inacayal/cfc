<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Localidade
 * 
 * @property int $id
 * @property string $nombre
 * @property int $id_provincia
 * @property int $id_departamento
 * 
 * @property \App\Models\Departamento $departamento
 * @property \Illuminate\Database\Eloquent\Collection $personas
 * @property \App\Models\Provincia $provincia
 * @property \Illuminate\Database\Eloquent\Collection $proyectos
 *
 * @package App\Models
 */
class Localidade extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'id_provincia' => 'int',
		'id_departamento' => 'int'
	];

	protected $fillable = [
		'nombre',
		'id_provincia',
		'id_departamento'
	];

	public function departamento()
	{
		return $this->hasOne(\App\Models\Departamento::class, 'id', 'id_departamento');
	}

	public function personas()
	{
		return $this->hasMany(\App\Models\Persona::class, 'id_localidad');
	}

	public function provincia()
	{
		return $this->hasOne(\App\Models\Provincia::class, 'id', 'id_provincia');
	}

	public function proyectos()
	{
		return $this->hasMany(\App\Models\Proyecto::class, 'id_localidad');
	}
}
