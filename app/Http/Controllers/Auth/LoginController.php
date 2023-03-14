<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
//use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    //protected $redirectTo = '/home';
    //protected $redirectTo = '/seguridad/roles/seleccionar';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        //$this->middleware('guest',['except' => ['seleccionarRol','ingresarSistema']]);
    }



    public function mostrarPagina(){
        return view('auth.login',['usuario'=>'','mensaje'=>'']);
    }

    public function login(Request $request){
        $credenciales= $request->only('login','password');
        if(Auth::attempt($credenciales)){
            $request->session()->regenerate(); //regenera sesion y token csrf, evita una vulnerabilidad, evita robo de session

            $usuario=Auth::user();//usuario autenticado
            session()->put("USUARIO_ID",$usuario->id);
            session()->put("USUARIO_NOMBRE",$usuario->persona->nombre);
            session()->put("USUARIO_PATERNO",$usuario->persona->paterno!=null?$usuario->persona->paterno:'');
            session()->put("USUARIO_MATERNO",$usuario->persona->materno!=null?$usuario->persona->materno:'');
            session()->put("SUCURSAL_ID",$usuario->sucursal!=null? $usuario->sucursal->id:'-1');
            session()->put("SUCURSAL_NOMBRE",$usuario->sucursal!=null? $usuario->sucursal->nombre:'');
            session()->put("AGENCIA_ID",$usuario->agencia!=null? $usuario->agencia->id:'-1');
            session()->put("AGENCIA_NOMBRE",$usuario->agencia!=null? $usuario->agencia->nombre:'');

            return redirect('/seguridad/roles/seleccionar');
        }
        return view('auth.login',['usuario'=>$request->login,'mensaje'=>'Credenciales incorrectas']);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }



}
