<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class SeleccionarAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Seleccionar';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'proyectos' ;
    }
    public function getIcon()
    {
        return 'voyager-check';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm pull-right  btn-primary blank-modal',
            'id' => $this->data->{$this->data->getKeyName()}
        ];
    }

    public function getDefaultRoute()
    {
        return route('seleccionar',$this->data->{$this->data->getKeyName()});
    }
}