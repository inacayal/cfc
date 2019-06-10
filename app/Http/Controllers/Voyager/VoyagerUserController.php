<?php

namespace App\Http\Controllers\Voyager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Http\Controllers\VoyagerUserController as BaseVoyagerUserController;

class VoyagerUserController extends BaseVoyagerUserController
{
    public function __construct(Request $request){
        $this->middleware('user')->only('edit','destroy','update');
    }
    public function archivo (Request $request, $id, $archivo)
    {
        $user = Auth::user();
        $array_path = explode('/',$user->{$archivo});
        $path_to_file = implode('/',array_slice($array_path,2,count($array_path)-1));
        $delete = \Storage::disk('public')->delete('/'.$path_to_file);
        if ($delete)
        {
            $message =  [
                'message'    => "¡Has eliminado el archivo exitosamente!",
                'alert-type' => 'success',
            ];
            $user->update([$archivo => '/storage/usuarios/default/'.(($archivo==='dni_dorso' || $archivo==='dni_frente') ? 'dni.png' : 'foto_perfil.jpg')]);
        } else
            $message = [
                'message'    => "No has cargado ningun archivo",
                'alert-type' => 'error',
            ];       
        return redirect()
            ->route('voyager.users.edit', $user->id)
            ->with($message);
    }
}
