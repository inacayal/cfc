<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class AprobarAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Aprobar';
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
            'class' => 'btn btn-sm pull-right  btn-success left-margin blank-modal',
            'id' => $this->data->{$this->data->getKeyName()}
        ];
    }

    public function getDefaultRoute()
    {
        return route('aprobar',$this->data->{$this->data->getKeyName()});
    }
}