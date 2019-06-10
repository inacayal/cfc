<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Auth;

class VistaRapidaAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Vista Rápida';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'proyectos-juzgados' ;
    }
    public function getIcon()
    {
        return 'voyager-window-list';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm pull-right margin-left ver-evaluacion btn-oscuro',
            'data' => $this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return "";
    }
}