<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class PresentarEvaluacionAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Finalizar';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'proyectos-juzgados';
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
                'class' => 'btn btn-sm pull-right btn-success blank-modal margin-left',
                'data' => $this->data->{$this->data->getKeyName()}
            ];
    }

    public function getDefaultRoute()
    {
        return route('finalizar',$this->data->{$this->data->getKeyName()});
    }
}