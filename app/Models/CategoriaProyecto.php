<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
/**
 * Class CategoriaProyecto
 * 
 * @property int $id
 * @property string $descripcion
 * 
 * @property \Illuminate\Database\Eloquent\Collection $proyectos
 *
 * @package App\Models
 */
class CategoriaProyecto extends Eloquent
{
	protected $table = 'categoria_proyecto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'descripcion_categoria'
	];

	public function proyectos()
	{
		return $this->hasMany(\App\Models\Proyecto::class,'id_categoria','id');
	}

	/*public function scopeExisteProyecto ($query,$param) {
		return $query->whereIn('id',$param);
	}*/
}
