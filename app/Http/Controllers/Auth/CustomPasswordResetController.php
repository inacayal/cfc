<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TokenNuevaContrasena;
use App\Models\PasswordReset;
use Illuminate\Support\Str;

class CustomPasswordResetController extends Controller
{
    //user asking for password reset
    protected $user;
    //unresolved password resets
    protected $unresolved;
    //where to return
    protected $redirectTo = '/';

    public function __construct(Request $request){
        if ($request->email)
        {
            $this->user = User::where('email',$request->email)->first();
            if ($this->user){
                $pending = $this->user->resets->where('updated_at',null);
                if ($pending->count()>0)   
                    $this->unresolved = $pending->first();
            }
        }
    }

    public function enviarToken (Request $request)
    {
        //validate the email address requesting password reset
        $rules = [
            'email' => 'required|exists:users,email|max:100'
        ];
        $message = [
            'required' => 'Tienes que ingresar una direccion de correo.',
            'exists' => 'Tu usuario no esta registrado',
            'max' =>'El correo no puede tener mas de 100 caracteres'
        ];
        $this->validate($request,$rules,$message);
        if (!is_null($this->unresolved))
        {
            return redirect($this->redirectTo)
                ->with('alerta','Ya hemos enviado un código a tu correo '.$this->user->email.' para reestablecer tu contraseña. En caso de que no haya llegado, escribinos a través de becas@cfcultura.com.ar');    
        }
        $token = \Str::random(150);
        PasswordReset::create([
            'email' => $this->user->email,
            'reset_token' => $token
        ]);
        $this->user->notify(new TokenNuevaContrasena ($this->user->email,$this->user->name,$token));
        return redirect($this->redirectTo)
            ->with('alerta','Hemos enviado un código a tu correo: '.$this->user->email.' para que puedas cambiar tu contraseña');
    }

    public function validarToken (Request $request, $token)
    {
        $reset = PasswordReset::where('reset_token',$token)->first();
        if ($reset)
        {
            if ($reset->user)
                return view('auth.passwords.reset',['token'=>$token,'email'=>$reset->user->email]);
            return redirect($this->redirectTo)
                ->with('alerta','Código Inválido.');
        }
        return redirect($this->redirectTo)
            ->with('alerta','Código Inválido.');
        
    }

    public function actualizarContrasena(Request $request)
    {
        $rules = [
            'reset_token'=>'required|exists:password_reset,reset_token|min:150|max:150',
            'email'=>'required|exists:password_reset,email',
            'password' => 'required|string|min:8|confirmed'
        ];
        $message =[
            'email.exists' => 'Usuario no registrado',
            'token.exists' => 'Código invalido',
            'required' => 'El campo :attribute se requiere para que puedas completar tu solicitud',
            'confirmed' => 'Las contraseñas no coinciden',
            'min' => 'tu :attribute debe tener una longitud de :min',
            'max' => 'tu token no tiene la longitud requerida',
        ];
        $this->validate($request,$rules,$message);
        if($this->user)
        {
            if ($this->user->verificarToken($request->reset_token))
            {
                $this->user->crearContrasena($request->password);
                return redirect($this->redirectTo)
                    ->with('alerta','Has restablecido tu contraseña exitosamente');
            }
            return redirect($this->redirectTo)
                ->with('alerta','Código inválido. El código que has introducido ya fue usado. Tienes que solicitar uno nuevamente');
        }
        return redirect($this->redirectTo)
                    ->with('alerta','Usuario no registrado');
    }
}
