<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Proyecto;

class CheckProvAdmin
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
        if ((int)$user->role_id === 4 || (int) $user->role_id === 1){
            if($proy->provincia_usuario === $user->id_provincia)
                return $next($request);
            return redirect('/');
        }
        return redirect('/');
    }
}
