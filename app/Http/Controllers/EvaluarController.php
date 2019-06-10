<?php

namespace App\Http\Controllers;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProyectosJuzgado as Evaluacion;

class EvaluarController extends VoyagerBaseController
{
    public function __construct(){
        $this->middleware('admin.judge.functions')->only('update','destroy','presentarEvaluacion');
    }

    public function presentarEvaluacion (Request $request,$id){
        $eval = Evaluacion::where('id',$id)->first();
        $proyecto = $eval->proyecto;
        if($proyecto->evaluaciones->count()===3){
            //si los tres jurados externos realizaron las evaluaciones, considerar la postulacion como evaluada.
            $proyecto->update(['id_estado'=>3]);
        }

        $eval->update(['finalizado'=>1]);

        return redirect()
            ->route('voyager.dashboard',$id)
            ->with([
                'message'    => "Has finalizado tu evaluación",
                'alert-type' => 'success',
            ]);
    }

    public function presentarEvaluacionMasiva (Request $request){
        $ids = explode(',',$request->ids);
        $queryBuild = Evaluacion::whereIn('id',$ids);
        $evaluados = [];

        foreach($queryBuild->get()->groupBy('id_proyecto') as $key=>$eval){
            if ($eval->count()===3){
                //si los tres jurados externos realizaron las evaluaciones, considerar la postulacion como evaluada.
                array_push($key);
            }
        }

        Proyecto::whereIn('id',$evaluados)->update(['id_estado'=>3]);
        $queryBuild->update(['finalizado'=>1]);

        return redirect()
            ->route('voyager.dashboard')
            ->with([
                'message'    => "Has finalizado la evaluación de ".count($ids)." postulaciones",
                'alert-type' => 'success',
            ]);
    }

    public function borradoMasivo (Request $request){
        $evaluaciones = explode(',',$request->ids);
        $delete = Evaluacion::whereIn('id',$evaluaciones)->delete();
        return redirect()
            ->route('voyager.dashboard')
            ->with([
                'message'    => "Has eliminado exitosamente la evaluación de ".count($evaluaciones)." postulaciones",
                'alert-type' => 'success',
            ]);
    }
}