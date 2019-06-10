<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\VerificarCorreo;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'id' => ['required', 'string', 'min:8','max:8', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ];
        $message = [
            'required' => 'Tienes que llenar todos los campos para poder registrarte',
            'email.unique' => 'ya existe un usuario con ese correo',
            'id.unique' =>'ya existe un usuario con ese DNI',
            'confirmed' => 'las contraseñas no coinciden',
            'password.min' => 'la contraseña debe tener 8 caracteres',
            'id.min'=>'el dni debe tener 8 caracteres',
            'id.max'=>'el dni debe tener 8 caracteres'
        ];
        return ['rules'=>$rules,'message'=>$message];
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $val = $this->validator($request->all());
        $val = $this->validate($request,$val['rules'],$val['message']);
        $user = $this->create($request->all());
        $user->notify(new VerificarCorreo($user->email,$user->name,$user->verify_token));
        return redirect($this->redirectTo)
            ->with('alerta','Te has registrado exitosamente, hemos enviado un correo de confirmacion a tu direccion: '.$user->email);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $obj = new \stdClass();
        $obj->locale = 'es';
        $user =  User::create([
            'name' => $data['name'],
            'role_id' => 2,
            'foto_perfil' => '/storage/usuarios/default/foto_perfil.jpg',
            'dni_frente' => '/storage/usuarios/default/dni.png',
            'dni_dorso' => '/storage/usuarios/default/dni2.png',
            'email' => $data['email'],
            'id' => $data['id'],
            'password' => Hash::make($data['password']),
            'settings' => collect($obj),
            'verify_token' => Str::random(150),
            'path' => Str::random(50)
        ]);
            
        return $user;
    }
}
