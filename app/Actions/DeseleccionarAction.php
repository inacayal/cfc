<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class DeseleccionarAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Deseleccionar';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'proyectos' ;
    }
    public function getIcon()
    {
        return 'voyager-x';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm pull-right  btn-danger blank-modal',
            'id' => $this->data->{$this->data->getKeyName()}
        ];
    }

    public function getDefaultRoute()
    {
        return route('deseleccionar',$this->data->{$this->data->getKeyName()});
    }
}