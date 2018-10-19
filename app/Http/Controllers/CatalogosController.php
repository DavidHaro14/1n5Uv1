<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use insuvi\Http\Requests;
use Laracasts\Flash\Flash;
use insuvi\Caja;
use insuvi\Usuario;
use insuvi\Banco;
use insuvi\Calle;
use insuvi\Estado;
use insuvi\Localidad;
use insuvi\Municipio;
use insuvi\Colonia;
use insuvi\Documento;
use insuvi\GrupoAtencion;
use insuvi\Mejoramiento;
use insuvi\Muro;
use insuvi\Piso;
use insuvi\Ocupacion;
use insuvi\Programa;
use insuvi\RegimenPropiedad;
use insuvi\Subsidio;
use insuvi\Techo;
use insuvi\Situacion;

class CatalogosController extends Controller
{
	/*******************
	*		CAJAS	   *
	********************/
    public function catalogo_caja(){
    	$modulo = enums('caja','modulo');
    	$cajas 	= Caja::all();
    	$users 	= Usuario::where('estatus_us','=','1')->where('modulos','=','CAJA')->get();
    	return view('sistema_interno.Catalogos.caja')->with('modulos',$modulo)
    												->with('cajas',$cajas)
    												->with('users',$users);
    }

    public function add_caja(Request $request){
    	$caja = new Caja();
    	$caja->folio_inicio = $request->inicial;
    	$caja->folio_final  = $request->final;
    	$caja->folio_actual = $request->inicial;
    	$caja->modulo       = $request->modulo;
    	$caja->save();

    	return redirect()->route('catalogo_caja');
    }

    public function asignacion_caja(Request $request, $id){
    	$this->validate($request, [
            'user'        => 'unique:caja,usuario_id',
        ],[
            'user.unique' => 'Error: Este usuario esta asignado en otra caja'
        ]);  	

        $caja = Caja::find($id);
    	$caja->folio_inicio = $request->inicial;
    	$caja->folio_final  = $request->final;
    	$caja->folio_actual = $request->actual;
    	$caja->usuario_id   = $request->user;
    	$caja->save();

    	return redirect()->route('catalogo_caja');
    }

    public function baja_usuario($id){
    	$caja = Caja::find($id);
    	$caja->usuario_id = 0;
    	$caja->save();

    	return redirect()->route('catalogo_caja');
    }

    public function estatus_caja($id){
    	$caja = Caja::find($id);
    	$caja->estatus = !$caja->estatus;
    	$caja->save();

    	return redirect()->route('catalogo_caja');
    }

    /*******************
    *       BANCOS     *
    ********************/
    public function index_banco(){
        $bank = Banco::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.banco')->with('bancos',$bank);
    }

    public function agregar_banco(Request $request){
        $bank = new Banco();
        $bank->nombre = $request->banco;
        $bank->cuenta = $request->cuenta;
        $bank->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('banco_index');
    }

    public function estatus_banco($id){
        $bank = Banco::find($id);
        $bank->estatus = !$bank->estatus;
        $bank->save();
        
        return redirect()->route('banco_index');
    }

    /*******************
    *       CALLES     *
    ********************/

    public function index_calle(){
        $estado = Estado::where('estatus','1')->orderBy('nombre')->get();
        $calle = Calle::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.calle')  ->with('calles',$calle)
                                                        ->with('estados',$estado);
    }

    public function agregar_calle(Request $request){
        $calle = new Calle();
        $calle->nombre      = $request->nombre;
        $calle->colonia_id  = $request->colonia;
        $calle->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('calle_index');
    }

    public function estatus_calle($id){
        $calle = Calle::find($id);
        $calle->estatus = !$calle->estatus;
        $calle->save();

        return redirect()->route('calle_index');
    }

    /*******************
    *     COLONIAS     *
    ********************/

    public function index_colonia(){
        $estado = Estado::where('estatus','1')->orderBy('nombre')->get();
        $colonia = Colonia::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.colonia')->with('colonias',$colonia)
                                                        ->with('estados',$estado);
    }

    public function agregar_colonia(Request $request){
        $colonia = new Colonia();
        $colonia->nombre        = $request->nombre;
        $colonia->abreviacion   = $request->abreviacion;
        $colonia->localidad_id  = $request->localidad;
        $colonia->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('colonia_index');
    }

    public function estatus_colonia($id){
        $colonia = Colonia::find($id);
        $colonia->estatus = !$colonia->estatus;
        $colonia->save();

        return redirect()->route('colonia_index');
    }

    /*******************
    *   DOCUMENTOS     *
    ********************/

    public function index_documento(){
        $documento = Documento::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.documento')->with('documentos',$documento);
    }

    public function agregar_documento(Request $request){
        $documento = new Documento();
        $documento->nombre   = $request->nombre;
        $documento->opcional = $request->opcional;
        $documento->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('documento_index');
    }

    public function estatus_documento($id){
        $documento = Documento::find($id);
        $documento->estatus = !$documento->estatus;
        $documento->save();

        return redirect()->route('documento_index');
    }

    /*******************
    *      ESTADOS     *
    ********************/

    public function index_estado(){
        $est = Estado::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.estado')->with('estados',$est);
    }

    public function agregar_estado(Request $request){
        $estado = new Estado();
        $estado->nombre = $request->nombre;
        $estado->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('estado_index');
    }

    public function estatus_estado($id){
        $est = Estado::find($id);
        $est->estatus = !$est->estatus;
        $est->save();
        
        return redirect()->route('estado_index');
    }

    /***********************
    *      SITUACIONES     *
    ************************/

    public function index_situacion(){
        $sit = Situacion::orderBy('situacion')->get();
        return view('sistema_interno.Catalogos.situaciones')->with('situaciones',$sit);
    }

    public function agregar_situacion(Request $request){
        $sit = new Situacion();
        $sit->situacion = $request->nombre;
        $sit->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('situacion_index');
    }

    public function estatus_situacion($id){
        $sit = Situacion::find($id);
        $sit->status = !$sit->status;
        $sit->save();
        
        return redirect()->route('situacion_index');
    }

    /*******************
    * GRUP. ATENCION   *
    ********************/

    public function index_grupo(){
        $atencion = GrupoAtencion::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.grupo_atencion')->with('grupos',$atencion);
    }

    public function agregar_grupo(Request $request){
        $atencion = new GrupoAtencion();
        $atencion->nombre = $request->nombre;
        $atencion->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('grupo_index');
    }

    public function estatus_grupo($id){
        $atencion = GrupoAtencion::find($id);
        $atencion->estatus = !$atencion->estatus;
        $atencion->save();
        
        return redirect()->route('grupo_index');
    }

    /*******************
    *   LOCALIDAD      *
    ********************/

    public function index_localidad(){
        $estado = Estado::where('estatus','1')->orderBy('nombre')->get();
        $localidad = Localidad::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.localidad')  ->with('localidades',$localidad)
                                                            ->with('estados',$estado);
    }

    public function agregar_localidad(Request $request){
        $localidad = new Localidad();
        $localidad->nombre      = $request->nombre;
        $localidad->municipio_id = $request->municipio;
        $localidad->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('localidad_index');
    }

    public function estatus_localidad($id){
        $localidad = Localidad::find($id);
        $localidad->estatus = !$localidad->estatus;
        $localidad->save();
        
        return redirect()->route('localidad_index');
    }

    /*******************
    *   MEJORAMIENTO   *
    ********************/

    public function index_mejoramiento(){
        $mejoramiento = Mejoramiento::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.mejoramiento')->with('mejoramientos',$mejoramiento);
    }

    public function agregar_mejoramiento(Request $request){
        $mejoramiento = new Mejoramiento();
        $mejoramiento->nombre = $request->nombre;
        $mejoramiento->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('mejoramiento_index');
    }

    public function estatus_mejoramiento($id){
        $mejoramiento = Mejoramiento::find($id);
        $mejoramiento->estatus = !$mejoramiento->estatus;
        $mejoramiento->save();
        
        return redirect()->route('mejoramiento_index');
    }

    /*******************
    *   MUNICIPIOS     *
    ********************/

    public function index_municipio(){
        $estado = Estado::where('estatus','1')->orderBy('nombre')->get();
        $municipio = Municipio::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.municipio')  ->with('municipios',$municipio)
                                                            ->with('estados',$estado);
    }

    public function agregar_municipio(Request $request){
        $municipio = new Municipio();
        $municipio->nombre      = $request->nombre;
        $municipio->estado_id   = $request->estado;
        $municipio->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('municipio_index');
    }

    public function estatus_municipio($id){
        $municipio = Municipio::find($id);
        $municipio->estatus = !$municipio->estatus;
        $municipio->save();
        
        return redirect()->route('municipio_index');
    }

    /*******************
    *       MUROS      *
    ********************/

    public function index_muro(){
        $muro = Muro::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.muro')->with('muros',$muro);
    }

    public function agregar_muro(Request $request){
        $muro = new Muro();
        $muro->nombre = $request->nombre;
        $muro->save();

        return redirect()->route('muro_index');
    }

    public function estatus_muro($id){
        $muro = Muro::find($id);
        $muro->estatus = !$muro->estatus;
        $muro->save();
        
        return redirect()->route('muro_index');
    }

    /*******************
    *  OCUPACIONES     *
    ********************/

    public function index_ocupacion(){
        $ocupacion = Ocupacion::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.ocupacion')->with('ocupaciones',$ocupacion);
    }

    public function agregar_ocupacion(Request $request){
        $ocupacion = new Ocupacion();
        $ocupacion->nombre = $request->nombre;
        $ocupacion->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('ocupacion_index');
    }

    public function estatus_ocupacion($id){
        $ocupacion = Ocupacion::find($id);
        $ocupacion->estatus = !$ocupacion->estatus;
        $ocupacion->save();
        
        return redirect()->route('ocupacion_index');
    }

    /*******************
    *       PISOS      *
    ********************/

    public function index_piso(){
        $piso = Piso::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.piso')->with('pisos',$piso);
    }

    public function agregar_piso(Request $request){
        $piso = new Piso();
        $piso->nombre = $request->nombre;
        $piso->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('piso_index');
    }

    public function estatus_piso($id){
        $piso = Piso::find($id);
        $piso->estatus = !$piso->estatus;
        $piso->save();
        
        return redirect()->route('piso_index');
    }

    /*******************
    *   PROGRAMAS      *
    ********************/

    public function index_programa(){
        $programa = Programa::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.programa')->with('programas',$programa);
    }

    public function agregar_programa(Request $request){
        $programa = new Programa();
        $programa->nombre = $request->nombre;
        $programa->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('programa_index');
    }

    public function estatus_programa($id){
        $programa = Programa::find($id);
        $programa->estatus = !$programa->estatus;
        $programa->save();
        
        return redirect()->route('programa_index');
    }

    /********************
    * REGIMEN PROPIEDAD *
    *********************/

    public function index_regimen(){
        $regimen = RegimenPropiedad::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.regimen_propiedad')->with('regimenes',$regimen);
    }

    public function agregar_regimen(Request $request){
        $regimen = new RegimenPropiedad();
        $regimen->nombre = $request->nombre;
        $regimen->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('regimen_index');
    }

    public function estatus_regimen($id){
        $regimen = RegimenPropiedad::find($id);
        $regimen->estatus = !$regimen->estatus;
        $regimen->save();

        return redirect()->route('regimen_index');
    }

    /*******************
    *   SUBSIDIOS      *
    ********************/

    public function index_subsidio(){
        $sub  = Subsidio::orderBy('tipo')->get();
        $type = enums('subsidio','tipo');
        return view('sistema_interno.Catalogos.subsidio')->with('subsidios',$sub)
                                                         ->with('tipos',$type);
    }

    public function agregar_subsidio(Request $request){
        $sub = new Subsidio();
        $sub->organizacion      = $request->organizacion;
        $sub->valor             = $request->valor;
        $sub->clave             = $request->clave;
        $sub->tipo              = $request->tipo;
        $sub->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('subsidio_index');
    }

    public function estatus_subsidio($id){
        $sub          = Subsidio::find($id);
        $sub->estatus = !$sub->estatus;
        $sub->save();

        return redirect()->route('subsidio_index');
    }

    /*******************
    *       TECHOS     *
    ********************/

    public function index_techo(){
        $techo = Techo::orderBy('nombre')->get();
        return view('sistema_interno.Catalogos.techo')->with('techos',$techo);
    }

    public function agregar_techo(Request $request){
        $techo = new Techo();
        $techo->nombre = $request->nombre;
        $techo->save();

        Flash::success("Se ha creado correctamente.");
        return redirect()->route('techo_index');
    }

    public function estatus_techo($id){
        $techo = Techo::find($id);
        $techo->estatus = !$techo->estatus;
        $techo->save();
        
        return redirect()->route('techo_index');
    }
}   
