<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use insuvi\Http\Requests;
use Laracasts\Flash\Flash;
use insuvi\Contrato;
use Carbon\Carbon;

class ContratoController extends Controller
{
    public function index()
    {
    	$contrato = Contrato::orderBy('nombre')->get();
    	return view('sistema_interno.Catalogos.contrato')->with('contratos',$contrato);
    }

    public function agregar(Request $request)
    {
    	$contrato       = $request->file('contrato');
        $NombreContrato = Carbon::now()->format('d-m-Y') . "_" . $contrato->getClientOriginalName();
        $extension      = $contrato->getClientOriginalExtension();
        if ($extension == "doc" || $extension == "docx") {
            $cont = new Contrato();
        	$cont->nombre = $NombreContrato;
        	$cont->save();

            Storage::disk('contratos')->put($NombreContrato, File::get($contrato));

            Flash::success("Se ha creado correctamente.");
    	    return redirect()->route('contrato_index');
        }
        return redirect()->back()->withErrors(['Favor de seleccionar un archivo con extensión .docx o .doc del formato word.']);
    }

    public function estatus($id)
    {
    	$cont = Contrato::find($id);
    	$cont->estatus = !$cont->estatus;
    	$cont->save();
    	
    	return redirect()->route('contrato_index');
    }
}
