<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\ProyectosJuzgado;

class CheckJudgePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if ((int)$user->role_id === 1 ){
            return $next($request);
        }
        $eval = ProyectosJuzgado::where('id',$request->route()->proyectos_juzgado)->first();
        if ((int)$user->role_id === 5 && (int)$eval->finalizado === 0){
            if($eval->id_juez === Auth::user()->id){
                return $next($request);
            }
        }
        return redirect('/');
    }
}
