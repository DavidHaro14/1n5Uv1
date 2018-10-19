<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use insuvi\Http\Requests;
use insuvi\PreRegistro;
use insuvi\Ocupacion;
use Carbon\Carbon;

class PreRegistroController extends Controller
{
    public function form()
    {
        //dd(Carbon::now()->day.Carbon::now()->month.substr(Carbon::now()->year,-2,2)."-IN-".rand(1, 9999));//Formato de la clave registro: dia/mes/año-IN-consecutivo/random
        $genero         = enums('pre_registro','genero');
        $civil          = enums('pre_registro','estado_civil');
        $bienes         = enums('pre_registro','bienes');
        $escolaridad    = enums('pre_registro','escolaridad');

        $ocup = Ocupacion::where('estatus','=','1')->get();
    	return view('principal.preregistro.solicitud')->with('ocupaciones',$ocup)
                                                      ->with('generos',$genero)
                                                      ->with('civiles',$civil)
                                                      ->with('bienes',$bienes)
                                                      ->with('escolaridades',$escolaridad);
    }

    public function agregar(Request $request)
    {
        //* Validacion Inputs(Request) *//
        $this->validate($request, [
            'curp'                  => 'between:18,18|required',
            'curp_conyuge'          => 'between:18,18',
            'correo'                => 'unique:pre_registro,correo|unique:cliente,correo',
            'password'              => 'between:8,20|required|confirmed',
            'password_confirmation' => 'required',
            //'g-recaptcha-response' => 'required|captcha'
        ],[
            'curp.between'                  => 'El campo CURP debe contener 18 Caracteres',
            'curp_conyuge.between'          => 'El campo CURP Conyuge debe contener 18 Caracteres',
            'correo.unique'                 => 'El correo ya esta registrado.',
            'password.between'              => 'El campo Contraseña debe contener entre 8 y 20 Caracteres',
            'password.required'             => 'El campo Contraseña es obligatorio',
            'password.confirmed'            => 'Las Contraseñas no coincide',
            'password_confirmation.required'=> 'El campo Confirmar Contraseña es obligatorio',
        ]);

        /*
         * VALIDACION DEL FORMATO DE LA CURP DEL SOLICITANTE
         */

        //* Descomposicion de la CURP del Solicitante *//
        $sol_inical     = substr($request->curp, 0, 4);
        $sol_num        = substr($request->curp, 4, 6);       
        $sol_genero     = substr($request->curp, 10, 1);
        $sol_estado     = substr($request->curp, 11, 2);
        $sol_letra      = substr($request->curp, 13, 3);
        $sol_homoclave  = substr($request->curp, 16, 2);

        if(!(ctype_alpha($sol_inical) && ctype_alpha($sol_letra) && ctype_digit($sol_num) && ctype_digit($sol_homoclave) && $this->is_mx_state($sol_estado) && $this->is_sexo_curp($sol_genero)))
        {
            return redirect()->back()->withErrors(['El formato de la CURP es incorrecto.']);//REDIRECCIONAR ATRAS CON EL ERROR DE COMPOSICION (CURP)
        }

        /*
         * SI LA CURP DEL CONYUGE ES DIFERENTE A VACIO => VALIDAR EL FORMATO DE LA CURP DEL CONYUGE
         */

        if ($request->curp_conyuge != "") {

            //* Descomposicion de la CURP del Conyuge *//
            $cony_inical     = substr($request->curp_conyuge, 0, 4);
            $cony_num        = substr($request->curp_conyuge, 4, 6);       
            $cony_genero     = substr($request->curp_conyuge, 10, 1);
            $cony_estado     = substr($request->curp_conyuge, 11, 2);
            $cony_letra      = substr($request->curp_conyuge, 13, 3);
            $cony_homoclave  = substr($request->curp_conyuge, 16, 2);

             if(!(ctype_alpha($cony_inical) && ctype_alpha($cony_letra) && ctype_digit($cony_num) && ctype_digit($cony_homoclave) && $this->is_mx_state($cony_estado) && $this->is_sexo_curp($cony_genero)))
            {
                return redirect()->back()->withErrors(['El formato de la CURP (Conyuge) es incorrecto.']);//REDIRECCIONAR ATRAS CON EL ERROR DE COMPOSICION (CURP)
            }
        } 

        /*
         * CREAR EL PREREGISTRO
         */
        
        $registro = new PreRegistro();

        $registro->solicitante_nombre       = $request->nombre;
        $registro->solicitante_ape_paterno  = $request->ape_paterno;
        $registro->solicitante_ape_materno  = $request->ape_materno;
        $registro->solicitante_curp         = $request->curp;
        $registro->genero                   = $request->genero;
        $registro->correo                   = $request->correo;
        $registro->telefono                 = $request->tel;
        $registro->fecha_nac                = $request->fecha_nac;
        $registro->estado_nac               = $request->estado_nac;
        $registro->lugar_nac                = $request->lugar_nac;
        $registro->no_dependientes          = $request->dependientes;
        $registro->estado_civil             = $request->estado_civil;
        $registro->conyuge_nombre           = $request->nombre_conyuge;
        $registro->conyuge_ape_paterno      = $request->ape_p_conyuge;
        $registro->conyuge_ape_materno      = $request->ape_m_conyuge;
        $registro->conyuge_curp             = $request->curp_conyuge;
        $registro->conyuge_fecha_nac        = $request->fecha_nac_conyuge;
        $registro->conyuge_lugar_nac        = $request->lugar_nac_conyuge;
        $registro->bienes                   = $request->bienes;
        $registro->clave_registro           = Carbon::now()->year."-INSUVI-".rand(1, 9999);
        $registro->password                 = encrypt($request->password);
        $registro->ocupacion_id             = $request->ocupacion;
        $registro->escolaridad              = $request->escolaridad;

        $registro->save();

        return view('principal.PreRegistro.clave')->with('solicitante',$registro);
    }

    /*
     * COMPOSICION DE LA CURP (ESTADO)
     */
    
    public function is_mx_state($state){    
        $mxStates = [        
            'AS','BS','CL','CS','DF','GT',        
            'HG','MC','MS','NL','PL','QR',        
            'SL','TC','TL','YN','NE','BC',        
            'CC','CM','CH','DG','GR','JC',        
            'MN','NT','OC','QT','SP','SR',        
            'TS','VZ','ZS'    
        ];    
        if(in_array($state,$mxStates)){    
            return true;    
        }    
        return false;
    }

    /*
     * COMPOSICION DE LA CURP (SEXO)
     */

    public function is_sexo_curp($sexo){   
        $sexoCurp = ['H','M'];   
        if(in_array($sexo,$sexoCurp)){    
           return true;   
        }   
        return false;
    }
}
