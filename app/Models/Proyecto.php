<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MiembrosProyecto;
use Illuminate\Http\Request;
use TCG\Voyager\Models\User;
use App\Models\MiembrosProyectoPivot;
use App\User as AppUser;
use TCG\Voyager\Models\DataType;
use Illuminate\Support\Facades\DB;
/**
 * Class Proyecto
 * 
 * @property int $id
 * @property string $id_persona
 * @property string $nombre
 * @property string $descripcion
 * @property \Carbon\Carbon $inicio
 * @property int $duracion
 * @property float $materiales
 * @property float $alojamiento
 * @property float $viaticos
 * @property float $pasajes
 * @property float $equipamento
 * @property float $alquiler
 * @property float $otros
 * @property string $id_docente
 * @property string $nombre_docente
 * @property int $id_provincia
 * @property int $id_departamento
 * @property int $id_localidad
 * @property string $nombre_grupo
 * @property string $antecedentes
 * @property string $premios
 * @property int $id_disciplina
 * @property int $id_categoria
 * @property int $id_estado
 * 
 * @property \App\Models\CategoriaProyecto $categoria_proyecto
 * @property \App\Models\Departamento $departamento
 * @property \App\Models\DisciplinaBeca $disciplina_beca
 * @property \App\Models\EstadoProyecto $estado_proyecto
 * @property \App\Models\Localidade $localidade
 * @property \App\Models\Provincia $provincia
 * @property \App\Models\MiembrosProyecto $miembros_proyecto
 * @property \App\Models\ProyectosJuzgado $proyectos_juzgado
 * @property \App\Models\Persona $persona
 *
 * @package App\Models
 */
class Proyecto extends Eloquent
{
	public $timestamps = false;
	
	protected $casts = [
		'duracion' => 'int',
		'materiales' => 'float',
		'alojamiento' => 'float',
		'viaticos' => 'float',
		'pasajes' => 'float',
		'equipamento' => 'float',
		'alquiler' => 'float',
		'otros' => 'float',
		'id_provincia' => 'int',
		'id_departamento' => 'int',
		'id_localidad' => 'int',
		'id_disciplina' => 'int',
		'id_categoria' => 'int',
		'id_estado' => 'int'
	];

	protected $dates = [
		'inicio'
	];

	protected $fillable = [
		'id_persona',
		'nombre',
		'descripcion',
		'inicio',
		'duracion',
		'materiales',
		'alojamiento',
		'viaticos',
		'pasajes',
		'equipamento',
		'alquiler',
		'otros',
		'id_docente',
		'nombre_docente',
		'id_provincia',
		'id_departamento',
		'id_localidad',
		'nombre_grupo',
		'antecedentes',
		'premios',
		'id_disciplina',
		'id_categoria',
		'id_estado',
		'nota_admision',
		'designacion_representante',
		'archivos_complementarios',
		'link_complementario',
		'completado',
		'provincia_usuario',
		'total'
	];

	public static $actionsByRole = [//roles
		1=>[//administrador
			//status
			1=>[ //activo
				"TCG\Voyager\Actions\DeleteAction",
            	"TCG\Voyager\Actions\EditAction",
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\PresentarAction"
			],
			2=>[//seleccionado
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
			3=>[//Evaluado
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
			4=>[//Presentado
				"App\Actions\AprobarAction",
				"App\Actions\DesaprobarAction",
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
			5=>[//Aprobado
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
		],
		2=>[//usuario
			//status
			1=>[ //activo
				//"TCG\Voyager\Actions\DeleteAction",
            	//"TCG\Voyager\Actions\EditAction",
				"TCG\Voyager\Actions\ViewAction"
			],
			2=>[
				"TCG\Voyager\Actions\ViewAction"
			],
			3=>[
				"TCG\Voyager\Actions\ViewAction"
			],
			4=>[
				"TCG\Voyager\Actions\ViewAction"
			],
			5=>[
				"TCG\Voyager\Actions\ViewAction"
			]
		],
		3=>[//administrador cfc casi igual que el nuestro
			//status
			1=>[ //activo
            	"TCG\Voyager\Actions\ViewAction"
			],
			2=>[//seleccionado
				"TCG\Voyager\Actions\ViewAction"
			],
			3=>[//Evaluado
				"TCG\Voyager\Actions\ViewAction"
			],
			4=>[//Presentado
				"App\Actions\AprobarAction",
				"App\Actions\DesaprobarAction",
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\HabilitarAction"
			],
			5=>[//Aprobado
				"TCG\Voyager\Actions\ViewAction"
			]
		],
		4=>[//administrador de provincia
			//status
			1=>[ //activo
            	"TCG\Voyager\Actions\ViewAction",
			],
			2=>[//seleccionado
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\DeseleccionarAction"
			],
			3=>[//Evaluado
				"TCG\Voyager\Actions\ViewAction"
			],
			4=>[//Presentado
				"TCG\Voyager\Actions\ViewAction"
			],
			5=>[//Aprobado
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\SeleccionarAction"
			]
		],
		5=>[ //juez
			//status
			1=>[//activo
				"TCG\Voyager\Actions\ViewAction"
			],
			2=>[//seleccionado
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\EvaluarAction"
			],
			3=>[//evaluado
				"TCG\Voyager\Actions\ViewAction"
			],
			4=>[//presentado
				"TCG\Voyager\Actions\ViewAction"
			],
			5=>[//aprobado
				"TCG\Voyager\Actions\ViewAction"
			]
		],
		6=>[//administrador CFC nuevos privilegios
			//status
			1=>[ //activo
            	"TCG\Voyager\Actions\EditAction",
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\PresentarAction"
			],
			2=>[//seleccionado
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
			3=>[//Evaluado
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
			4=>[//Presentado
				"App\Actions\AprobarAction",
				"App\Actions\DesaprobarAction",
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
			5=>[//Aprobado
				"App\Actions\HabilitarAction",
				"TCG\Voyager\Actions\ViewAction",
				"TCG\Voyager\Actions\EditAction"
			],
		],
	];
	/**relaciones usadas en voyager para dropdown */
	public function disciplina()
	{
		return $this->belongsTo(\App\Models\DisciplinaBeca::class,'id_disciplina','id');
	}
	public function provincia()
	{
		return $this->belongsTo(\App\Models\Provincia::class,'id_provincia','id');
	}
	public function provinciaUsuario()
	{
		return $this->belongsTo(\App\Models\Provincia::class,'provincia_usuario','id');
	}
	public function lugarTransferencia()
	{
		return $this->belongsTo(\App\Models\Provincia::class,'lugar_transferencia','id');
	}
	public function lugarTransferenciaList(){
		return \App\Models\Provincia::all();
	}
	public function idPersona()
	{
		return $this->belongsTo(User::class,'id_persona','id');
	}
	public function users(){
    	return $this->belongsToMany(User::class,'miembros_proyecto')->using(MiembrosProyectoPivot::class);
	}
	public function idCategoria()
	{
		return $this->belongsTo(\App\Models\CategoriaProyecto::class,'id_categoria','id');
	}
	public function idCategoriaList($param)
	{
		//los proyectos individuales del usuario
		$individual = Proyecto::where('id_persona',\Auth::user()->id)
			->where('id_categoria',1)
			->first();
		//los proyectos grupales del usuario
		$grupal = MiembrosProyectoPivot::where('user_id',\Auth::user()->id)
			->first();
		if($individual && $grupal)
			return collect([$this->idCategoria]);
		//si estoy añadiendo un proyecto y tengo un proyecto creado 
		if(!$param && ($individual || $grupal))
			return CategoriaProyecto::where('id','!=',$individual ? 1 : 2)->get();
		return CategoriaProyecto::all();
	}
	public function idEstado()
	{
		return $this->belongsTo(\App\Models\EstadoProyecto::class, 'id_estado');
	}
	/**fin relaciones usadas en voyager para dropdown */

	public function disciplina_beca()
	{
		return $this->belongsTo(\App\Models\DisciplinaBeca::class, 'id_disciplina');
	}
	public function evaluaciones()
	{
		return $this->hasMany(ProyectosJuzgado::class,'id_proyecto','id');
	}
	/**scope para que usuario vea solo sus propios proyectos */
	protected static function boot()
    {
		parent::boot();
		if (\Auth::user()->role_id === 2)
		{	
			static::addGlobalScope('id', function (Builder $builder) 
				{
					$memberProject = MiembrosProyectoPivot::where('user_id',\Auth::user()->id)->first();
					if(isset($memberProject))
						$builder->where('id_persona',\Auth::user()->id)
							->orWhere('id',$memberProject->proyecto_id);
					else
						$builder->where('id_persona',\Auth::user()->id);
				});
		}
	}
	public function getActionsByStatus (int $role) {
		return $this::$actionsByRole[$role][$this->id_estado];
	}

	public function getRouteByAction($Action,$key){
		$routes = [
			"TCG\Voyager\Actions\DeleteAction" => [
				'route'=>route('voyager.proyectos.destroy',$key),
				'class'=>'btn btn-danger delete',
				'title'=>'Eliminar',
				'icon'=>'voyager-trash'
			],
			"TCG\Voyager\Actions\EditAction" => [
				'route' => route('voyager.proyectos.edit',$key),
				'class'=> 'btn btn-primary',
				'title'=>'Editar',
				'icon'=>'voyager-pen'
			],
			"App\Actions\HabilitarAction" => [
				'route' => route('habilitar',$key),
				'class'=> 'btn btn-primary',
				'title'=>'Habilitar',
				'icon'=>'voyager-pen'
			],
			"App\Actions\AprobarAction"=> [
				'route' => route('aprobar',$key),
				'class'=> 'btn btn-success',
				'title'=>'Aprobar',
				'icon'=>'voyager-check'
			],
			"App\Actions\DesaprobarAction" => [
				'route' => route('desaprobar',$key),
				'class'=> 'btn btn-danger',
				'title'=>'Desaprobar',
				'icon'=>'voyager-x'
			],
			"App\Actions\SeleccionarAction" => [
				'route' => route('seleccionar',$key),
				'class'=> 'btn btn-primary',
				'title'=>'Seleccionar',
				'icon'=>'voyager-check'
			],
			"App\Actions\DeseleccionarAction"=> [
				'route' => route('deseleccionar',$key),
				'class'=> 'btn btn-danger',
				'title'=>'Deseleccionar',
				'icon'=>'voyager-x'
			],
			"App\Actions\EvaluarAction" => [
				'route' => route('voyager.proyectos-juzgados.create',['id_proyecto'=>$key]),
				'class'=> 'btn btn-primary',
				'title'=>'Evaluar',
				'icon'=>'voyager-receipt'
			],
			"App\Actions\PresentarAction"=>[
				'route' => route('presentar',$key),
				'class'=> 'btn btn-success',
				'title'=>'Presentar',
				'icon'=>'voyager-check'
			],
		];
		return (object) $routes[$Action];
	}
	public static function assignDataByRole(AppUser $user){
		$data = [];
		switch ( (int) $user->role_id ){
			case 1:
			case 3:
			case 6:
				$stats = DB::select("select (select count(*) from proyectos where id_estado=1) as activos, (select count(*) from proyectos where id_estado=2) as seleccionados, (select count(*) from proyectos where id_estado=1) as activos, (select count(*) from proyectos where completado=1) as completos, (select count(*) from proyectos where completado=0) as incompletos, (select count(*) from proyectos where id_estado=5) as aprobados, (select count(*) from proyectos where id_estado=3) as evaluados, (select count(*) from proyectos where id_estado=4) as presentados")[0];
                $data = [
					'title'=>'Postulaciones',
					'size' => 'col-md-2',
					'empty' => 'No se ha encontrado postulaciones en el sistema.',
					'table'=>'dataTable_proyectos',
					'analisis'=>[
						'activos' => [
							'display'=>'Activos',
							'color'=>'var(--amarillo)',
							'icono' => 'voyager-lightbulb',
							'contador'=> $stats->activos,
							'search'=>'activo'
						],
						'presentados' => [
							'display'=>'Presentados',
							'color'=>'var(--violeta)',
							'icono' => 'voyager-lock',
							'contador'=> $stats->presentados,
							'search'=>'presentado'
						],
						'aprobados' => [
							'display'=>'Aprobados',
							'color'=>'var(--verde)',
							'icono' => 'voyager-check-circle',
							'contador'=> $stats->aprobados,
							'search'=>'aprobado'
						],
						'seleccionados' => [
							'display'=>'Seleccionados',
							'color'=>'var(--oscuro)',
							'icono'=>'voyager-rocket',
							'contador'=> $stats->seleccionados,
							'search'=>'seleccionado'
						],
						'evaluados' => [
							'display'=>'Evaluados',
							'color'=>'var(--claro)',
							'icono' => 'voyager-receipt',
							'contador'=> $stats->evaluados,
							'search'=>'evaluado'
						]
					],
					'estado-extra'=>[
						'incompletos' => [
							'display'=>'Incompletos',
							'color'=>'var(--naranja)',
							'icono'=> 'voyager-edit',
							'contador'=> $stats->incompletos,
							'search'=>'incompleto'
						],
						'completos' => [
							'display'=>'Completos',
							'color'=>'var(--fucsia)',
							'icono'=> 'voyager-paper-plane',
							'contador'=> $stats->completos,
							'search'=>'completado'
						],
					],
					'extra-busqueda'=>[
						'categoria'=>[
							'titulos'=>[
								'Individual',
								'Grupal'
							],
							'style'=>'text-align:right;border-right:solid 1px var(--border);padding:15px 35px;',
							'color'=>"var(--violeta)"
						],
						'lugar'=>[
							'titulos'=>[
								'En el País',
								'En el Exterior'
							],
							'style'=>'padding:15px 35px;',
							'color'=>"var(--fucsia)"
						],
						'formacion'=>[
							'titulos'=>[
								"Clínica",
								"Residencia",
								"Taller",
								"Posgrado",
								"Curso",
								"Seminario",
								"Workshop",
								"Otro"
							],
							'style'=>'text-align:center;padding:15px 35px;',
							'color'=>"var(--azul)"
						]
					],
					
					'data'=>self::all(),
					'type'=>DataType::where('slug','proyectos')->first()
				];
			break;
			case 4:
				$prov = $user->id_provincia;
				$stats = DB::select('select count(*) as seleccionados from proyectos where provincia_usuario = '.$user->id_provincia.' and id_estado = 2')[0];
				$data = [
					'size' => 'col-md-2',
					'title'=>'Postulaciones',
					'empty' => 'No se ha encontrado postulaciones en tu provincia.',
					'table'=>'dataTable_proyectos',
					'analisis'=>[
						'seleccionados'=>[
							'display'=>'Seleccionados',
							'color'=>'var(--claro)',
							'icono'=> 'voyager-rocket',
							'contador' => $stats->seleccionados,
							'search'=>'seleccionado',
						]
					],
					'data'=>self::where('provincia_usuario',$prov)->whereIn('id_estado',[5,2])->get(),
					'type'=>DataType::where('slug','proyectos')->first()
				];
			break;
			case 5:
				$evaluados = $user->juzgados;
				if($evaluados->count()>0){
					$postulaciones = self::whereNotIn('id',$evaluados->pluck('id_proyecto'))->where('id_estado',2)->get();
					$evaluadosIds = implode(',',$evaluados->pluck('id_proyecto')->toArray());
					$stats = DB::select('select (select count(*) from proyectos where id not in ('.$evaluadosIds.') and id_estado = 2) as seleccionados, (select count(*) from proyectos_juzgados where id_juez ='.$user->id.' and finalizado = 1) as finalizados, (select count(*) from proyectos_juzgados where id_juez ='.$user->id.' and finalizado = 0) as por_finalizar, (select count(*) from proyectos_juzgados where id_juez = '.$user->id.') as evaluados')[0];
				}else{
					$postulaciones = self::where('id_estado',2)->get();
					$stats = DB::select('select (select count(*) from proyectos where id_estado = 2) as seleccionados, (select count(*) from proyectos_juzgados where id_juez ='.$user->id.' and finalizado = 1) as finalizados, (select count(*) from proyectos_juzgados where id_juez ='.$user->id.' and finalizado = 0) as por_finalizar, (select count(*) from proyectos_juzgados where id_juez = '.$user->id.') as evaluados')[0];
				}
				$data = [
					'title'=>'Postulaciones',
					'size' => 'col-md-2',
					'empty' => 'No hay postulaciones que mostrar.',
					'table'=>'dataTable_proyectos',
					'analisis'=>[
						'seleccionados'=>[
							'display'=>'Por Evaluar',
							'color'=>'var(--azul)',
							'icono'=> 'voyager-rocket',
							'contador'=>$postulaciones->count(),
							'table'=>'dataTable_proyectos',
							'search'=>'seleccionado'
						],
						'evaluados'=>[
							'display'=>'Evaluados',
							'color'=>'var(--claro)',
							'icono'=> 'voyager-receipt',
							'contador'=>$stats->evaluados,
							'table'=>'dataTable_proyectos',
							'search'=>'evaluado'
						]
					],
					'data'=>$postulaciones,
					'type'=>DataType::where('slug','proyectos')->first()
				];
			break;
		}
		return $data;
	}
}
