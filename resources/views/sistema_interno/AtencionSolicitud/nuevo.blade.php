@extends('sistema_interno.template.cuerpo')

@section('titulo', 'Atención Solicitud')

@section('head')
	<link rel="stylesheet" href="{{ asset('datetimepicker/css/bootstrap-datepicker.min.css') }}">
@stop

@section('contenido')

	@include('errors.errores')
	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<div class="input-group">
		   	<div class="input-group-btn">
				<select name="tipo" class="btn btn-default size-search" id="tipo">
					<option value="clave"><b>Clave Solicitante</b></option>
					<option value="curp"><b>CURP</b></option>
				</select>
		   	</div>
		  	<b><input type="text" class="form-control" name="buscar" maxlength="18" id="dato"></b>
			<div class="input-group-btn"><button type="button" class="btn BtnOrange" id="btn-buscar"><span class="icon icon-search"></span> Buscar PreRegistro</button></div>
		</div>
	</div>
	<br>

	<div class="alert alert-danger msj-error" role="alert" style="display:none;">
		<button type="button" class="close close-alert">
		  <span aria-hidden="true">&times;</span>
		</button>
        <li id="msj"></li>
    </div>

	<br>
	<div class="row">
		<ul class="nav nav-tabs nav-justified" role="tablist">
		  	<li role="presentation" class="active">
		  		<!-- <a href="#generales" aria-controls="generales" role="tab" data-toggle="tab">Datos Generales</a> -->
		  		<a href="#generales">Datos Generales</a>
		  	</li>

		  	<li role="presentation" id="TabConyuge">
		  		<!-- <a href="#conyuge" aria-controls="conyuge" role="tab" data-toggle="tab">Cónyuge</a> -->
		  		<a href="#conyuge">Cónyuge</a>
		  	</li>

		  	<li role="presentation">
		  		<!-- <a href="#domicilio" aria-controls="domicilio" role="tab" data-toggle="tab">Domicilio</a> -->
		  		<a href="#domicilio">Domicilio</a>
			</li>

		  	<li role="presentation">
		  		<!-- <a href="#modalidad" aria-controls="modalidad" role="tab" data-toggle="tab">Modalidad Requerida</a> -->
		  		<a href="#modalidad">Modalidad Requerida</a>
		  	</li>
		</ul>
	</div>

	<form action="{{ route('solicitudes_agregar')}}" method="POST">
		{{ csrf_field() }}
		<div class="tab-content panel-body">
			<div role="tabpanel" class="tab-pane active" id="generales">
				<!-- Datos Generales-->
				<div class="row">

					<div class="form-group col-md-3">
						<label for="curp">CURP</label>
						<div class="input-group">
							<input type="text" name="curp" class="form-control" required maxlength="18" id="SolicitanteCurp" value="{{ old('curp') }}">
							<div class="input-group-btn"><button type="button" class="btn BtnOrange	Sbuscar-datos"><span class="icon icon-search"></span></button></div>
						</div>
					</div>

					<div class="form-group col-md-3">
						<label>Nombre <span class="icon icon-pushpin ColorRequired"></span></label>
						<input type="text" name="nombre" class="form-control" required id="SolicitanteNombre" value="{{ old('nombre') }}"> 
					</div>

					<div class="form-group col-md-3">
						<label>Apellido Paterno <span class="icon icon-pushpin ColorRequired"></span></label>
						<input type="text" name="ape_p" class="form-control" required id="SolicitantePaterno" value="{{ old('ape_p') }}">
					</div>

					<div class="form-group col-md-3">
						<label>Apellido Materno <span class="icon icon-pushpin ColorRequired"></span></label>
						<input type="text" name="ape_m" class="form-control" required id="SolicitanteMaterno" value="{{ old('ape_m') }}">
					</div>

					<div class="form-group col-md-3">
						<label for="estado_civil">Estado Civil</label>
						<select name="estado_civil" required class="EstadoCivil SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($civiles as $civil)
								<option value="{{$civil}}">{{$civil}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label>Genero <span class="icon icon-pushpin ColorRequired"></span></label>
						<select name="genero" required class="SimpleSelect" id="gender">
							{{-- <option selected value="" hidden>Seleccionar</option> --}}
							@foreach($generos as $gen)
								<option value="{{$gen}}">{{$gen}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label>Fecha de Nacimiento <span class="icon icon-pushpin ColorRequired"></span></label>
						<div class='input-group date d_nac' id="picker-container">
							<input type="text" name="fecha_nac" class="form-control readonly" required value="{{ old('fecha_nac') }}" id="date_nac">
		                    <span class="input-group-addon">
		                        <span class="icon icon-calendar"></span>
		                    </span>
		                </div>
					</div>

					<div class="form-group col-md-3">
						<label for="correo">Correo (Opcional)</label>
						<input type="email" name="correo" class="form-control" value="{{ old('correo') }}" id="mail">
					</div>

					<div class="form-group col-md-3">
						<label for="tel">Teléfono (Opcional)</label>
						<input type="number" name="tel" class="form-control" maxlength="13" value="{{ old('tel') }}" id="phone">
					</div>

					<div class="form-group col-md-3">
						<label for="estado_nac">Estado de Nacimiento</label>
						<input type="text" name="estado_nac" class="form-control" required value="{{ old('estado_nac') }}" id="state_nac">
					</div>

					<div class="form-group col-md-3">
						<label for="lugar_nac">Lugar de Nacimiento</label>
						<input type="text" name="lugar_nac" class="form-control" required value="{{ old('lugar_nac') }}" id="place_nac">
					</div>

					<div class="form-group col-md-3">
						<label for="dependientes">No. Dependientes</label>
						<input type="number" name="dependientes" class="form-control" required maxlength="1" value="0" id="dependient">
					</div>

					<div class="form-group col-md-3">
						<label for="ocupacion">Ocupación</label>
						<select name="ocupacion" required class="SimpleSelect" id="ocupation">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($ocupaciones as $ocup)
								<option value="{{$ocup->id_ocupacion}}">{{$ocup->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="escolaridad">Escolaridad</label>
						<select name="escolaridad" required class="SimpleSelect" id="school">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($escolaridades as $esc)
								<option value="{{$esc}}">{{$esc}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="grupo_social">Grupo Social (Opcional)</label>
						<select name="grupo_social" class="SimpleSelect">
							<option selected value="">Seleccionar</option>
							@foreach($social as $grpsocial)
								<option value="{{$grpsocial}}">{{$grpsocial}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3 text-center">
						<label>Generar CURP</label><br>
						<button style="width:100%;" type="button" class="btn BtnOrange Sbuscar-curp"><b>Generar</b></button>
					</div>

					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnGray CleanCurp"><span class="icon icon-pushpin"></span> Limpiar Datos Curp </button>
						<button type="button" class="btn BtnOrange BtnGeneral" id="BtnContinuarGeneral">Continuar <span class="icon icon-arrow-right"></span></button>
					</div>
					<!--<input type="hidden" name="password" id="contra" value="{{ old('password') }}">
					div class="form-group col-md-3">
						<label for="pass">Contraseña</label>
						<input type="password" name="password" required maxlength="15" class="form-control" id="pass" value="{{ old('password') }}">
					</div>

					<div class="form-group col-md-3">
						<label for="confirmar">Confirmar Contraseña</label>
						<input type="password" name="password_confirmation" required maxlength="15" class="form-control" id="pass2">
					</div-->
				</div>

			</div>

			<div role="tabpanel" class="tab-pane fade" id="domicilio">
				
				<!-- Domicilio -->
				<div class="row">
					<div class="form-group col-md-3">
						<label for="estado">Estado</label>
						<select id="state" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($estados as $est)
								<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="municipio">Municipio</label>
						<select id="town" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="localidad">Localidad</label>
						<select id="location" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="colonia">Colonia</label>
						<select id="colonie" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="calle">Calle</label>
						<select name="calle" id="street" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-1">
						<label for="num_ext"># Ext</label>
						<input type="text" name="num_ext" class="form-control OnlyNumberDomicilio" value="0" maxlength="7" required>
					</div>

					<div class="form-group col-md-1">
						<label for="num_int"># Int</label>
						<input type="text" name="num_int" class="form-control OnlyNumberDomicilio" value="0" maxlength="7" required>
					</div>

					<div class="form-group col-md-1">
						<label for="codigo_postal">C.P.</label>
						<input type="text" name="codigo_postal" class="form-control OnlyNumber" maxlength="5" required>
					</div>

					<div class="form-group col-md-3">
						<label for="referencia">Referencia 1</label>
						<input type="text" name="referencia" class="form-control" required>
					</div>

					<div class="form-group col-md-3">
						<label for="referencia2">Referencia 2</label>
						<input type="text" name="referencia2" class="form-control" required>
					</div>

					<div class="form-group col-md-3">
						<label for="referencia3">Referencia 3</label>
						<input type="text" name="referencia3" class="form-control" required>
					</div>

					<div class="form-group col-md-12">
						<label>Descripción de la Ubicación</label>
						<textarea class="form-control" name="ubicacion" rows="5" maxlength="100"></textarea>
					</div>

					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnGray BtnConyuge2" id="BtnRegresarConyuge"><span class="icon icon-arrow-left"></span> Regresar</button>
						<button type="button" class="btn BtnOrange BtnDomicilio">Continuar <span class="icon icon-arrow-right"></span></button>
					</div>
				</div>

			</div>

			<div role="tabpanel" class="tab-pane fade" id="conyuge">
				
				<!-- cónyuge -->
				<div class="row">
					<div class="form-group col-md-3">
						<label for="nombre_conyuge">Nombre</label>
						<input type="text" name="nombre_conyuge" class="form-control DatosConyuge" id="ConyugeNombre">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_p_conyuge">Apellido Paterno</label>
						<input type="text" name="ape_p_conyuge" class="form-control DatosConyuge" id="ConyugePaterno">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_m_conyuge">Apellido Materno</label>
						<input type="text" name="ape_m_conyuge" class="form-control DatosConyuge" id="ConyugeMaterno">
					</div>

					<div class="form-group col-md-3">
						<label for="curp_conyuge">CURP</label>
						<div class="input-group">
							<input type="text" name="curp_conyuge" class="form-control DatosConyuge" maxlength="18" id="ConyugeCurp">
							<div class="input-group-btn">
								<button type="button" class="btn BtnOrange CleanCurpConyuge"><span class="icon icon-cancel-circle"></span></button>
							</div>
						</div>
					</div>

					<div class="form-group col-md-3">
						<label for="fecha_nac_conyuge">Fecha de Nacimiento</label>
						<div class='input-group date d_nac_cony' id="picker-container_cony">
							<input type="text" name="fecha_nac_conyuge" class="form-control DatosConyuge readonly" id="cony_fecha_nac">
		                    <span class="input-group-addon">
		                        <span class="icon icon-calendar"></span>
		                    </span>
		                </div>
					</div>

					<div class="form-group col-md-3">
						<label for="lugar_nac_conyuge">Lugar de Nacimiento</label>
						<input type="text" name="lugar_nac_conyuge" class="form-control DatosConyuge" id="cony_lugar_nac">
					</div>

					<div class="form-group col-md-3 bienes">
						<label>Bienes</label>
						<select name="bienes" class="DatosConyuge SimpleSelect" id="bienes">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($bienes as $bienes)
								<option value="{{$bienes}}">{{$bienes}}</option>	
							@endforeach
						</select>
					</div>
					
					<div class="col-md-3 form-group text-center">
						<label>Generar CURP</label><br>
						<button type="button" style="width:100%;" class="btn BtnOrange Cbuscar-curp">Generar</button>
					</div>

					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnGray BtnConyuge2"><span class="icon icon-arrow-left"></span> Regresar</button>
						<button type="button" class="btn BtnOrange BtnConyuge">Continuar <span class="icon icon-arrow-right"></span></button>
					</div>
				</div>


			</div>

			<div role="tabpanel" class="tab-pane fade" id="modalidad">
				
				<!-- Modalidad Requerida -->
				<div class="row">
					<div class="form-group col-md-4" id="DivAdquisicion">
						<label for="adquisicion">Vivienda o Lote</label>
						<select name="adquisicion" class="SimpleSelect" id="Adquisicion" required>
							<option selected value="">Seleccionar</option>
							@foreach($adquisicion as $adq)
								<option value="{{$adq}}">{{$adq}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4" id="DivAutoConstruccion">
						<label for="autoconstruccion">Vivienda en Lote Propio</label>
						<select name="autoconstruccion" class="SimpleSelect" id="AutoConstruccion" required>
							<option selected value="">Seleccionar</option>
							@foreach($autoconstruccion as $aut)
								<option value="{{$aut}}">{{$aut}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4" id="DivMejoramiento">
						<label for="mejoramiento">Ampliación - Mejoramiento</label>
						<select name="mejoramiento" class="SimpleSelect" id="Mejoramiento" required>
							<option selected value="">Seleccionar</option>
							@foreach($mejoramientos as $mej)
								<option value="{{$mej->id_mejoramiento}}">{{$mej->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="interes">Zona de Interés</label>
						<select name="zona_interes" class="SimpleSelect" required>
							<option selected value="">Seleccionar</option>
							@foreach($interes as $zona)
								<option value="{{$zona}}">{{$zona}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="grupo atencion">Grupo de Atención</label>
						<select name="grupo_atencion" class="SimpleSelect" required>
							<option selected value="">Seleccionar</option>
							@foreach($grupos as $atencion)
								<option value="{{$atencion->id_grupo}}">{{$atencion->nombre}}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="row">
					<div class="form-group text-center">
						<a href="{{route('insuvi')}}" class="btn BtnRed text-left"><span class="icon icon-reply"></span> Cancelar</a>
						<button type="button" class="btn BtnGray BtnGeneral"><span class="icon icon-arrow-left"></span> Regresar</button>
						<button type="submit" class="btn BtnOrange"><span class="icon icon-upload"></span> Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- Modal MENSAJE ERROR -->
	<div class="modal fade" id="MensajeError" tabindex="-1" role="dialog" aria-labelledby="MensajeErrors" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="MensajeErrors"><b>Asegurate de completar los campos correctamente</b></h4>
				</div>
				<div class="modal-body">
					<div class="mensaje-error-ajax"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn BtnOrange" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal IMAGEN CARGANDO -->
	<div class="modal fade" id="ImgCargando" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="Cargando" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-body text-center">
					<img src="{{asset('img/cargando.gif')}}" >
				</div>
			</div>
		</div>
	</div>
@stop

@section('js')

	<script src="{{ asset('datetimepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('datetimepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

	<script src="{{ asset('js/AtencionSolicitud.js') }}"></script>
	<script type="text/javascript">
		var date   = new Date();
		var inicio = (date.getFullYear()-18) + "-" + (date.getMonth()+1) + "-" + date.getDate();
		var fin    = (date.getFullYear()-99) + "-" + (date.getMonth()+1) + "-" + date.getDate();
		
      	$('.d_nac').datepicker({
		    format: 'yyyy-mm-dd',
		    autoclose:true,
		    language:'es',
		    //orientation: "bottom",
		    startView:2,
		    startDate: fin,
		    endDate: inicio,
		    clearBtn: true,
		    container: "#picker-container",
		    title:"Solicitante"
		});

		$('.d_nac_cony').datepicker({
		    format: 'yyyy-mm-dd',
		    autoclose:true,
		    language:'es',
		    //orientation: "bottom",
		    startView:2,
		    startDate: fin,
		    endDate: inicio,
		    clearBtn: true,
		    container: "#picker-container_cony",
		    title:"Cónyuge"
		});

   </script>
@stop
