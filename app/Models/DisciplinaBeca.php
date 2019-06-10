<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DisciplinaBeca
 * 
 * @property int $id
 * @property string $descripcion
 * 
 * @property \Illuminate\Database\Eloquent\Collection $proyectos
 *
 * @package App\Models
 */
class DisciplinaBeca extends Eloquent
{
	protected $table = 'disciplina_beca';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'descripcion'
	];

	public function proyectos()
	{
		return $this->hasMany(\App\Models\Proyecto::class, 'id_disciplina');
	}
}
