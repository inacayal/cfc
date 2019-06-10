<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Traits\DebeVerificarCorreo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\PasswordReset;
use App\Models\Proyecto;
use TCG\Voyager\Models\DataType;
use App\Models\ProyectosJuzgado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
{
    use Notifiable;
    //public $incrementing = false;
    protected $primaryKey = 'id';
    /*from voyager user*/
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_verified_at','name', 'email', 'password','id','settings','role_id','verify_token','foto_perfil','dni_frente','dni_dorso','path','telefono','completado','nacionalidad'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'id' => 'string'    
    ];
    /**
     * actions by role.
     *
     * @var array
     */
    protected static $modelsByRole = [
        1=>[
            'proyectos'=>'App\Models\Proyecto',
            'postulantes'=>'App\User',
            'evaluaciones'=>'App\Models\ProyectosJuzgado',
        ],
        4=>[
            'proyectos'=>'App\Models\Proyecto'
        ],
        5=>[
            'proyectos'=>'App\Models\Proyecto',
            'evaluaciones'=>'App\Models\ProyectosJuzgado'
        ]
    ];
    protected static $actionsByRole = [
        1=>[
            "TCG\Voyager\Actions\DeleteAction",
            "TCG\Voyager\Actions\EditAction",
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
        ],
    ];
    /**
     * actions by role explained.
     *
     * @var array
     */
    protected static $roleActions = [
        1=>[
            'botones'=> [
                'Ver'=>[
                    'icono'=>'voyager-eye',
                    'color' => 'var(--fucsia)',
                    'texto' => 'Con este botón serás redireccionado a la vista individual de cada postulación. Encontrarás toda la información relacionada, junto a los datos más importantes del postulante o grupo.'
                ],
                'Aprobar' => [
                    'icono'=>'voyager-check',
                    'color' => 'var(--verde)',
                    'texto' => 'Con este botón habilitarás esta postulación para que el administrador de provincia pueda verla, indicando que toda la información de la postulación es correcta. Hasta que no lo apruebes, el administrador de provincia no podrá ver la postulación.'
                ],
                'Desaprobar' =>[
                    'icono'=>'voyager-x',
                    'color' =>'var(--naranja)',
                    'texto' => 'Si marcaste una postulación como aprobada y cambias de opinión, puedes volver a cambiar su estado a presentado. Así el administrador de provincia no podrá verla.'
                ],
                'Habilitar' =>[
                    'icono'=>'voyager-pen',
                    'color' =>'var(--claro)',
                    'texto' => 'Si deseas habilitar la postulación para que el usuario pueda seguir editándola, puedes presionar este botón. Una vez que lo presionas, se deshabilita la función de aprobar/desaprobar y el usuario tendrá que presentar la postulación de nuevo.'
                ]
            ]
        ],
        4=>[
            'botones'=> [
                'Ver'=>[
                    'icono'=>'voyager-eye',
                    'color' => 'var(--fucsia)',
                    'texto' => 'Con este botón usted será redireccionado a la vista individual de cada postulación.'
                ],
                'Seleccionar' => [
                    'icono'=>'voyager-check',
                    'color' => 'var(--claro)',
                    'texto' => 'Utilice "Seleccionar" para marcar las postulaciones (máximo 5) que serán evaluadas por el jurado externo.'
                ],
                'Deseleccionar' =>[
                    'icono'=>'voyager-x',
                    'color' =>'var(--naranja)',
                    'texto' => 'Si usted marcó una postulación como seleccionada, podrá volver a cambiar su estado a la instancia anterior.'
                ]
            ]
        ],
        5=> [
            'botones'=> [
                'Ver'=>[
                    'icono'=>'voyager-eye',
                    'color' => 'var(--fucsia)',
                    'texto' => 'Al hacer clic en "Ver" se redireccionará a la vista individual de cada postulación.'
                ],
                'Evaluar' => [
                    'icono'=>'voyager-receipt',
                    'color' => 'var(--claro)',
                    'texto' => 'Este botón lleva al formulario de evaluación. Una vez realizada esta acción, la postulación pasará a la solapa Postulaciones Evaluadas.'
                ],
                'Finalizar' =>[
                    'icono'=>'voyager-check',
                    'color' =>'var(--verde)',
                    'texto' => 'Utilice este botón para indicar que ha culminado el proceso de evaluación. Una vez realizado, no podrá modificar ni eliminar la evaluación de una postulación.'
                ]
            ]
        ]
    ];
	public function resets()
	{
		return $this->hasMany(PasswordReset::class,'email','email');
    }
    public function juzgados()
	{
        return $this->hasMany(ProyectosJuzgado::class,'id_juez','id');
    }
    function postulacion () {
        return $this->hasMany(Proyecto::class,'id_persona','id');
    }
    public function proyectoUsuario () {
        return $this->hasOne(Proyecto::class, 'id_persona','id');
    }
    public function verificarToken ($token)
    {
        $log = $this->resets->where('reset_token',$token)->first(); 
        if (is_null($log->updated_at))
        {
            PasswordReset::where('email',$this->email)
                ->update(['updated_at'=>$this->freshTimestamp()]);
            return true;
        }
        return false;
    }

    public function crearContrasena ($password)
    {
        $newPass = \Hash::make($password);
        $this->update([
            'password'=>$newPass
        ]);
    }

    function getSelectedNumber(){
        $selec = 0;
        if((int)$this->role_id === 4){
            $number = DB::select('select count(*) as number from proyectos where provincia_usuario = '.$this->id_provincia.' and id_estado=2 or id_estado=3')[0];
            $selec = $number->number;
        }
        return 5-$selec;
    }
    
    public function getInstructions (int $role){
        $index = ($role===3|| $role===6) ? 1 : $role;
        return $this::$roleActions[$index];
    }
    
    public function getRoleInformation (int $role) {
        $index = ($role === 3|| $role===6) ? 1 : $role;
        $data = $this->dataByRole($index);
        return $data;
    }
    
    public function getActionsByStatus (int $role) {
        return $this::$actionsByRole[$role];
    }
    /**
     * User methods
     *
     * @var array
     */
    public function dataByRole(int $role){
        $index = ($role === 3|| $role===6) ? 1 : $role;
        return $this::$modelsByRole[$index];
    }
    /**
     * Assign data based on user role
     */
    public static function assignDataByRole (self $user) {
        $data = [];
        switch ( (int) $user->role_id){
            case 1:
            case 3:
            case 6:
                $stats = DB::select("select (select count(*) from users where completado=1) as completos, (select count(*) from users where completado=0) as incompletos")[0];
                $data = [
                    'title'=>'Usuarios',
                    'size' => 'col-md-2',
                    'empty' => 'No se ha encontrado usuarios registrados.',
                    'table'=>'dataTable_postulantes',
                    'analisis'=>[
                        'incompletos' => [
                            'display'=>'Incompletos',
                            'color'=>'var(--naranja)',
                            'icono'=> 'voyager-edit',
                            'contador'=>$stats->incompletos,
                            'search'=>'incompleto'
                        ],
                        'completos' => [
                            'display'=>'Completos',
                            'color'=>'var(--fucsia)',
                            'icono'=> 'voyager-paper-plane',
                            'contador'=>$stats->completos,
                            'search'=>'completado'
                        ]
                    ],
                    'extra-busqueda'=>[
                        'nacionalidad'=>[
							'titulos'=>[
								'Argentino',
								'Extranjero'
							],
							'style'=>'text-align:right;border-right:solid 1px var(--border);padding:15px 35px;',
							'color'=>"var(--violeta)"
						],
						'sexo'=>[
							'titulos'=>[
								'Masculino',
                                'Femenino',
                                'Otro'
							],
							'style'=>'padding:15px 35px;',
							'color'=>"var(--fucsia)"
						],
                    ],
                    'data'=>self::where('role_id',2)->get(),
                    'type'=>DataType::where('slug','users')->first()
                ];
            break;
        }
        return $data;
    }
}
