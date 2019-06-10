<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;
use TCG\Voyager\Models\DataType;
use App\Http\Resources\EvaluacionResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\ResumenEvaluacionResource;
use App\User;
use App\Http\Resources\GanadoresResource;
/**
 * Class ProyectosJuzgado
 * 
 * @property int $id_proyecto
 * @property string $id_persona
 * @property int $proyecto_formacion
 * @property int $plan_transferencia
 * @property int $antecedentes
 * @property int $total
 * @property string $observaciones
 * 
 * @property \App\Models\Proyecto $proyecto
 *
 * @package App\Models
 */
class ProyectosJuzgado extends Eloquent
{
	public static $actionsByRole = [//roles
		1=>[//administrador
			//status
			0=>[ //activo
				"TCG\Voyager\Actions\DeleteAction",
            	"TCG\Voyager\Actions\EditAction",
				"TCG\Voyager\Actions\ViewAction"
			],
			1=>[//finalizado
				"App\Actions\VerResumenAction",
				"App\Actions\VerPostulacionAction"
			]
		],
		2=>[//usuario
			//status
			0=>[ //activo
				"TCG\Voyager\Actions\ViewAction"
			],
			1=>[//finalizado
				"TCG\Voyager\Actions\ViewAction"
			],
		],
		4=>[//administrador de provincia
			//status
			0=>[ //activo
            	"TCG\Voyager\Actions\ViewAction"
			],
			1=>[//finalizado
				"TCG\Voyager\Actions\ViewAction"
			]
		],
		5=>[//juez
			//status
			0=>[//activo
            	"TCG\Voyager\Actions\EditAction",
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\PresentarEvaluacionAction",
				"App\Actions\VistaRapidaAction"
			],
			1=>[//finalizado
				"TCG\Voyager\Actions\ViewAction",
				"App\Actions\VistaRapidaAction"
			],
		],
		6=>[
			[ //activo
            	"TCG\Voyager\Actions\EditAction",
				"TCG\Voyager\Actions\ViewAction"
			],
			1=>[//finalizado
				"App\Actions\VerResumenAction",
				"App\Actions\VerPostulacionAction"
			]
		]
	];
	public $incrementing = false;
	public $timestamps = false;
	protected $primaryKey = 'id';
	protected $casts = [
		'id_proyecto' => 'int',
		'proyecto_formacion' => 'float(5,3)',
		'plan_transferencia' => 'float(5,3)',
		'antecedentes' => 'float(5,3)',
		'total' => 'float(5,3)'
	];

	protected $fillable = [
		'id_proyecto',
		'id_persona',
		'id_provincia',
		'proyecto_formacion',
		'plan_transferencia',
		'antecedentes',
		'total',
		'observaciones',
		'finalizado'
	];

	public function proyecto()
	{
		return $this->belongsTo(\App\Models\Proyecto::class, 'id_proyecto', 'id');
	}
	public function usuario()
	{
		return $this->belongsTo(\App\User::class,'id_persona','id');
	}
	public function provincia()
	{
		return $this->belongsTo(\App\Models\Provincia::class,'id_provincia','id');
	}
	public function juez() {
		return $this->belongsTo(\App\User::class,'id_juez','id');
	}
	protected static function boot()
    {
		parent::boot();
		if (\Auth::user()->role_id === 5)
		{	
			static::addGlobalScope('id_juez', function (Builder $builder){
				$builder->where('id_juez',\Auth::user()->id);
			});
		}
	}
	public function getActionsByStatus (int $role) {
		$index = ($role===3) ? 1 : $role;
		return $this::$actionsByRole[$index][(int)$this->finalizado];
	}
	protected static function verGanadores($data){
		if($data->count()>0){
			$data = new GanadoresResource($data);
			return $data->formatCollection();
		}
		return json_encode([]);
	}
	public static function assignDataByRole (User $user){
		$data = [];
		switch( (int) $user->role_id){
			case 1:
			case 3:
			case 6:
				$stats = DB::select("select (select count(*) from proyectos_juzgados where finalizado=0) as por_finalizar, (select count(*) from proyectos_juzgados where finalizado=1) as finalizados")[0];
				$avgData = self::where('finalizado',1)
					->select(DB::raw('id_proyecto,id_provincia, id_persona, CAST(AVG(plan_transferencia) as DECIMAL(5,3)) as plan_transferencia, CAST(AVG(antecedentes) AS DECIMAL(5,3)) as antecedentes, CAST(AVG(proyecto_formacion) AS DECIMAL(5,3)) as proyecto_formacion, CAST(AVG(total) AS DECIMAL(5,3)) as total, 1 as finalizado'))
					->groupBy('id_proyecto','id_provincia','id_persona')
					->get();
				$data = [
					'title'=>'Postulaciones Evaluadas',
					'size' => 'col-md-3',
					'empty' => 'No se ha encontrado evaluaciones registradas.',
					'table'=>'dataTable_evaluaciones',
					'analisis'=>[
						'por_finalizar' => [
							'display'=>'Por Finalizar',
							'color'=>'var(--naranja)',
							'icono'=> 'voyager-edit',
							'contador'=>$stats->por_finalizar,
							'search'=>'Por finalizar'
						],
						'finalizados' => [
							'display'=>'Finalizados',
							'color'=>'var(--verde)',
							'icono'=> 'voyager-check',
							'contador'=>$stats->finalizados,
							'search'=>'Finalizado'
						]
					],
					'data'=> $avgData,
					'type'=>DataType::where('slug','proyectos-juzgados')->first(),
					'pretty'=>self::evaluacionResumen(self::where('finalizado',1)->get()->groupBy('id_proyecto')),
					'ganadores'=>self::verGanadores($avgData)
				];
			break;
			case 5:
				$evaluados = $user->juzgados;
				$evaluadosIds = implode(',',$evaluados->pluck('id_proyecto')->toArray());
				$stats = DB::select('select (select count(*) from proyectos_juzgados where id_juez ='.$user->id.' and finalizado = 1) as finalizados, (select count(*) from proyectos_juzgados where id_juez ='.$user->id.' and finalizado = 0) as por_finalizar')[0];
				$pretty = ($evaluados->count()>0) ? self::prettyInfo($evaluados) : json_encode([]);
				$data = [
					'title'=>'Postulaciones Evaluadas',
					'size' => 'col-md-3',
					'empty' => 'No has evaluado postulaciones. Haz click en añadir nueva para comenzar',
					'table'=>'dataTable_evaluaciones',
					'analisis'=>[
						'por_finalizar' => [
							'display'=>'Por Finalizar',
							'color'=>'var(--naranja)',
							'icono'=> 'voyager-edit',
							'contador'=>$stats->por_finalizar,
							'search'=>'Por finalizar'
						],
						'finalizados' => [
							'display'=>'Finalizados',
							'color'=>'var(--verde)',
							'icono'=> 'voyager-check',
							'contador'=>$stats->finalizados,
							'search'=>'Finalizado'
						]
					],
					'pretty'=> $pretty,
					'data'=>$evaluados,
					'type'=>DataType::where('slug','proyectos-juzgados')->first()
				];
			break;
		}
		return $data;
	}
	protected static function prettyInfo(Collection $dataCollection){
        $formatted = new EvaluacionResource($dataCollection);
        return $formatted->formatCollection();
	}
    protected static function evaluacionResumen (Collection $data){
		$formatted = new ResumenEvaluacionResource($data);
		return $formatted->formatCollection();
	}
	public function getRouteByAction($Action,$key){
		$routes = [
			"TCG\Voyager\Actions\DeleteAction" => [
				'route'=>route('voyager.proyectos-juzgados.destroy',$key),
				'class'=>'btn btn-danger delete',
				'title'=>'Eliminar',
				'icon'=>'voyager-trash'
			],
			"TCG\Voyager\Actions\EditAction" => [
				'route' => route('voyager.proyectos-juzgados.edit',[
					'proyectos-juzgados'=>$key,
					'id_proyecto'=>$this->proyecto->id
				]),
				'class'=> 'btn btn-primary',
				'title'=>'Editar',
				'icon'=>'voyager-pen'
			],
			"App\Actions\PresentarEvaluacionAction" => [
				'route' => route('finalizar',$key),
				'class'=> 'btn btn-success',
				'title'=>'Finalizar',
				'icon'=>'voyager-check'
			]
		];
		return (object) $routes[$Action];
	}
}
