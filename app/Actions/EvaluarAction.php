<?php

namespace App\Actions;
use App\Models\ProyectosJuzgado;
use TCG\Voyager\Actions\AbstractAction;

class EvaluarAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Evaluar';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'proyectos' ;
    }
    public function getIcon()
    {
        return 'voyager-receipt';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm pull-right btn-primary',
            'id' => $this->data->{$this->data->getKeyName()}
        ];
    }

    public function getDefaultRoute()
    {
        $puntuacion = ProyectosJuzgado::where('id_proyecto',$this->data->{$this->data->getKeyName()})->first();
        if ($puntuacion){
            return route('voyager.proyectos-juzgados.edit',[
                'proyectos_juzgado'=>$puntuacion->id,
                'id_proyecto'=>$this->data->{$this->data->getKeyName()}
            ]);
        }
        return route('voyager.proyectos-juzgados.create',['id_proyecto'=>$this->data->{$this->data->getKeyName()}]);
    }
}