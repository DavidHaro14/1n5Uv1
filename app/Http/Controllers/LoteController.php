<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use insuvi\Http\Requests;
use insuvi\Lote;
use insuvi\Estado;
use Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LoteController extends Controller
{
    public function index()
    {
    	$lote   = Lote::orderBy('no_manzana')->orderBy('no_lote')->get();
    	$estado = Estado::orderBy('nombre')->get();
    	return view('sistema_interno.Catalogos.lote')->with('lotes',$lote)
    												 ->with('estados',$estado);
    }

    public function agregar(Request $request)
    {
    	//dd($request->file('lotes'));
    	$archivo 			= $request->file('lotes');
    	$nombre_original 	= $archivo->getClientOriginalName();
    	$extension 			= $archivo->getClientOriginalExtension();
        if($extension == "xls" || $extension == "xlsx"){
        //dd($extension);
        	$r1 				= Storage::disk('lotes')->put($nombre_original, File::get($archivo));
        	$ruta				= storage_path('lotes') . "/" . $nombre_original;

        	if($r1)
        	{
        		Excel::selectSheetsByIndex(0)->load($ruta, function($hoja) use($request) {

        			$hoja->each(function($fila) use($request){
        				$lot = Lote::where("clave_catastral", "=", $fila->clave_catastral)->first();
                        if($fila->manzana == ""){
                            //Flash::success("Se ha importado los lotes con exito!");
                            return redirect()->route('lote_index');
                        } else if(count($lot) == 0){
        					$lote = new Lote;
        					$lote->no_manzana 		= $fila->manzana;
        					$lote->no_lote 			= $fila->lote;
        					$lote->superficie 		= $fila->superficie;
        					$lote->norte 			= $fila->norte;
        					$lote->sur 				= $fila->sur;
        					$lote->este 			= $fila->este;
        					$lote->oeste 			= $fila->oeste;
        					$lote->noreste 			= $fila->noreste;
        					$lote->sureste 			= $fila->sureste;
        					$lote->suroeste 		= $fila->suroeste;
        					$lote->noroeste 		= $fila->noroeste;
        					$lote->ochavo 			= $fila->ochavo;
        					$lote->uso_suelo 		= $fila->uso_suelo;
        					$lote->clave_catastral 	= $fila->clave_catastral;
        					$lote->calle 			= $fila->domicilio;
        					$lote->numero 			= $fila->numero;
        					$lote->colonia_id 		= $request->fraccionamiento;
        					$lote->save();
        				}
        			});
        		});

                Flash::success("Se ha importado los lotes con éxito!");
            	return redirect()->route('lote_index');
        	}
        	else
        	{
            	return redirect()->back()->withErrors(['Error al cargar el archivo.']);
        	}

        } else{
            return redirect()->back()->withErrors(['Favor de seleccionar un archivo con extensión XLS o XLSX del formato excel.']);
        } 
    }

    public function division_lote($id){
        $lote = Lote::find($id);
        return view("sistema_interno.Lote.dividir_lote")->with('lote',$lote);
    }

    public function add_division(Request $request, $id){
        //dd("ENTRO");
        $ant = Lote::find($id);
        $ant->superficie = $request->superficie_actual - $request->nueva_superficie;
        $ant->save();

        $pos_lote = strpos($ant->no_lote, '/');
        $pos_nume = strpos($ant->numero, '/');

        $v_lote = ($pos_lote === false) ? $ant->no_lote : substr($ant->no_lote, 0, $pos_lote);
        $v_nume = ($pos_nume === false) ? $ant->numero : substr($ant->numero, 0, $pos_nume);

        $new = new Lote();
        $new->clave_catastral = ($request->clave_catastral != "") ? $request->clave_catastral : NULL;
        $new->colonia_id = $ant->colonia_id;
        $new->calle      = $ant->calle;
        $new->numero     = $ant->numero . "/" . $request->letra;
        $new->no_manzana = $ant->no_manzana;
        $new->no_lote    = $ant->no_lote . "/" . $request->letra;
        $new->superficie = $request->nueva_superficie;
        $new->uso_suelo  = $request->uso_suelo;
        $new->ochavo     = $request->ochavo;
        $new->norte      = $request->norte;
        $new->sur        = $request->sur;
        $new->este       = $request->este;
        $new->oeste      = $request->oeste;
        $new->noreste    = $request->noreste;
        $new->noroeste   = $request->noroeste;
        $new->sureste    = $request->sureste;
        $new->suroeste   = $request->suroeste;
        $new->save();

        return redirect()->route('lote_index');
    }
}
