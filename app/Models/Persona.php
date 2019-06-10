<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Persona
 * 
 * @property string $dni
 * @property string $nombre
 * @property string $apellido
 * @property string $sexo
 * @property \Carbon\Carbon $fecha_nacimiento
 * @property string $direccion
 * @property string $codigo_postal
 * @property int $id_departamento
 * @property int $id_localidad
 * @property int $id_provincia
 * @property string $email_alt
 * @property string $email
 * @property string $password
 * @property string $telefono
 * @property string $celular
 * @property string $curriculo
 * @property string $universidad
 * @property int $id_estado
 * @property int $tipo_persona
 * @property string $remember_token
 * 
 * @property \App\Models\Departamento $departamento
 * @property \App\Models\EstadoUsuario $estado_usuario
 * @property \App\Models\Localidade $localidade
 * @property \App\Models\Provincia $provincia
 * @property \App\Models\MiembrosProyecto $miembros_proyecto
 * @property \App\Models\Proyecto $proyecto
 *
 * @package App\Models
 */
class Persona extends Eloquent
{
	protected $primaryKey = 'dni';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_departamento' => 'int',
		'id_localidad' => 'int',
		'id_provincia' => 'int',
		'id_estado' => 'int',
		'tipo_persona' => 'int'
	];

	protected $dates = [
		'fecha_nacimiento'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'nombre',
		'apellido',
		'sexo',
		'fecha_nacimiento',
		'direccion',
		'codigo_postal',
		'id_departamento',
		'id_localidad',
		'id_provincia',
		'email_alt',
		'email',
		'password',
		'telefono',
		'celular',
		'curriculo',
		'universidad',
		'id_estado',
		'tipo_persona',
		'remember_token'
	];

	public function departamento()
	{
		return $this->belongsTo(\App\Models\Departamento::class, 'id_departamento');
	}

	public function estado_usuario()
	{
		return $this->belongsTo(\App\Models\EstadoUsuario::class, 'id_estado');
	}

	public function localidade()
	{
		return $this->belongsTo(\App\Models\Localidade::class, 'id_localidad');
	}

	public function provincia()
	{
		return $this->belongsTo(\App\Models\Provincia::class, 'id_provincia');
	}

	public function miembros_proyecto()
	{
		return $this->belongsTo(\App\Models\MiembrosProyecto::class, 'dni', 'id_persona');
	}

	public function proyecto()
	{
		return $this->belongsTo(\App\Models\Proyecto::class, 'dni', 'id_persona');
	}

	public function tipo_persona()
	{
		return $this->belongsTo(\App\Models\TipoPersona::class, 'tipo_persona');
	}
}
