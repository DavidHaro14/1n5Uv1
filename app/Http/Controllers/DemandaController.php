<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use insuvi\Http\Requests;
use insuvi\Cliente;
use insuvi\Programa;
use insuvi\TipoPrograma;
use insuvi\Demanda;
use insuvi\Credito;
use insuvi\Ahorrador;
use insuvi\CesionDerecho;

class DemandaController extends Controller
{
    public function index(Request $request)
    {
        $cliente = Cliente::search($request->buscar,$request->tipo)->where('estatus','=','1')->orderBy('ape_paterno')->paginate(25);

        return view('sistema_interno.Demanda.index')->with('clientes',$cliente);
    }

    public function form($id)
    {
        $cliente = Cliente::find($id);
    	$modulo		= enums('demanda','modulo');
    	$programa 	= Programa::where('estatus','=','1')->orderBy('nombre')->get();
    	return view('sistema_interno.Demanda.nuevo')->with('cli',$cliente)
    												->with('programas',$programa)
    												->with('modulos',$modulo);
    }

    public function crear(Request $request)
    {
        //dd($request);
        $demanda = new Demanda();

        $demanda->cliente_id        = $request->cliente;
        $demanda->tipo_programa_id  = $request->tipo_programa;
        $demanda->modulo            = $request->modulo;
        $demanda->tipo_cliente      = $request->tipo_cliente;
        $demanda->observaciones     = $request->observaciones;
        $demanda->plantilla         = $request->plantilla;
        $demanda->enganche          = $request->enganche;
        $demanda->registrado        = \Auth::user()->usuario;
        $demanda->estatus           = ($request->enganche != 0) ? "EN PROCESO" : "PREPARADO";

        $demanda->save();
        
        Flash::success("Se ha registrado correctamente la demanda.");
        
        if($request->enganche != 0){

            $ahorrador = new Ahorrador();

            /* Estructura Clave Credito Ahorrador */
            $clv_modulo    = substr($request->modulo, 0,2);
            $prog          = str_pad($request->programa, 2, "0", STR_PAD_LEFT);
            $Tprog         = str_pad($request->tipo_programa, 2, "0", STR_PAD_LEFT);
            $clv_ahorrador = str_pad($demanda->id_demanda, 5, "0", STR_PAD_LEFT);

            $ahorrador->clave_ahorrador = $clv_modulo . $prog . $Tprog . $clv_ahorrador . "A1";
            $ahorrador->monto_total     = $request->enganche;
            $ahorrador->demanda_id      = $demanda->id_demanda;

            $ahorrador->save();

            return view('sistema_interno.Ahorrador.clave_ahorrador')->with('ahorrador',$ahorrador);
        }
        
        return redirect()->route('credito',$demanda->id_demanda);
    }

    public function enganche(Request $request)
    {
        $dem = Demanda::enganche($request->buscar, $request->tipo)->where('tipo_cliente','=','AHORRADOR')->where('estatus','!=','ELIMINADO')->get();
        return view('sistema_interno.demanda.update-enganche')->with('demandas',$dem);
    }

    public function update(Request $request){

        $dem = Demanda::find($request->clave);

        $dem->ahorrador->monto_total = $request->enganche;
        $dem->actualizado = \Auth::user()->usuario;
        $dem->enganche    = $request->enganche;

        /*if($dem->ahorrador->total_abonado < $request->enganche){//Si es aceptable la modificacion

            $dem->save();

            return redirect()->route('demanda_enganche');

        } else */
        if($request->enganche == $dem->ahorrador->total_abonado){//Si la modificacion es igual al total abonado -> cumple como ahorrador y estara preparado para el credito

            //$dem->ahorrador->monto_total = $request->enganche;
            $dem->ahorrador->pagado = 1;

            //$dem->enganche     = $request->enganche;
            $dem->estatus      = "PREPARADO";
            $dem->tipo_cliente = "SOLICITANTE";

        } else if($dem->ahorrador->total_abonado > $request->enganche) {//no es aceptable la modificacion
            return redirect()->back()->withErrors(['No puede modificar el enganche menor que el total abonado del ahorrador.']);
        }

        $dem->ahorrador->save();
        $dem->save();

        return redirect()->route('demanda_enganche');
        
    }

    public function cesion_derecho(){
        $cre = Credito::where('estatus','=','PAGANDOLA')->orwhere('estatus','=','CONGELADA')->orwhere('estatus','=','CONGELADA CONVENIO')->orwhere('estatus','=','PAGADA')->get();
        $cli = Cliente::where('estatus','=','1')->get();
        return view('sistema_interno.Demanda.cesion_derecho')->with('creditos',$cre)->with('clientes',$cli);
    }

    public function add_cesion(Request $request){
        //dd($request);
        $credito                  = Credito::find($request->credito);

        $cesion                   = new CesionDerecho();
        $cesion->acuerdo          = $request->acuerdo;
        $cesion->cliente_anterior = $credito->demanda->cliente->nombre." ".$credito->demanda->cliente->ape_paterno." ".$credito->demanda->cliente->ape_materno;
        $cesion->curp_anterior    = $credito->demanda->cliente->curp;
        $cesion->demanda_id       = $credito->demanda->id_demanda;
        $cesion->registrado       = \Auth::user()->usuario;
        $cesion->save();

        $credito->demanda->cliente_id = $request->nuevo;
        $credito->demanda->save();

        return redirect()->route('insuvi');
    }

    public function view_demandas(Request $request){
        $modulo   = enums('demanda','modulo');
        $programa = Programa::where('estatus','=','1')->orderBy('nombre')->get();
        $demanda = \DB::table('demanda')->leftJoin('credito','demanda.id_demanda','=','credito.demanda_id')->Join('tipo_programa','tipo_programa.id_tipo_programa','=','demanda.tipo_programa_id')->Join('programa','programa.id_programa','=','tipo_programa.programa_id')->Join('cliente','cliente.id_cliente','=','demanda.cliente_id')->leftJoin('ahorrador','ahorrador.demanda_id','=','demanda.id_demanda')->whereRaw('credito.clave_credito IS NULL AND demanda.estatus != "ELIMINADO"')->select('demanda.*','tipo_programa.nombre AS tipo','programa.nombre AS programa','cliente.nombre','cliente.ape_materno','cliente.ape_paterno', 'ahorrador.*')->orderBy('demanda.id_demanda')->get();
        return view('sistema_interno.demanda.pendientes')->with('demandas',$demanda)->with('modulos',$modulo)->with('programas',$programa);
    }

    public function modificar_demanda(Request $request, $id){

        $dem_actual = Demanda::find($id);
        $v_programa = TipoPrograma::find($request->tipo);

        $dem_actual->modulo           = $request->modulo;
        $dem_actual->tipo_programa_id = $request->tipo;
        $dem_actual->observaciones    = $request->observaciones;
        $dem_actual->plantilla        = $v_programa->plantilla;
        $dem_actual->actualizado      = \Auth::user()->usuario;
        // Cambiar Solicitante en Ahorrador
        if ($dem_actual->tipo_cliente === "SOLICITANTE" && $dem_actual->enganche === 0.00 && $request->enganche > $dem_actual->enganche) {
            
            $dem_actual->tipo_cliente = "AHORRADOR";
            $dem_actual->estatus      = "EN PROCESO";
            $dem_actual->enganche     = $request->enganche;
            
            $ahorrador = new Ahorrador();
            /* Estructura Clave Credito Ahorrador */
            $clv_modulo    = substr($dem_actual->modulo, 0,2);
            $prog          = str_pad($request->programa, 2, "0", STR_PAD_LEFT);
            $Tprog         = str_pad($request->tipo, 2, "0", STR_PAD_LEFT);
            $clv_ahorrador = str_pad($dem_actual->id_demanda, 5, "0", STR_PAD_LEFT);
            $ahorrador->clave_ahorrador = $clv_modulo . $prog . $Tprog . $clv_ahorrador . "A1";
            $ahorrador->monto_total     = $request->enganche;
            $ahorrador->demanda_id      = $id;
            $ahorrador->save();
        }
        $dem_actual->save();

        return redirect()->route('view_demandas');
    }

    public function delete_demanda($id){

        $demanda = Demanda::find($id);
        $demanda->estatus     = "ELIMINADO";
        $demanda->actualizado = \Auth::user()->usuario;
        if ($demanda->ahorrador) {
            $demanda->ahorrador->eliminado = 1;
            $demanda->ahorrador->save();
        }
        $demanda->save();

        return redirect()->route('view_demandas');
    }
}
