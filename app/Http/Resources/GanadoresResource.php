<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GanadoresResource extends JsonResource
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
        foreach ($collect->groupBy('id_provincia') as $key=>$item){
            $max = $item->max('total');
            $winners = $item->where('total',$max);
            $winArray = [];
            foreach ($winners as $win){
                $user = $win->usuario;
                array_push($winArray,[
                    'provincia' => $win->provincia->nombre,
                    'nombre'=> $user->name,
                    'telefono' => $user->telefono,
                    'correo'=> $user->email,
                    'total'=> $win->total,
                    'dni' => $user->id,
                ]);
            }
            $data[$key]=$winArray;
        }
        $this->formattedCollection = $data;
    }
    public function formatCollection () {
        return json_encode($this->formattedCollection);
    }
}
