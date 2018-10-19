<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use insuvi\Http\Requests;
use insuvi\Empleado;
use insuvi\Usuario;
use insuvi\Caja;

class EmpleadoController extends Controller
{
    public function index()
    {
        $modulo   = enums('empleado','modulo');
        $empleado = Empleado::orderBy('nombre')->get();
    	$jefes    = Empleado::orderBy('nombre')->where('jefe','1')->where('emp_estatus','1')->get();

        $empleado->each(function($emp,$pos){
            $v_modulos             = explode("|", $emp->usuario->modulos);
            $emp->usuario->modulos = $this->NombrarModulos($v_modulos);
        });

        return view('sistema_interno.empleados.nuevo')->with('empleados',$empleado)
    	                                              ->with('jefes',$jefes)
                                                      ->with('modulos',$modulo);
    }

    public function agregar(Request $request)
    {
        $v_permisos = $request->permisos;
        $v_jefe     = $request->jefe;
        
        if ($v_permisos === null) {
            switch ($request->operaciones[0]) {
                case 'ADMON':
                    $v_permisos = "100";
                    $v_jefe     = "0";
                    break;
                
                case 'CAJA':
                    $v_permisos = "50";
                    break;
            }
        }

        $empleado = new Empleado();
        $usuario  = new Usuario();

        $usuario->usuario   = $request->usuario;
        $usuario->password  = bcrypt($request->password); 
        $usuario->modulos   = collect($request->operaciones)->implode('|');
        $usuario->perfil    = $request->perfil;
        $usuario->permisos  = $v_permisos;
        $usuario->save();

        $empleado->nombre     = $request->nombre;
        $empleado->apellido_p = $request->apellido_p;
        $empleado->apellido_m = $request->apellido_m;
        $empleado->telefono   = $request->telefono;
        $empleado->correo     = $request->correo;
        $empleado->modulo     = $request->modulo;
        $empleado->usuario_id = $usuario->id_usuario;
        $empleado->jefe       = ($v_jefe != "jefe") ? 0 : 1;
        $empleado->jefe_id    = ($v_jefe != "jefe") ? $v_jefe : 0;
        $empleado->save();

        Flash::success("Se ha creado correctamente.");
    	return redirect()->route('empleado_index');
    }

    public function estatus(Request $request, $id)
    {
        if ($request->jefe != null) {
            foreach ($request->empleados as $empleado) {
                $emp = Empleado::find($empleado);
                $emp->jefe_id = $request->jefe;
                //dd($emp);
                $emp->save();
            }
        }

        $empleado = Empleado::find($id);

        $empleado->usuario->estatus_us = !$empleado->usuario->estatus_us;
    	$empleado->emp_estatus         = !$empleado->emp_estatus;

        $empleado->usuario->save();
    	$empleado->save();
    	
    	return redirect()->route('empleado_index');
    }

    public function NombrarModulos($modulos){
        foreach ($modulos as $pos => $modulo) {
            switch ($modulo) {
                case 'SOLICITUD':
                    $modulos[$pos] = "ATENCION A LA SOLICITUD";
                    break;

                case 'ESTUDIO':
                    $modulos[$pos] = "ESTUDIO SOCIOECONOMICO";
                    break;

                case 'DEMANDA':
                    $modulos[$pos] = "ATENCION A LA DEMANDA";
                    break;

                case 'ENGANCHE':
                    $modulos[$pos] = "MODIFICACION DE ENGANCHES";
                    break;

                case 'DOMICILIO':
                    $modulos[$pos] = "MODIFICACION DE DOMICILIO";
                    break;

                case 'SAIV':
                    $modulos[$pos] = "REESTRUCTURA SAIV";
                    break;

                case 'CESION':
                    $modulos[$pos] = "CESION DE DERECHOS";
                    break;

                case 'ADMON':
                    $modulos[$pos] = "SUPER ADMINISTRADOR";
                    break;
            }
        }
        return $modulos;
    }
}
