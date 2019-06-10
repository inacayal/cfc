<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Departamento
 * 
 * @property int $id
 * @property string $nombre
 * @property int $id_provincia
 * 
 * @property \App\Models\Localidade $localidade
 * @property \Illuminate\Database\Eloquent\Collection $personas
 * @property \App\Models\Provincia $provincia
 * @property \Illuminate\Database\Eloquent\Collection $proyectos
 *
 * @package App\Models
 */
class Departamento extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'id_provincia' => 'int'
	];

	protected $fillable = [
		'nombre',
		'id_provincia'
	];

	public function localidade()
	{
		return $this->belongsTo(\App\Models\Localidade::class, 'id', 'id_departamento');
	}

	public function personas()
	{
		return $this->hasMany(\App\Models\Persona::class, 'id_departamento');
	}

	public function provincia()
	{
		return $this->hasOne(\App\Models\Provincia::class, 'id', 'id_provincia');
	}

	public function proyectos()
	{
		return $this->hasMany(\App\Models\Proyecto::class, 'id_departamento');
	}
}
