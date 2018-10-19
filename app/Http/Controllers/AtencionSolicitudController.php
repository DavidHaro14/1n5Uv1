<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use insuvi\Http\Requests;
use insuvi\Ocupacion;
use insuvi\Escolaridad;
use insuvi\Mejoramiento;
use insuvi\Estado;
use insuvi\Cliente;
use insuvi\Usuario;
use insuvi\Conyuge;
use insuvi\GrupoAtencion;
use insuvi\CambioDomicilio;

class AtencionSolicitudController extends Controller
{
    public function formulario()
    {
        $genero         = enums('cliente','genero');
        $civil          = enums('cliente','estado_civil');
        $grpsocial      = enums('cliente','grupo_social');
        $adquisi        = enums('cliente','adquisicion_vivienda');
        $autocons       = enums('cliente','autoconstruccion');
        $bienes         = enums('conyuge','bienes');
        $escolaridad    = enums('cliente','escolaridad');
        $interes        = enums('cliente','zona_interes');

    	$mej 	= Mejoramiento::where('estatus','=','1')->orderBy('nombre')->get();
    	$ocup 	= Ocupacion::where('estatus','=','1')->orderBy('nombre')->get();
    	$est 	= Estado::where('estatus','=','1')->orderBy('nombre')->get();
        $aten   = GrupoAtencion::where('estatus','=','1')->orderBy('nombre')->get();

    	return view('sistema_interno.AtencionSolicitud.nuevo')	->with('mejoramientos',$mej)
    															->with('ocupaciones',$ocup)
    															->with('escolaridades',$escolaridad)
    															->with('estados',$est)
                                                                ->with('grupos',$aten)
                                                                ->with('generos',$genero)
                                                                ->with('civiles',$civil)
                                                                ->with('social',$grpsocial)
                                                                ->with('adquisicion',$adquisi)
                                                                ->with('autoconstruccion',$autocons)
                                                                ->with('bienes',$bienes)
                                                                ->with('interes',$interes);
    }

    public function agregar(Request $request)
    {

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

        $cliente = new Cliente();

        $cliente->nombre               = $request->nombre;
        $cliente->ape_paterno          = $request->ape_p;
        $cliente->ape_materno          = $request->ape_m;
        $cliente->curp                 = $request->curp;
        $cliente->estado_civil         = $request->estado_civil;
        $cliente->genero               = $request->genero;
        $cliente->fecha_nac            = $request->fecha_nac;
        $cliente->correo               = $request->correo;
        $cliente->telefono             = $request->tel;
        $cliente->estado_nac           = $request->estado_nac;
        $cliente->lugar_nac            = $request->lugar_nac;
        $cliente->dependientes         = $request->dependientes;
        $cliente->escolaridad          = $request->escolaridad;
        $cliente->ocupacion_id         = $request->ocupacion;
        $cliente->grupo_social         = $request->grupo_social;
        $cliente->calle_id             = $request->calle;
        $cliente->num_int              = $request->num_int;
        $cliente->num_ext              = $request->num_ext;
        $cliente->referencia_calles    = "ENTRE " . $request->referencia . ", " . $request->referencia2 . " Y " . $request->referencia3;
        $cliente->descripcion_ubicacion= $request->ubicacion;
        $cliente->codigo_postal        = $request->codigo_postal;
        $cliente->mejoramiento_id      = $request->mejoramiento;
        $cliente->adquisicion_vivienda = $request->adquisicion;
        $cliente->autoconstruccion     = $request->autoconstruccion;
        $cliente->zona_interes         = $request->zona_interes;
        $cliente->grupo_atencion_id    = $request->grupo_atencion;
        $cliente->usuario_id           = 0;
        $cliente->creado               = \Auth::user()->usuario;
        $cliente->save();

        if($request->estado_civil == "CASADO(A)" || $request->estado_civil == "UNION LIBRE" || $request->estado_civil == "VIUDO(A)"){

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
            
            $conyuge = new Conyuge();
            $conyuge->nombre              = $request->nombre_conyuge;
            $conyuge->ape_paterno         = $request->ape_p_conyuge;
            $conyuge->ape_materno         = $request->ape_m_conyuge;
            $conyuge->curp                = $request->curp_conyuge;
            $conyuge->fecha_nac           = $request->fecha_nac_conyuge;
            $conyuge->lugar_nac           = $request->lugar_nac_conyuge;
            $conyuge->bienes              = $request->bienes;
            $conyuge->cliente_id          = $cliente->id_cliente;
            $conyuge->save();
        }

        Flash::success("Se ha registrado correctamente.");
        return view('sistema_interno.AtencionSolicitud.continuar')->with('solicitante',$cliente);

    }

    public function view_update(Request $request){
        
        $dom = ($request->tipo == "domicilio") ? 1 : 0;

        $cli = Cliente::search($request->buscar,$request->tipo)->where('cliente.estatus','=','1')->paginate(10);
        
        $est = Estado::where('estatus','=','1')->orderBy('nombre')->get();

        return view('sistema_interno.Cliente.update')->with('clientes', $cli)
                                                     ->with('estados', $est)
                                                     ->with('domicilio', $dom);
    }

    public function update(Request $request){

        $cl = Cliente::find($request->clave);

        $cam = new CambioDomicilio();
        $cam->domicilio = $cl->calle->nombre . " # " . $cl->num_ext . $cl->num_int . ", Colonia: " . 
                            $cl->calle->colonia->nombre . ", Localidad: " . 
                            $cl->calle->colonia->localidad->nombre . ", " . 
                            $cl->calle->colonia->localidad->municipio->nombre . ", " . 
                            $cl->calle->colonia->localidad->municipio->estado->nombre;
        $cam->cliente_id =  $cl->id_cliente;
        $cam->creado     =  \Auth::user()->usuario;
        $cam->save();

        $cl->calle_id = $request->calle;
        $cl->num_ext  = $request->num_ext;
        $cl->num_int  = $request->num_int;

        $cl->save();

        return redirect()->route('cliente_view_update');
    }

    public function view_cliente(Request $request, $id = 0){
        $modulos   = \Auth::user()->modulos;
        $caja  = str_contains($modulos,'CAJA');
        if (!$caja) {
            if ($id != 0) {
                $cliente = Cliente::find($id);
                return view('sistema_interno.Cliente.detalle')->with('cliente',$cliente);
            }

            $dom = ($request->tipo == "domicilio") ? 1 : 0;
            $cliente = Cliente::search($request->buscar,$request->tipo)->orderBy('nombre')->paginate(30);

            return view('sistema_interno.Cliente.info') ->with('clientes', $cliente)
                                                        ->with('domicilio', $dom);
        }
        return redirect()->route('insuvi')->withErrors('Acceso Denegado');
    }

    public function estatus_cliente($id){
        $cliente = Cliente::find($id);
        $cliente->estatus = !$cliente->estatus;
        $cliente->save();
        return redirect()->route('view_cliente');
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
