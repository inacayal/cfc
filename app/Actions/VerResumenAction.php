<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Auth;

class VerResumenAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Ver Resumen';
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug === 'proyectos-juzgados' ;
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
            'class' => 'btn btn-sm pull-right margin-left ver-resumen btn-oscuro',
            'proyecto' => $this->data->id_proyecto
        ];
    }

    public function getDefaultRoute()
    {
        return null;
    }
}