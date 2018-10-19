<?php
//use insuvi\Programa;
// Route::get('/prueba', function(){
// 	return view('prueba');
// });

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/Login',[
	'uses' => 'LoginController@autentificar',
	'as'   => 'autentificacion'
]);

Route::get('/Logout',[
	'uses' => 'LoginController@logout',
	'as'   => 'logout'
]);

//*-- INICIO -> Publico --**//
Route::get('/Insuvi',['as' => 'home', function () {
    return view('welcome');
}]);

//*-- PreRegistro --**//
Route::get('/Sesion',[
		'uses'	=>	'PreRegistroController@form',
		'as' 	=> 	'pre-registro'
	]);

Route::post('/PreRegistroA',[
		'uses'	=>	'PreRegistroController@agregar',
		'as' 	=> 	'AgregarRegistro'
	]);

//*-- Programas --**//
Route::get('/InsuviProgramas', function () {
	$prog = \insuvi\Programa::where('estatus','1')->orderBy('nombre')->get();
    return view('principal.programas')->with('programas',$prog);
});

//** Autentificado **//
Route::group(['middleware' => 'auth'],function(){

	//*-- Menu Principal Sistema Interno --*//
	Route::get('/InsuviMenu',['as'	=>	'insuvi', function(){
		$limit     = insuvi\DatosEmpresa::where('estatus','=','1')->first();
		$solicitud = insuvi\SolicitudEliminacion::where('status','=','PENDIENTE')/*->where('created_at','=',date('Y-m-d'))*/->get();
		return view('sistema_interno.principal')->with('datos',$limit)->with('solicitudes',$solicitud);
	}]);

	//** ADMON **//
	Route::group(['middleware' => 'admon'],function(){

		//*-- Menu Catalogos --*//
		Route::get('/Catalogos', function(){
			return view('sistema_interno.Catalogos.menu-catalogo');
		})->name('catalogos');

		//*-- Catalogo CAJA --*//
		Route::get('/AsignacionCaja',[
				'uses'	=>	'CatalogosController@catalogo_caja',
				'as'	=>	'catalogo_caja'
			]);

		Route::post('/AgregarCaja',[
				'uses'	=>	'CatalogosController@add_caja',
				'as'	=>	'add_caja'
			]);

		Route::post('/Asignacion/{id}',[
				'uses'	=>	'CatalogosController@asignacion_caja',
				'as'	=>	'asignacion_caja'
			]);

		Route::get('/BajaUsuario/{id}',[
				'uses'	=>	'CatalogosController@baja_usuario',
				'as'	=>	'baja_usuario'
			]);

		Route::get('/EstatusCaja/{id}',[
				'uses'	=>	'CatalogosController@estatus_caja',
				'as'	=>	'estatus_caja'
			]);

		//*-- Catalogo Lote --*//
		Route::get('/Lotes',[
				'uses'	=>	'LoteController@index',
				'as'	=>	'lote_index'
			]);

		Route::get('/DivisionLote/{id}',[
				'uses'	=>	'LoteController@division_lote',
				'as'	=>	'division_lote'
			]);

		Route::post('/LotesAgregar',[
				'uses'	=>	'LoteController@agregar',
				'as'	=>	'lote_agregar'
			]);

		Route::post('/DivisionLote/{id}',[
				'uses'	=>	'LoteController@add_division',
				'as'	=>	'add_division'
			]);

		//*-- Catalogo Subsidio --*//
		Route::get('/Subsidio',[
				'uses'	=>	'CatalogosController@index_subsidio',
				'as'	=>	'subsidio_index'
			]);

		Route::post('/SubsidioA',[
				'uses'	=>	'CatalogosController@agregar_subsidio',
				'as'	=>	'subsidio_agregar'
			]);

		Route::get('/SubsidioU/{id}',[
				'uses'	=>	'CatalogosController@estatus_subsidio',
				'as'	=>	'subsidio_estatus'
			]);

		//*-- Catalogo Contrato --*//
		Route::get('/Contrato',[
				'uses'	=>	'ContratoController@index',
				'as'	=>	'contrato_index'
			]);

		Route::post('/Contrato',[
				'uses'	=>	'ContratoController@agregar',
				'as'	=>	'contrato_agregar'
			]);

		Route::get('/Contrato/{id}',[
				'uses'	=>	'ContratoController@estatus',
				'as'	=>	'contrato_estatus'
			]);

		//*-- Catalogo Banco --*//
		Route::get('/Banco',[
				'uses'	=>	'CatalogosController@index_banco',
				'as'	=>	'banco_index'
			]);

		Route::post('/Banco',[
				'uses'	=>	'CatalogosController@agregar_banco',
				'as'	=>	'banco_agregar'
			]);

		Route::get('/Banco/{id}',[
				'uses'	=>	'CatalogosController@estatus_banco',
				'as'	=>	'banco_estatus'
			]);

		//*-- Catalogo Situaciones --*//
		Route::get('/Situacion',[
				'uses'	=>	'CatalogosController@index_situacion',
				'as'	=>	'situacion_index'
			]);

		Route::post('/SituacionAgregar',[
				'uses'	=>	'CatalogosController@agregar_situacion',
				'as'	=>	'situacion_agregar'
			]);

		Route::get('/SituacionE/{id}',[
				'uses'	=>	'CatalogosController@estatus_situacion',
				'as'	=>	'situacion_estatus'
			]);

		//*-- Catalogo Estado --*//
		Route::get('/Estados',[
				'uses'	=>	'CatalogosController@index_estado',
				'as'	=>	'estado_index'
			]);

		Route::post('/EstadosAgregar',[
				'uses'	=>	'CatalogosController@agregar_estado',
				'as'	=>	'estado_agregar'
			]);

		Route::get('/EstadoE/{id}',[
				'uses'	=>	'CatalogosController@estatus_estado',
				'as'	=>	'estado_estatus'
			]);

		//*-- Catalogo Grupo Atencion --*//

		Route::get('/GrupoAtencion',[
				'uses'	=>	'CatalogosController@index_grupo',
				'as'	=>	'grupo_index'
			]);

		Route::post('/GrupoAtencionAgregar',[
				'uses'	=>	'CatalogosController@agregar_grupo',
				'as'	=>	'grupo_agregar'
			]);

		Route::get('/GrupoAtencionE/{id}',[
				'uses'	=>	'CatalogosController@estatus_grupo',
				'as'	=>	'grupo_estatus'
			]);

		//*-- Catalogo Ocupacion --*//

		Route::get('/Ocupaciones',[
				'uses'	=>	'CatalogosController@index_ocupacion',
				'as'	=>	'ocupacion_index'
			]);

		Route::post('/OcupacionesAgregar',[
				'uses'	=>	'CatalogosController@agregar_ocupacion',
				'as'	=>	'ocupacion_agregar'
			]);

		Route::get('/OcupacionE/{id}',[
				'uses'	=>	'CatalogosController@estatus_ocupacion',
				'as'	=>	'ocupacion_estatus'
			]);

		//*-- Catalogo Techo --*//

		Route::get('/Techos',[
				'uses'	=>	'CatalogosController@index_techo',
				'as'	=>	'techo_index'
			]);

		Route::post('/TechosAgregar',[
				'uses'	=>	'CatalogosController@agregar_techo',
				'as'	=>	'techo_agregar'
			]);

		Route::get('/TechoE/{id}',[
				'uses'	=>	'CatalogosController@estatus_techo',
				'as'	=>	'techo_estatus'
			]);

		//*-- Catalogo Piso --*//

		Route::get('/Pisos',[
				'uses'	=>	'CatalogosController@index_piso',
				'as'	=>	'piso_index'
			]);

		Route::post('/PisosAgregar',[
				'uses'	=>	'CatalogosController@agregar_piso',
				'as'	=>	'piso_agregar'
			]);

		Route::get('/PisoE/{id}',[
				'uses'	=>	'CatalogosController@estatus_piso',
				'as'	=>	'piso_estatus'
			]);

		//*-- Catalogo Muro --*//

		Route::get('/Muros',[
				'uses'	=>	'CatalogosController@index_muro',
				'as'	=>	'muro_index'
			]);

		Route::post('/MurosAgregar',[
				'uses'	=>	'CatalogosController@agregar_muro',
				'as'	=>	'muro_agregar'
			]);

		Route::get('/MuroE/{id}',[
				'uses'	=>	'CatalogosController@estatus_muro',
				'as'	=>	'muro_estatus'
			]);

		//*-- Catalogo Regimen de propiedad --*//

		Route::get('/RegimenesPropiedad',[
				'uses'	=>	'CatalogosController@index_regimen',
				'as'	=>	'regimen_index'
			]);

		Route::post('/RegimenesPropiedadAgregar',[
				'uses'	=>	'CatalogosController@agregar_regimen',
				'as'	=>	'regimen_agregar'
			]);

		Route::get('/RegimenE/{id}',[
				'uses'	=>	'CatalogosController@estatus_regimen',
				'as'	=>	'regimen_estatus'
			]);

		//*-- Catalogo Documentos --*//

		Route::get('/Documentos',[
				'uses'	=>	'CatalogosController@index_documento',
				'as'	=>	'documento_index'
			]);

		Route::post('/DocumentosAgregar',[
				'uses'	=>	'CatalogosController@agregar_documento',
				'as'	=>	'documento_agregar'
			]);

		Route::get('/DocumentoE/{id}',[
				'uses'	=>	'CatalogosController@estatus_documento',
				'as'	=>	'documento_estatus'
			]);

		//*-- Catalogo programas --*//

		Route::get('/Programas',[
				'uses'	=>	'CatalogosController@index_programa',
				'as'	=>	'programa_index'
			]);

		Route::post('/ProgramasAgregar',[
				'uses'	=>	'CatalogosController@agregar_programa',
				'as'	=>	'programa_agregar'
			]);

		Route::get('/ProgramaE/{id}',[
				'uses'	=>	'CatalogosController@estatus_programa',
				'as'	=>	'programa_estatus'
			]);

		//*-- Catalogo Mejoramiento --*//

		Route::get('/Mejoramientos',[
				'uses'	=>	'CatalogosController@index_mejoramiento',
				'as'	=>	'mejoramiento_index'
			]);

		Route::post('/MejoramientosAgregar',[
				'uses'	=>	'CatalogosController@agregar_mejoramiento',
				'as'	=>	'mejoramiento_agregar'
			]);

		Route::get('/MejoramientoE/{id}',[
				'uses'	=>	'CatalogosController@estatus_mejoramiento',
				'as'	=>	'mejoramiento_estatus'
			]);

		//*-- Catalogo Municipio --*//

		Route::get('/Municipios',[
				'uses'	=>	'CatalogosController@index_municipio',
				'as'	=>	'municipio_index'
			]);

		Route::post('/MunicipiosAgregar',[
				'uses'	=>	'CatalogosController@agregar_municipio',
				'as'	=>	'municipio_agregar'
			]);

		Route::get('/MunicipioE/{id}',[
				'uses'	=>	'CatalogosController@estatus_municipio',
				'as'	=>	'municipio_estatus'
			]);

		//*-- Catalogo Localidad --*//

		Route::get('/Localidades',[
				'uses'	=>	'CatalogosController@index_localidad',
				'as'	=>	'localidad_index'
			]);

		Route::post('/LocalidadesAgregar',[
				'uses'	=>	'CatalogosController@agregar_localidad',
				'as'	=>	'localidad_agregar'
			]);

		Route::get('/LocalidadE/{id}',[
				'uses'	=>	'CatalogosController@estatus_localidad',
				'as'	=>	'localidad_estatus'
			]);

		//*-- Catalogo Colonia --*//

		Route::get('/Colonias',[
				'uses'	=>	'CatalogosController@index_colonia',
				'as'	=>	'colonia_index'
			]);

		Route::post('/ColoniasAgregar',[
				'uses'	=>	'CatalogosController@agregar_colonia',
				'as'	=>	'colonia_agregar'
			]);

		Route::get('/ColoniaE/{id}',[
				'uses'	=>	'CatalogosController@estatus_colonia',
				'as'	=>	'colonia_estatus'
			]);

		//*-- Catalogo Calle --*//

		Route::get('/Calles',[
				'uses'	=>	'CatalogosController@index_calle',
				'as'	=>	'calle_index'
			]);

		Route::post('/CallesAgregar',[
				'uses'	=>	'CatalogosController@agregar_calle',
				'as'	=>	'calle_agregar'
			]);

		Route::get('/CalleE/{id}',[
				'uses'	=>	'CatalogosController@estatus_calle',
				'as'	=>	'calle_estatus'
			]);

		//*-- Catalogo Empleados --*//

		Route::get('/Personal',[
				'uses'	=>	'EmpleadoController@index',
				'as'	=>	'empleado_index'
			]);

		Route::post('/Personal',[
				'uses'	=>	'EmpleadoController@agregar',
				'as'	=>	'empleado_agregar'
			]);

		Route::get('/Personal/{id}',[
				'uses'	=>	'EmpleadoController@estatus',
				'as'	=>	'empleado_estatus'
			]);

		Route::put('/Jefe/{id}',[
				'uses'	=>	'EmpleadoController@estatus',
				'as'	=>	'jefe_estatus'
			]);

		//*-- Catalogo Tipo de Programas --*//

		Route::get('/TiposProgramas',[
				'uses'	=>	'TipoProgramaController@index',
				'as'	=>	'tiprog_index'
			]);

		Route::post('/TiposProgramasAgregar',[
				'uses'	=>	'TipoProgramaController@agregar',
				'as'	=>	'tiprog_agregar'
			]);

		Route::get('/TipoProgramaE/{id}',[
				'uses'	=>	'TipoProgramaController@estatus',
				'as'	=>	'tiprog_estatus'
			]);

		Route::post('/InsuviMenu',['as'	=>	'add_datos', function(Illuminate\Http\Request $request){
			//dd($request);
			$anterior = insuvi\DatosEmpresa::find($request->id);
			$anterior->estatus = 0;
			$anterior->save();

			$datos = new insuvi\DatosEmpresa();
			if ($request->registro != "limite") {
				$datos->director 		  = $request->nombre;
				$datos->fecha_director 	  = date('Y-m-d');
				// Se quedan igual
				$datos->fecha_limite 	  = $anterior->fecha_limite;
				$datos->limite_monto 	  = $anterior->limite_monto;
				$datos->limite_porcentaje = $anterior->limite_porcentaje;
				$datos->save();
				return redirect()->route('insuvi');
			}

			$datos->director 		  = $anterior->director;
			$datos->fecha_director 	  = $anterior->fecha_director;
			// Se quedan igual
			$datos->fecha_limite 	  = date('Y-m-d');

			if ($request->tipo != "Porcentaje") {
				$datos->limite_monto 	  = $request->limite;
			} else {
				$datos->limite_porcentaje = $request->limite / 100;
			}

			$datos->save();
			return redirect()->route('insuvi');
		}]);

		// Reporte Solicitudes
		route::get('/ListaSolicitudes',[
				'uses' => 'ReporteController@reporte_solicitudes',
				'as'   => 'reporte_solicitudes'
			]);
	});
	
	//** SOLICITUD **//
	Route::group(['middleware' => 'solicitud'],function(){
		
		Route::get('/AtencionSolicitudes',[
				'uses'	=>	'AtencionSolicitudController@formulario',
				'as'	=>	'solicitudes_formulario'
			]);

		Route::post('/AtencionSolicitudesA',[
				'uses'	=>	'AtencionSolicitudController@agregar',
				'as'	=>	'solicitudes_agregar'
			]);
		
	});

	//** CAMBIO DE DOMICILIO **//
	Route::group(['middleware' => 'domicilio'],function(){
		Route::get('/Clientes',[
				'uses'	=>	'AtencionSolicitudController@view_update',
				'as'	=>	'cliente_view_update'
			]);

		Route::put('/ClienteUpdate',[
				'uses'	=>	'AtencionSolicitudController@update',
				'as' 	=> 	'cliente_update'
			]);
	});

	//** ESTUDIO **//
	Route::group(['middleware' => 'estudio'],function(){

		Route::get('/EstudioSocioeconomico/{id}',[
				'uses'	=>	'EstudioSocioeconomicoController@form',
				'as' 	=> 	'socioeconomico'
			]);

		Route::get('/EstudioSocioeconomico',[
				'uses'	=>	'EstudioSocioeconomicoController@index',
				'as' 	=> 	'estudio_index'
			]);

		Route::post('/EstudioSocioeconomicoA',[
				'uses'	=>	'EstudioSocioeconomicoController@agregar',
				'as' 	=> 	'socioeconomicoA'
			]);
	});

	//** DEMANDA **//
	Route::group(['middleware' => 'demanda'],function(){

		Route::get('/Demanda/{id}',[
				'uses'	=>	'DemandaController@form',
				'as' 	=> 	'demanda'
			]);

		Route::get('/Demanda',[
				'uses'	=>	'DemandaController@index',
				'as' 	=> 	'demanda_index'
			]);

		Route::post('/Demanda',[
				'uses'	=>	'DemandaController@crear',
				'as' 	=> 	'demandaC'
			]);

		Route::get('/DemandasPendientes',[
				'uses'	=>	'DemandaController@view_demandas',
				'as' 	=> 	'view_demandas'
			]);

		Route::put('/DemandasPendientes/{id}',[
				'uses'	=>	'DemandaController@modificar_demanda',
				'as' 	=> 	'modificar_demanda'
			]);

		Route::get('/DemandasPendientes/{id}',[
				'uses'	=>	'DemandaController@delete_demanda',
				'as' 	=> 	'delete_demanda'
			]);

	});
	
	//** ENGANCHE **//
	Route::group(['middleware' => 'enganche'],function(){

		Route::get('/DemandaEnganche',[
				'uses'	=>	'DemandaController@enganche',
				'as' 	=> 	'demanda_enganche'
			]);

		Route::put('/DemandaUpdate*',[
				'uses'	=>	'DemandaController@update',
				'as' 	=> 	'demanda_update'
			]);
	});

	//** CONTRATACION **//
	Route::group(['middleware' => 'contratacion'],function(){

		Route::get('/Credito/{id}/{credito?}',[
				'uses'	=>	'CreditoController@form',
				'as' 	=> 	'credito'
			]);

		Route::get('/Credito',[
				'uses'	=>	'CreditoController@index',
				'as' 	=> 	'credito_index'
			]);

		Route::post('/Credito',[
				'uses'	=>	'CreditoController@add',
				'as' 	=> 	'creditoC'
			]);

		Route::put('/Credito',[
				'uses'	=>	'CreditoController@credito_put',
				'as' 	=> 	'creditoC'
			]);

		Route::get('/ModificarCredito',[
				'uses'	=>	'CreditoController@creditos_virgen',
				'as' 	=> 	'creditos_virgen'
			]);
	});

	//** REESTRUCTURA NORMAL **//
	Route::group(['middleware' => 'reestructura'],function(){
		
		Route::get('/Reestructura',[
				'uses'	=>	'CreditoController@reestructura_index',
				'as' 	=> 	'reestructura_index'
			]);

		Route::get('/Reestructura/{clave}',[
				'uses'	=>	'CreditoController@reestructura_form',
				'as' 	=> 	'reestructura_form'
			]);

		Route::get('/SolicitudEliminacion',[
				'uses'	=>	'CreditoController@index_solicitud',
				'as' 	=> 	'solicitud_eliminacion'
			]);

		Route::post('/SolicitudEliminacion',[
				'uses'	=>	'CreditoController@add_solicitud',
				'as' 	=> 	'add_solicitud'
			]);

		Route::get('/AprobacionSolicitudEliminacion/{aprobacion}/{solicitud?}/{clave?}',[
				'uses'	=>	'CreditoController@aprobacion_solicitud',
				'as' 	=> 	'aprobacion_solicitud'
			]);
	});

	//** REESTRUCTURA SAIV **//
	Route::group(['middleware' => 'saiv'],function(){
		
		Route::get('/ReestructuraSAIV',[
				'uses'	=>	'CreditoController@reestructura_saiv',
				'as' 	=> 	'reestructura_saiv'
			]);

		Route::post('/ReestructuraSAIV/{saiv?}',[
				'uses'	=>	'CreditoController@reestructura_add',
				'as' 	=> 	'reestructura_add'
			]);
	});
	
	//** CANCELACION **//
	Route::group(['middleware' => 'cancelacion'],function(){

		Route::get('/Cancelacion',[
				'uses'	=>	'CreditoController@index_cancelacion',
				'as' 	=> 	'cancelacion_index'
			]);

		Route::post('/Cancelacion',[
				'uses'	=>	'CreditoController@add_cancelacion',
				'as' 	=> 	'cancelacionC'
			]);
	});

	//** SEGUIMIENTO **//
	Route::group(['middleware' => 'seguimiento'],function(){

		Route::get('/Seguimiento',[
				'uses'	=>	'SeguimientoController@seguimiento',
				'as' 	=> 	'seguimiento'
			]);

		Route::get('/Seguimiento/{clave}',[
				'uses'	=>	'SeguimientoController@seguimiento_credito',
				'as' 	=> 	'seguimiento_credito'
			]);

		Route::post('/Seguimiento',[
				'uses'	=>	'SeguimientoController@add_seguimiento',
				'as' 	=> 	'seguimiento_add'
			]);

		Route::put('/Seguimiento',[
				'uses'	=>	'SeguimientoController@seguimiento_estatus',
				'as' 	=> 	'seguimiento_estatus'
			]);

		Route::put('/SeguimientoConvenio/{id}',[
				'uses'	=>	'SeguimientoController@update_convenio',
				'as' 	=> 	'update_convenio'
			]);
	});

	//** CESION **//
	Route::group(['middleware' => 'cesion'],function(){

		Route::get('/CesionDerecho',[
				'uses'	=>	'DemandaController@cesion_derecho',
				'as' 	=> 	'cesion_derecho'
			]);

		Route::post('/CesionDerecho',[
				'uses'	=>	'DemandaController@add_cesion',
				'as' 	=> 	'add_cesion'
			]);
	});

	//** CAJA Y DESCUENTO **//
	Route::group(['middleware' => 'caja'],function(){

		Route::get('/Caja/{descuentos?}/{busqueda?}',[
				'uses'	=>	'CobranzaController@index_creditos',
				'as' 	=> 	'caja'
			]);

		Route::get('/Pagos/{credito}/{tipo}/{reestructurado?}',[
				'uses'	=>	'CobranzaController@pagos_credito',
				'as' 	=> 	'pagos_credito'
			]);

		Route::get('/DescuentosPagos/{credito}',[
				'uses'	=>	'CobranzaController@descuentos_credito',
				'as' 	=> 	'descuentos_credito'
			]);

		Route::post('/PagoAhorrador/{credito}',[
				'uses'	=>	'CobranzaController@PagoAhorrador',
				'as' 	=> 	'PagoAhorrador'
			]);

		Route::post('/PagoCredito/{credito}',[
				'uses'	=>	'CobranzaController@PagoCredito',
				'as' 	=> 	'PagoCredito'
			]);

		Route::post('/PagoConvenio/{seguimiento}',[
				'uses'	=>	'CobranzaController@PagoConvenio',
				'as' 	=> 	'PagoConvenio'
			]);

		//** DESCUENTO **//
		Route::group(['middleware' => 'descuento'],function(){
			Route::post('/AplicarDescuento/{credito}',[
					'uses'	=>	'CobranzaController@AplicarDescuento',
					'as' 	=> 	'AplicarDescuento'
				]);
		});
	});

	Route::get('/DatosCliente/{id?}',[
			'uses'	=>	'AtencionSolicitudController@view_cliente',
			'as'	=>	'view_cliente'
		]);

	Route::get('/EstatusCliente/{id}',[
			'uses'	=>	'AtencionSolicitudController@estatus_cliente',
			'as'	=>	'estatus_cliente'
		]);
	
	//** DOCUMENTOS PDF **//
	Route::get('/pdf_credito/{clave}/{plantilla}/{stream?}',[
			'uses'	=>	'PdfController@plantilla',
			'as'	=>	'pdf_credito'
		]);

	/*-- Peticiones Query Ajax --*/
	Route::get('MunicipiosGet','QueryController@getMunicipios');
	Route::get('LocalidadesGet','QueryController@getLocalidades');
	Route::get('ColoniasGet','QueryController@getColonias');
	Route::get('CallesGet','QueryController@getCalles');
	/*-- Ajax Atencion a la Solicitud --*/
	Route::get('RenapoDatosGet','QueryController@getDatosCliente');
	Route::get('RenapoCurpGet','QueryController@getCurpCliente');
	Route::get('GetPreRegistro','QueryController@preregistro');
	/*-- Ajax PreRegistro--*/
	Route::get('GetValidar','QueryController@validar');
	/*-- Ajax EstudioSocioEconomico --*/
	Route::post('PostFamiliar','QueryController@familiar');
	Route::get('DeleteFamiliar/{id}','QueryController@deletefamiliar');
	Route::post('PostReferencia','QueryController@referencia');
	Route::get('DeleteReferencia/{id}','QueryController@deletereferencia');
	/*-- Ajax Demanda --*/
	Route::get('TiposProgramasGet','QueryController@getTiposProgramas');
	Route::get('DocumentosGet/{id}','QueryController@getDocumentos');
	/*-- Ajax Credito --*/
	Route::get('LotesGet','QueryController@getLotes');
	Route::get('Lotes2Get','QueryController@getLotes2');
	Route::get('SuperficieGet/{id}','QueryController@getSuperficie');
	Route::get('SubsidiosGet/{id}','QueryController@getValorSubsidio');
	Route::get('CreditoEngancheGet/{id}','QueryController@getCreditoEnganche');
	Route::get('LoteInfoGet/{id}','QueryController@getInfoLote');
	Route::get('CreditosGet','QueryController@getCreditoReestructura');
	Route::get('SubsidiosValidacion','QueryController@SubsidiosValidacion');
	Route::post('SetRegularizacionLote','QueryController@SetRegularizacionLote');
	/*-- Ajax Caja/Cobranza y Reestructura/SAIV --*/
	Route::get('GetAcreditado','QueryController@getAcreditado');
	Route::get('GetMensualidadesVenc','QueryController@getMensualidadesVenc');
	Route::get('GetCredito','QueryController@getCredito');
	Route::get('GetPagos','QueryController@getPagos');
	Route::get('GetSiglasFraccionamiento','QueryController@GetSiglasFraccionamiento');
	Route::get('GetPlantillaDemanda','QueryController@GetPlantillaDemanda');
	Route::post('SetDemandaSaiv','QueryController@setDemandaSaiv');
	Route::get('SetDeleteDemanda/{id}','QueryController@SetDeleteDemanda');
	/*-- Ajax Seguimientos --*/
	Route::get('SeguimientosPendientes','QueryController@getSeguimientos');
	/*-- Ajax Solicitud Eliminacion --*/
	Route::get('GetCreditoAnterior','QueryController@getCreditoAnterior');
	/* VALIDACIONES */
	Route::get('GetValidacionAtencionSolicitud','ValidacionAjaxController@GetValidacionAtencionSolicitud');	
	Route::get('GetValidacionEstudioSocioeconomico','ValidacionAjaxController@GetValidacionEstudioSocioeconomico');	

	//*-- Ejemplo Contratos --*//
	Route::get('/word/{id}',[
			'uses'	=>	'TipoProgramaController@word',
			'as'	=>	'word'
		]);

});// EDN group -> AUTH
