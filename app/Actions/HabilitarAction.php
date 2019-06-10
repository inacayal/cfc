<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class HabilitarAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Habilitar';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'proyectos' ;
    }
    public function getIcon()
    {
        return 'voyager-pen';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm pull-right  btn-primary left-margin blank-modal',
            'id' => $this->data->{$this->data->getKeyName()}
        ];
    }

    public function getDefaultRoute()
    {
        return route('habilitar',$this->data->{$this->data->getKeyName()});
    }
}