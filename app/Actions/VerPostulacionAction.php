<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Auth;

class VerPostulacionAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Ver Postulación';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug === 'proyectos-juzgados' ;
    }
    public function getIcon()
    {
        return 'voyager-documentation';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm pull-right btn-warning',
            'data' => $this->data->id_proyecto,
            'target'=>'_blank'
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.proyectos.show',$this->data->id_proyecto);
    }
}