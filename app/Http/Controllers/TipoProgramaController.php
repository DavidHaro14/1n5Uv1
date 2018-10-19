<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use insuvi\Http\Requests;
use insuvi\Programa;
use insuvi\Documento;
use insuvi\TipoPrograma;
use insuvi\Banco;
use insuvi\Contrato;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
//use Carbon\Carbon;

use PhpOffice\PhpWord\TemplateProcessor;
//use PhpOffice\PhpWord\Element\Text;

class TipoProgramaController extends Controller
{
    public function index()
    {
        // $templateWord = new TemplateProcessor(storage_path('contratos') . '\prueba.docx');
        // $temporal  = $templateWord->getVariables()[0];
        // $buscar    = ['$[V_CLIENTE]','$[V_CLAVE_CATASTRAL]','$[V_PLAZO]', '$[EDAD]'];
        // $remplazar = ['ORLANDO ANTONIO GONZALEZ JUAREZ','123-456-789','120 MESES', '20'];

        // $temporal = str_replace($buscar, $remplazar, $temporal);
        // dd($temporal); //-> poner todo el documento con ${ContenidoWord}
        $plantilla = enums('tipo_programa','plantilla');
    	$programa  = Programa::where('estatus','1')->orderBy('nombre')->get();
    	$documento = Documento::where('estatus','1')->orderBy('nombre')->get();
        $contrato  = Contrato::where('estatus','1')->orderBy('nombre')->get();
    	$tipoprog  = TipoPrograma::orderBy('nombre')->get();
        $banco     = Banco::where('estatus','1')->orderBy('nombre')->get();
    	return view('sistema_interno.Catalogos.tipo_programa')	->with('programas',$programa)
    															->with('tiposprogramas',$tipoprog)
    															->with('documentos',$documento)
                                                                ->with('bancos',$banco)
                                                                ->with('contratos',$contrato)
                                                                ->with('plantillas',$plantilla);
    }

    public function agregar(Request $request)
    {
        //$contrato       = $request->file('contrato');
        //$NombreContrato = Carbon::now()->format('d-m-Y') . "_" . $contrato->getClientOriginalName();
        //dd($request->contrato);
        $tipoprog = new TipoPrograma();
        $tipoprog->nombre       = $request->nombre;
        $tipoprog->descripcion  = $request->descripcion;
        //$tipoprog->contrato     = $NombreContrato;
        $tipoprog->plantilla    = $request->plantilla;
        $tipoprog->banco_id     = $request->banco;
        $tipoprog->programa_id  = $request->programa;
        $tipoprog->save();

        $tipoprog->documentos()->attach($request->documento);
        $tipoprog->contratos()->attach($request->contrato);

        //Storage::disk('contratos')->put($NombreContrato, File::get($contrato));

        Flash::success("Se ha creado correctamente.");
    	return redirect()->route('tiprog_index');
    }

    public function estatus($id)
    {
    	$tipoprog = TipoPrograma::find($id);
    	$tipoprog->estatus = !$tipoprog->estatus;
    	$tipoprog->save();

    	return redirect()->route('tiprog_index');
    }

    /*public function modificar(Request $request, $id)
    {
        $tipoprog = TipoPrograma::find($id);

        $tipoprog->nombre       = $request->nombre;
        $tipoprog->programa_id 	= $request->programa;

        $tipoprog->documentos()->sync($request->documento);

        $tipoprog->save();
        
        return redirect()->route('tiprog_index');
    }*/

    public function word($id)
    {
        $lol = TipoPrograma::find($id);
        $templateWord = new TemplateProcessor('../storage/contratos/'. $lol->contrato);

        // --- Variables
       
        $alumno = "Oscar Mendoza Gutierrez";
        $promedio = "96";
        $maestro = "Alberto Rodriguez Salazar";
        $carrera = "Ing. Sistemas Computacionales";
        $director = "Maria de Guadalupe Sanchez Lopez";

        // --- Asignamos valores
        $templateWord->setValue('alumno', $alumno);
        $templateWord->setValue('promedio', $promedio);
        $templateWord->setValue('maestro', $maestro);
        $templateWord->setValue('carrera', $carrera);
        $templateWord->setValue('director', $director);

        $templateWord->saveAs($lol->contrato);

        header("Content-Disposition: attachment; filename=". $lol->contrato . "; charset=iso-8859-1");
        echo file_get_contents($lol->contrato);
        dd("aqui");

        return redirect()->route('solicitudes_formulario');
    }
}
