<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use insuvi\Http\Requests;
use Laracasts\Flash\Flash;
use insuvi\Seguimiento;
use insuvi\Credito;
use insuvi\Situacion;

class SeguimientoController extends Controller
{
    /* Seguimiento */
	public function seguimiento(Request $request)
	{
		$busqueda = false;
		if (count($request->buscar) != 0) {
			//$cre = Credito::select('credito.*',\DB::raw('count(*) as vencidos'))->buscar($request->buscar,$request->tipo)->where('credito.estatus','=','PAGANDOLA')->paginate(25);//->orWhere('credito.estatus','=','PAGADA')->paginate(25);
			$cre = Credito::select('credito.*',\DB::raw('count(*) as vencidos'))->join('mensualidad','credito.clave_credito','=','mensualidad.credito_clave')->buscar($request->buscar,$request->tipo)->whereRaw('(credito.estatus = "PAGANDOLA" or credito.estatus = "CONGELADA" or credito.estatus = "CONGELADA CONVENIO")')/*->where('credito.estatus','=','PAGANDOLA')->orwhere('credito.estatus','=','CONGELADA')->orwhere('credito.estatus','=','CONGELADA CONVENIO')*/->groupBy('credito.clave_credito')->paginate(25);
			$busqueda = true;
		} else {
			$cre = Credito::select('credito.*',\DB::raw('count(*) as vencidos'))->join('mensualidad','credito.clave_credito','=','mensualidad.credito_clave')->where('mensualidad.estatus','=','VENCIDO')->where('credito.estatus','=','PAGANDOLA')->groupBy('credito.clave_credito')->paginate(25);
		}
		return view('sistema_interno.Seguimiento.index_seguimiento')->with('creditos',$cre)
																	->with('busqueda',$busqueda);
	}

	public function seguimiento_credito($clave){
		$GLOBALS['convenio'] 	 = 0;
		$GLOBALS['descuento']	 = 0;
		$GLOBALS['congelacion']  = 0;
		$situacion = Situacion::where('status','=','1')->get();
		$seg = Seguimiento::where('credito_clave','=',$clave)->orderBy('estatus_seguimiento','desc')->paginate(25);
		$cre = Credito::find($clave);
		if($seg){
			$seg->each(function($seguimiento, $pos){
				if ($seguimiento->estatus_seguimiento) {
					switch ($seguimiento->restriccion) {
						case 'CONVENIO':
							$GLOBALS['convenio'] = 1;
							break;
						case 'DESCUENTO':
							$GLOBALS['descuento'] = 1;
							break;
						case 'CONGELACION':
							$GLOBALS['congelacion'] = 1;
							break;
						default:
							break;
					}
				}
			});
		}
		if($cre->estatus == "CANCELADA"){return redirect()->route('seguimiento');}
		return view('sistema_interno.Seguimiento.seguimiento_credito')
																	->with('situaciones',$situacion)
																	->with('seguimientos',$seg)
																	->with('convenio',$GLOBALS['convenio'])
																	->with('descuento',$GLOBALS['descuento'])
																	->with('congelacion',$GLOBALS['congelacion'])
																	->with('clave',$clave);
	}

	public function add_seguimiento(Request $request)
	{
		//dd($request);
		$seg = new Seguimiento();
		$seg->credito_clave   		  = $request->credito;
		$seg->situacion_id 	  		  = $request->situacion;
		$seg->restriccion 	  		  = $request->restriccion;
		$seg->monto_convenio  		  = $request->monto;
		$seg->descripcion_seguimiento = $request->descripcion;
		$seg->registrado			  = \Auth::user()->usuario;
		$seg->save();

		if ($seg->restriccion == "CONVENIO") {
			$this->estatus_credito('CONGELADA CONVENIO',$seg->credito_clave);
		} else if($seg->restriccion == "CONGELACION") {
			$this->estatus_credito('CONGELADA',$seg->credito_clave);
		}
		
		Flash::success("Se ha credo el seguimiento correctamente.");
		return redirect()->route('seguimiento_credito',$seg->credito_clave);
	}

	public function seguimiento_estatus(Request $request)
	{
		//dd($request);
		$seg = Seguimiento::find($request->id);
		$seg->estatus_seguimiento = "0";
		$seg->save();
		
		$this->estatus_credito('PAGANDOLA',$seg->credito_clave);

		return redirect()->route('seguimiento_credito',$seg->credito_clave);
	}

	//modificar cantidad del convenio
	public function update_convenio($id, Request $request)
	{
		//dd($request);
		$seg = Seguimiento::find($id);
		
		if ($seg->abonado_convenio == $request->monto) {
			$seg->convenio_pagado = "1";
		}
		
		$seg->monto_convenio = $request->monto;

		$seg->save();
		
		return redirect()->route('seguimiento_credito',$seg->credito_clave);
	}

	function estatus_credito($estatus,$clave){
		//dd($clave);
		$cre = Credito::find($clave);
		$cre->estatus = $estatus;
		$cre->demanda->estatus = ($estatus == "PAGANDOLA") ? "EN PROCESO" : "CONGELADA";
		$cre->demanda->save();
		$cre->save();
		//return redirect()->route('seguimiento_credito',$cre->clave_credito);
	}
}
