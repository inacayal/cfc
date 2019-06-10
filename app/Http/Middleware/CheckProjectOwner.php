<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;

class CheckProjectOwner
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
        $proy = Proyecto::where('id',$request->route()->proyecto)->first();
        if ((int)$user->role_id === 1 || (int)$user->role_id===6){
            return $next($request);
        }
        return redirect('/');
        if ($user->id === $proy->id_persona){
            if($proy->id_estado === 1){
                return $next($request);
            }
        }
        return redirect('/');
    }
}
