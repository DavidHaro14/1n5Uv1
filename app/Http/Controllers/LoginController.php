<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use insuvi\Http\Requests;

class LoginController extends Controller
{
    public function autentificar(Request $request){
    	if(\Auth::guard('web')->attempt(['usuario' => $request->usuario, 'password' => $request->password])) {
    		if(\Auth::user()->estatus_us){
    			return redirect()->route('insuvi');
    		} else {
    	       return redirect()->route('login')->withErrors('Usuario Suspendido');
            }
        } 
        return redirect()->route('login')->withErrors('Usuario o Contraseña son Incorrectos');
    }

    public function logout(){
    	\Auth::logout();
    	return redirect()->route('login');
    }
}
