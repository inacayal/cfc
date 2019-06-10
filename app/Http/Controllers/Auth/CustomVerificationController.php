<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Notifications\VerificarCorreo;
use Illuminate\Auth\Events\Verified;
use \Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Encryption\DecryptException;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $email;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        if ($request->email)
            $this->email = $request->email;
    }
    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->user = $this->email ? User::where('email',$this->email)->first() : null;
        if (!is_null($this->user))
            if ($this->user->hasVerifiedEmail())
                return redirect('admin/usuario');
            else {
                return redirect($this->redirectTo)
                    ->with('alerta',$request->email.' tu correo no ha sido verificado');
            }
        return redirect($this->redirectTo)
            ->with(['alerta'=>$request->email.' debes registrarte para comenzar con tus postulaciones']);
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $this->user = $request->token ? User::where('verify_token',$request->token)->first() : null;
        if ($this->user)
        {
            if ($this->user->hasVerifiedEmail()) {
                return redirect($this->redirectTo)
                    ->with(['alerta'=>$this->user->name.', Ya verificaste tu usuario. Puedes comenzar tus postulaciones']);
            }
            if ($this->user->markEmailAsVerified($request->token)) 
            {   
                event(new Verified($this->user));
            }
            return redirect($this->redirectTo)
                ->with(['alerta'=>$this->user->name.', Ya verificaste tu usuario. Puedes comenzar tus postulaciones']);
        }
        return redirect($this->redirectTo)
                ->with(['alerta'=>'No te has registrado en el sistema']);
    }

    public function resend(Request $request)
    {
        $rules = [
            'email' => 'required|exists:users,email|max:100'
        ];
        $message = [
            'required' => 'Tienes que ingresar una direccion de correo.',
            'exists' => 'Debes registrarte para poder solicitar un reenvio',
            'max' =>'El correo no puede tener mas de 191 caracteres'
        ];
        $this->validate($request,$rules,$message);
        $this->user = $this->email ? User::where('email',$this->email)->first() : null;
        if ($this->user->hasVerifiedEmail()) {
            return redirect($this->redirectTo)
                ->with('alerta',$this->user->name.', Ya verificaste tu usuario. Puedes comenzar tus postulaciones');
        }
        //make a new hash if the user asks for new verification code
        $newKey = Str::random(150);
        $this->user->update(['verify_token' => $newKey]);
        $this->user->notify(new VerificarCorreo($this->user->email,$this->user->name,$newKey));

        return back()->with('alerta', 'Hemos enviado un nuevo correo de confirmacion a tu correo: '.$this->user->email);
    }
}
