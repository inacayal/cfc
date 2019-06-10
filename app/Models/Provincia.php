<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Provincia
 * 
 * @property int $id
 * @property string $nombre
 * 
 * @property \App\Models\Departamento $departamento
 * @property \App\Models\Localidade $localidade
 * @property \Illuminate\Database\Eloquent\Collection $personas
 * @property \Illuminate\Database\Eloquent\Collection $proyectos
 *
 * @package App\Models
 */
class Provincia extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'nombre'
	];

	public function departamento()
	{
		return $this->belongsTo(\App\Models\Departamento::class, 'id', 'id_provincia');
	}

	public function localidade()
	{
		return $this->belongsTo(\App\Models\Localidade::class, 'id', 'id_provincia');
	}

	public function personas()
	{
		return $this->hasMany(\App\Models\Persona::class, 'id_provincia');
	}

	public function proyectos()
	{
		return $this->hasMany(\App\Models\Proyecto::class, 'id_provincia');
	}
}
