<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class PresentarAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Presentar';
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
            'class' => 'btn btn-sm pull-right  btn-success',
            'id' => $this->data->{$this->data->getKeyName()}
        ];
    }

    public function getDefaultRoute()
    {
        return route('presentar',$this->data->{$this->data->getKeyName()});
    }
}