<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;

class EvaluacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected $formattedCollection;
    public function __construct(Collection $collect){
        $data = [];
        foreach ($collect as $item){
            $data[$item->id] = [
                'id'=>$item->id_proyecto,
                'observaciones'=>$item->observaciones,
                'total'=>$item->total,
                'proyecto'=>$item->proyecto_formacion,
                'antecedentes'=>$item->antecedentes,
                'plan'=>$item->plan_transferencia,
                'usuario'=>$item->usuario->name,
                'juez'=>$item->juez->name,
                'provincia'=>$item->provincia->nombre,
                'finalizado' => $item->finalizado,
                'edit' => route('voyager.proyectos-juzgados.edit',[
                    'proyectos-juzgado'=>$item->id,
                    'id_proyecto'=>$item->id_proyecto
                ]),
                'view' => route('voyager.proyectos-juzgados.show',[
                    'proyectos-juzgado'=>$item->id,
                    'id_proyecto'=>$item->id_proyecto
                ]),
                'finalizar' => route('finalizar',$item->id)
            ];
        }
        $this->formattedCollection = $data;
    }
    public function formatCollection () {
        return json_encode($this->formattedCollection);
    }
}
