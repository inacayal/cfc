<?php

namespace App\Http\Controllers;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ProyectoPresentado;
use TCG\Voyager\Models\DataType;
use App\Models\ProyectosJuzgado;
use TCG\Voyager\Facades\Voyager;

class ProyectoController extends VoyagerBaseController
{
    public function __construct(){
        $this->middleware('proyecto')->only('presentar','edit','destroy','update');
        $this->middleware('admin.cfc.functions')->only('habilitar','aceptar','desaprobar');
        $this->middleware('admin.prov.functions')->only('seleccionar','deseleccionar');
    }
    public function updatePostulacion ($id,$estado,$mensaje){
        $update = Proyecto::where('id',$id)
                ->update(['id_estado'=> $estado]);
        return [
            'message' => $mensaje,
            'alert-type' => 'success'
        ];
    }
    public function presentar(Request $request, $id)
    {
        $user = \Auth::user();
        if((int)$user->role_id===2){
            return redirect('/');
            $user->notify(new ProyectoPresentado ($user->email,$user->name));
        }
        $user->notify(new ProyectoPresentado ($user->email,$user->name));
        $update = Proyecto::where('id',$id)
            ->update(['bases_condiciones'=>1,'id_estado'=> 4]);
        return redirect()
            ->route("voyager.welcome.user")
            ->with([
                    'message'    => "¡Has presentado tu postulación exitosamente!",
                    'alert-type' => 'success',
                ]);
    }

    public function habilitar(Request $request, $id)
    {
        $user = \Auth::user();
        $msj = $this->updatePostulacion($id,1,'¡La postulación ha sido reactivada!');
        return redirect()
            ->route("voyager.dashboard")
            ->with($msj);
        return redirect('/');
    }
    public function seleccionar(Request $request, $id)
    {
        $user = \Auth::user();
        $selected = $user->getSelectedNumber();
        if($selected>0)
            $msj = $this->updatePostulacion($id,2,"¡La postulación ha sido seleccionada!");
        else $msj = [
            'message'    => "¡Ya seleccionaste 5 postulaciones, no puedes seleccionar más!",
            'alert-type' => 'error',
        ];
        return redirect()
            ->route("voyager.dashboard")
            ->with($msj);
        return redirect('/');
    }
    public function deseleccionar(Request $request, $id)
    {
        $user = \Auth::user();
        $msj = $this->updatePostulacion($id,5,"¡Has revertido el estado de la postulacion a aprobada!");
        return redirect()
            ->route("voyager.dashboard")
            ->with($msj);
        return redirect('/');
    }
    public function aprobar(Request $request, $id)
    {
        $user = \Auth::user();
        $msj = $this->updatePostulacion($id,5,"¡La postulación ha sido aprobada!");
        return redirect()
            ->route("voyager.dashboard")
            ->with($msj);
    }
    public function desaprobar(Request $request, $id)
    {
        $user = \Auth::user();
        $msj = $this->updatePostulacion($id,4,"¡La postulación ya no esta aprobada!");
        return redirect()
            ->route("voyager.dashboard")
            ->with($msj);
    }
    public function casoProyecto ($slug,$request,$usuario_proyecto,$id) 
    {
        $copy = $request->all();
        if(isset($copy[$usuario_proyecto]) && isset($copy['id_categoria'])){
            if($copy['id_categoria'] === '2' ){
                if (isset($id))
                    \DB::table('miembros_proyecto')->where('proyecto_id',$id)->delete();
                $miembros = $copy[$usuario_proyecto]; 
                array_push($miembros,\Auth::user()->id);
                $request->merge([$usuario_proyecto => $miembros]);
            }
        }
    }
    public function eliminarArchivos ($id,$carpeta) {
        $path = 'proyectos/'.$id.'/'.$carpeta;
        if (\Storage::disk('public')->exists($path)){
            if(\Storage::disk('public')->deleteDirectory($path)){
                $message =  [
                    'message'    => "¡Has eliminado el archivo exitosamente!",
                    'alert-type' => 'success',
                ];
                Proyecto::where('id',$id)->first()->update([$carpeta => '{"path":[]}']);
            }
        }else
            $message = [
                'message'    => "No has cargado ningun archivo",
                'alert-type' => 'error',
            ];   
        return redirect()
            ->route('voyager.proyectos.edit', $id)
            ->with($message);
    }
    public function crearPdf () {
        $user = Auth::user();
        if( (int) $user->role_id === 4){
            $postulaciones = Proyecto::where('provincia_usuario',$user->id_provincia)->whereIn('id_estado',[5,2])->get();
            $format = new PostulacionesResource($postulaciones);
            $data = $format->formatCollection();
            $gastos = [
                "Materiales",
                "Viaticos",
                "Alojamiento",
                "Equipamento",
                "Pasajes",
                "Alquiler",
                "Honorarios",
                "Otros"
            ];
            return Voyager::view('voyager::postulaciones-list',compact('data','postulaciones','gastos'));
        }
        redirect('/');
    }
}
