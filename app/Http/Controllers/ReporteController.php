<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use insuvi\Http\Requests;
use insuvi\SolicitudEliminacion;

class ReporteController extends Controller
{
    public function reporte_solicitudes(Request $request){
    	$solicitudes = SolicitudEliminacion::Solicitante($request->buscar,$request->tipo)->where('status','!=','PENDIENTE')->orderBy('created_at','DESC')->get();
    	return view('sistema_interno.Solicitud.reporte')->with('solicitudes',$solicitudes);
    }
}
