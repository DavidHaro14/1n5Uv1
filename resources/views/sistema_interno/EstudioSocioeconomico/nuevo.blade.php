@extends('sistema_interno.template.cuerpo')

@section('titulo', 'Estudio Socioeconomico')

@section('contenido')
	<!-- Tabla de los datos del cliente -->
	<table class="table table-hover">
		<tbody>
			<tr>
				<td colspan="6" class="text-center subtitle"><b>Datos del Solicitante</b></td>
			</tr>
			<tr>
				<td><b>Nombre:</b></td><td> {{$solicitante->nombre}} {{$solicitante->ape_paterno}} {{$solicitante->ape_materno}}</td>
				<td><b>CURP:</b></td><td> {{$solicitante->curp}}</td>
				<td><b>Ocupación:</b></td><td> {{$solicitante->ocupacion->nombre}}</td>
			</tr>
			<tr>
				<td><b>Estado Civil:</b></td><td> {{$solicitante->estado_civil}}</td>
				<td><b>Dependientes:</b></td><td> {{$solicitante->dependientes}}</td>
				<td><b>Escolaridad:</b></td><td> {{$solicitante->escolaridad}}</td>
			</tr>
			@if($solicitante->conyuge)
			  	<tr>
			  		<tr>
						<td colspan="6" class="text-center subtitle"><b>Datos del Cónyuge</b></td>
					</tr>
					<tr>
						<td><strong>Nombre:</strong></td><td>{{$solicitante->conyuge->nombre . " " . $solicitante->conyuge->ape_paterno . " " . $solicitante->conyuge->ape_materno}}</td>
						<td><strong>CURP:</strong></td><td>{{$solicitante->conyuge->curp}}</td>
						<td><strong>Lugar de Nacimiento:</strong></td><td>{{$solicitante->conyuge->lugar_nac}}</td>
					</tr>
			  	</tr>
		  	@endif
		</tbody>
	</table>

	@include('errors.errores')

	<div class="row"><!-- Pestañas -->
		<ul class="nav nav-tabs nav-justified" role="tablist">
		  	<li role="presentation" class="active">
		  		<!-- <a href="#solicitante" aria-controls="solicitante" role="tab" data-toggle="tab">Solicitante</a> -->
		  		<a href="#solicitante">Solicitante</a>
		  	</li>

			@if($solicitante->conyuge)
			  	<li role="presentation" id="TabConyuge">
			  		<!-- <a href="#conyuge" aria-controls="conyuge" role="tab" data-toggle="tab">Conyuge</a> -->
		  			<a href="#conyuge">Conyuge</a>
			  	</li>
		  	@endif

		  	<li role="presentation">
		  		<!-- <a href="#familiares" aria-controls="familiares" role="tab" data-toggle="tab">Datos Familiares</a> -->
		  		<a href="#familiares">Datos Familiares</a>
		  	</li>

		  	<li role="presentation">
		  		<!-- <a href="#IngrGst" aria-controls="IngrGst" role="tab" data-toggle="tab">Ingresos y Gastos</a> -->
		  		<a href="#IngrGst">Ingresos y Gastos</a>
			</li>

			<li role="presentation">
		  		<!-- <a href="#referencias" aria-controls="referencias" role="tab" data-toggle="tab">Referencias</a> -->
		  		<a href="#referencias">Referencias</a>
			</li>

			<li role="presentation">
		  		<!-- <a href="#vivienda" aria-controls="vivienda" role="tab" data-toggle="tab">Datos Vivienda</a> -->
		  		<a href="#vivienda">Datos Vivienda</a>
			</li>
		</ul>
	</div><!-- /Pestañas -->

	<form action="{{ route('socioeconomicoA') }}" method="POST">
		<input type="hidden" name="num" value="{{$solicitante->id_cliente}}" id="num">
		<input type="hidden" name="estudio" value="{{$existe}}">
		{{ csrf_field() }}
		<div class="tab-content panel-body"><!-- Content PanelBody -->
			<div role="tabpanel" class="tab-pane active" id="solicitante"><!-- TabPanel Solicitante-->
				<p><b>Datos laborales:</b></p><br>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="empresa">Nombre Empresa</label>
						<input type="text" name="empresa" class="form-control" required>
					</div>

					<div class="form-group col-md-3">
						<label for="tel_empresa">Telefono Empresa</label>
						<input type="text" name="tel_empresa" class="form-control OnlyNumber" maxlength="13">
					</div>

					<div class="form-group col-md-3">
						<label for="antiguedad">Antiguedad (Meses)</label>
						<input type="number" name="antiguedad" class="form-control" required>
					</div>

					<div class="form-group col-md-3">
						<label for="estado">Estado</label>
						<select required class="SimpleSelect" id="state">
							<option selected hidden value="">Seleccionar</option>
							@foreach($estados as $state)
								<option value="{{$state->id_estado}}">{{$state->nombre}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="municipio">Municipio</label>
						<select required class="SimpleSelect" id="town">
							<option selected hidden value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="localidad">Localidad</label>
						<select required class="SimpleSelect" id="location">
							<option selected hidden value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="colonia">Colonia</label>
						<select required class="SimpleSelect" id="colonie">
							<option selected hidden value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="calle">Calle</label>
						<select name="calle" required class="SimpleSelect" id="street">
							<option selected hidden value="">Seleccionar</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="num_ext"># Ext</label>
						<input type="text" class="form-control OnlyNumberDomicilio" name="num_ext" required value="0" maxlength="7">
					</div>

					<div class="form-group col-md-3">
						<label for="num_int"># Int</label>
						<input type="text" class="form-control OnlyNumberDomicilio" name="num_int" required value="0" maxlength="7">
					</div>
					
					<div class="checkbox col-md-3 margin-checkbox">
						<label>
							<input type="checkbox" value="1" name="asalariado"> Asalariado
						</label>
					</div>

					<div class="form-group col-md-3">
						<label for="servicio_salud">Servicio Salud</label>
						<select name="servicio_salud" required class="SimpleSelect" id="ServicioSalud">
							<option selected hidden value="">Seleccionar</option>
							@foreach($serv_salud as $salud)
								<option value="{{$salud}}">{{$salud}}</option>	
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="servicio_vivienda">Servicio Vivienda</label>
						<select name="servicio_vivienda" required class="SimpleSelect">
							<option selected hidden value="">Seleccionar</option>
							@foreach($serv_vivienda as $vivienda)
								<option value="{{$vivienda}}">{{$vivienda}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3" id="DivSeguro" style="display:none;">
						<label for="seg_social">No. Seguro Social</label>
						<input type="number" class="form-control" name="seg_social" maxlength="15" id="Seguro">
					</div>
					
					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnOrange" id="BtnSolicitante">Continuar <span class="icon icon-arrow-right"></span></button>
					</div>
				</div>

			</div><!-- /TabPanel Solicitante -->

			<div role="tabpanel" class="tab-pane" id="IngrGst"><!-- TabPanel Ingresos Gastos-->

				<div class="row col-md-6 col-sm-6 text-center"><!-- Ingresos -->
					<h3><b>Ingresos</b></h3>
					<hr>

					<div class="col-md-4 col-sm-4 margin-checkbox text-right">
						<label>Periodo Mensual</label>
					</div>

					<div class="form-group col-md-4 col-sm-4 text-center">
						<label for="ingr_total">Total Ingresos</label>
						<input type="number" class="form-control" name="ingr_total" value="0" id="TotalIngresos" readonly required>
					</div>

					<div class="form-group col-md-4 col-sm-4 text-center">
						<label for="gst_total">Total Gastos</label>
						<input type="number" class="form-control" name="gst_total" value="0" id="TotalGastos" readonly required>
					</div>

					<div class="col-md-4 col-sm-4 margin-checkbox text-right">
						<label>Solicitante</label>
					</div>

					<div class="form-group col-md-4 col-sm-4 text-center">
						<label for="ingr_prin">Principal</label>
						<input type="number" class="form-control ingresos" name="ingr_prin" value="0" id="solicitante1" required>
					</div>

					<div class="form-group col-md-4 col-sm-4 text-center">
						<label for="ingr_sec">Secundario</label>
						<input type="number" class="form-control ingresos" name="ingr_sec" value="0" id="solicitante2" required>
					</div>
					
					@if($solicitante->estado_civil == 'VIUDO(A)' || $solicitante->estado_civil == 'UNION LIBRE' || $solicitante->estado_civil == 'CASADO(A)')
						<div class="row">
							<div class="col-md-4 col-sm-4 margin-checkbox text-right">
								<label>Conyuge</label>
							</div>

							<div class="form-group col-md-4 col-sm-4 text-center">
								<label for="ingr_prin_conyuge">Principal</label>
								<input type="number" class="form-control ingresos" name="ingr_prin_conyuge" value="0" id="conyuge1" required>
							</div>

							<div class="form-group col-md-4 col-sm-4 text-center">
								<label>Secundario</label>
								<input type="number" class="form-control ingresos" name="ingr_sec_conyuge" value="0" id="conyuge2" required>
							</div>
						</div>
					@endif

					<div class="row">
						<div class="col-md-4 col-sm-4 margin-checkbox text-right">
							<label>Familiares</label>
						</div>

						<div class="form-group col-md-4 col-sm-4 margin-checkbox">
							<input type="number" class="form-control ingresos" value="{{$solicitante->familiares->sum('ingresos')}}" id="ingrfamiliar" name="ingr_familiar" readonly required>
						</div>
					</div>

				</div>
				
				<div class="row col-md-6 text-center border-left"><!-- Gastos -->
					<h3><b>Gastos</b></h3>
					<hr>

					<div class="form-group col-md-4">
						<label for="gst_alimentacion">Alimentacion</label>
						<input type="number" class="form-control gastos" name="gst_alimentacion" required value="0" id="alimentacion">
					</div>

					<div class="form-group col-md-4">
						<label for="gst_luz">Luz</label>
						<input type="number" class="form-control gastos" name="gst_luz" required value="0" id="luz">
					</div>

					<div class="form-group col-md-4">
						<label for="gst_agua">Agua</label>
						<input type="number" class="form-control gastos" name="gst_agua" required value="0" id="agua">
					</div>

					<div class="form-group col-md-4">
						<label for="gst_educacion">Educacion</label>
						<input type="number" class="form-control gastos" name="gst_educacion" required value="0" id="educacion">
					</div>

					<div class="form-group col-md-4">
						<label for="gst_renta">Renta</label>
						<input type="number" class="form-control gastos" name="gst_renta" required value="0" id="renta">
					</div>

					<div class="form-group col-md-4">
						<label for="gst_transporte">Transporte</label>
						<input type="number" class="form-control gastos" name="gst_transporte" required value="0" id="transporte">
					</div>
					
					<div class="form-group col-md-4">
						<label for="gst_otros">Otros Gastos</label>
						<input type="number" class="form-control gastos" name="gst_otros" required value="0" id="otros">
					</div>	

				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnGray" id="BtnBackFamiliares"><span class="icon icon-arrow-left"></span> Regresar</button>
						<button type="button" class="btn BtnOrange" id="BtnIngresosGastos">Continuar <span class="icon icon-arrow-right"></span></button>
					</div>
				</div>

			</div><!-- /TabPanel Ingresos Gastos -->

			@if($solicitante->conyuge)

				<div role="tabpanel" class="tab-pane" id="conyuge"><!-- TabPanel Conyuge-->

					<input type="hidden" name="cony" value="1">
					<p><b>Datos laborales:</b></p><br>
					<div class="row">

						<div class="form-group col-md-3">
							<label for="ocupacion_conyuge">Ocupacion</label>
							<select name="ocupacion_conyuge" class="form-control" required>
								<option selected hidden value="">Seleccionar</option>
								@foreach($ocupaciones as $ocup)
									<option value="{{ $ocup->id_ocupacion }}">{{ $ocup->nombre }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="empresa_conyuge">Nombre Empresa</label>
							<input type="text" name="empresa_conyuge" class="form-control" required>
						</div>

						<div class="form-group col-md-3">
							<label for="tel_empresa_conyuge">Telefono Empresa</label>
							<input type="text" name="tel_empresa_conyuge" class="form-control OnlyNumber" maxlength="13" >
						</div>

						<div class="form-group col-md-3">
							<label for="antiguedad_conyuge">Antiguedad (Meses)</label>
							<input type="number" name="antiguedad_conyuge" class="form-control" value="0" required>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<label for="estado_conyuge">Estado</label>
							<select required class="SimpleSelect" id="stateC">
								<option selected hidden value="">Seleccionar</option>
								@foreach($estados as $state)
									<option value="{{$state->id_estado}}">{{$state->nombre}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="municipio_conyuge">Municipio</label>
							<select required class="SimpleSelect" id="townC">
								<option selected hidden value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="localidad_conyuge">Localidad</label>
							<select required class="SimpleSelect" id="locationC">
								<option selected hidden value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="colonia_conyuge">Colonia</label>
							<select required class="SimpleSelect" id="colonieC">
								<option selected hidden value="">Seleccionar</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<label for="calle_conyuge">Calle</label>
							<select name="calle_conyuge" required class="SimpleSelect" id="streetC">
								<option selected hidden value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="num_ext_conyuge"># Ext</label>
							<input type="text" class="form-control OnlyNumberDomicilio" name="num_ext_conyuge" required value="0" maxlength="7">
						</div>

						<div class="form-group col-md-3">
							<label for="num_int_conyuge"># Int</label>
							<input type="text" class="form-control OnlyNumberDomicilio" name="num_int_conyuge" required value="0" maxlength="7">
						</div>
						
						<div class="checkbox col-md-3 margin-checkbox">
							<label>
								<input type="checkbox" value="1" name="asalariado_conyuge"> Asalariado
							</label>
						</div>

					</div>

					<div class="row">
						<div class="form-group col-md-3">
							<label for="servicio_salud_conyuge">Servicio Salud</label>
							<select name="servicio_salud_conyuge" required class="SimpleSelect" id="ServicioSaludConyuge">
								<option selected hidden value="">Seleccionar</option>
								@foreach($serv_salud_conyuge as $saludC)
									<option value="{{$saludC}}">{{$saludC}}</option>	
								@endforeach
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="servicio_vivienda_conyuge">Servicio Vivienda</label>
							<select name="servicio_vivienda_conyuge" required class="SimpleSelect">
								<option selected hidden value="">Seleccionar</option>
								@foreach($serv_vivienda_conyuge as $viviendaC)
									<option value="{{$viviendaC}}">{{$viviendaC}}</option>	
								@endforeach
							</select>
						</div>

						<div class="form-group col-md-3" id="DivSeguroConyuge" style="display:none;">
							<label for="seg_social_conyuge">No. Seguro Social</label>
							<input type="number" class="form-control" name="seg_social_conyuge" maxlength="15" id="SeguroConyuge">
						</div>

						<div class="col-md-12 text-center">
							<button type="button" class="btn BtnGray" id="BtnBackSolicitante"><span class="icon icon-arrow-left"></span> Regresar</button>
							<button type="button" class="btn BtnOrange" id="BtnConyuge">Continuar <span class="icon icon-arrow-right"></span></button>
						</div>
					</div>

				</div><!-- /TabPanel Conyuge -->

			@else
				<input type="hidden" name="cony" value="0">
			@endif

			<div role="tabpanel" class="tab-pane" id="familiares"><!-- TabPanel Familiares-->
				
				<div class="row">
					<input type="hidden" value="{{ csrf_token() }}" id="token">
					<div class="form-group col-md-3">
						<label for="nombre_familiar">Nombre</label>
						<input type="text" class="limpiar form-control" id="nombre_familiar">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_p_familiar">Apellido Paterno</label>
						<input type="text" class="limpiar form-control" id="paterno_familiar">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_m_familiar">Apellido Materno</label>
						<input type="text" class="limpiar form-control" id="materno_familiar">
					</div>

					<div class="form-group col-md-3">
						<label for="parentesco_familiar">Parentesco</label>
						<select class="limpiar SimpleSelect" id="parentesco_familiar">
							<option selected hidden value="">Seleccionar</option>
							@foreach($parentescoF as $parentF)
								<option value="{{$parentF}}">{{$parentF}}</option>	
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="genero_familiar">Genero</label>
						<select class="limpiar SimpleSelect" id="genero_familiar">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($generoF as $genF)
								<option value="{{$genF}}">{{$genF}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="edad">Edad</label>
						<input type="text" class="limpiarNum form-control OnlyNumber" maxlength="2" value="0" id="edad_familiar">
					</div>

					<div class="form-group col-md-3">
						<label for="ocupacion_familiar">Ocupacion</label>
						<input type="text" class="limpiar form-control" id="ocupacion_familiar">
					</div>
						
					<div class="form-group col-md-3">
						<label for="ingreso_familiar">Ingreso Mensual</label>
						<input type="text" class="limpiarNum form-control OnlyMoney" value="0" id="ingreso_familiar">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnOrange" id="btnAdd-familiar"><span class="icon-plus"></span> Agregar</button>
					</div>
				</div>
				<br>
				<div class="alert alert-danger msj-error" role="alert" style="display:none;">
			    	<button type="button" class="close close-alert">
					  <span aria-hidden="true">&times;</span>
					</button>
			        <div id="msj"></div>
			    </div>
				<br>
				<div class="row" id="table-familiar">
					<table class="table">
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Parentesco</th>
								<th>Edad</th>
								<th>Genero</th>
								<th>Ocupacion</th>
								<th>Ingresos</th>
							</tr>
						</thead>
						<tbody id="mostrar-familiares">
							@foreach($solicitante->familiares as $fam)
								<tr>
									<td>{{$fam->nombre}} {{$fam->ape_paterno}} {{$fam->ape_materno}}</td>
									<td>{{$fam->parentesco}}</td>
									<td>{{$fam->edad}}</td>
									<td>{{$fam->genero}}</td>
									<td>{{$fam->ocupacion}}</td>
									<td class="familyingr">{{$fam->ingresos}}</td>
									<td><button type="button" class="btn btn-danger btn-sm btnquitar" value="{{$fam->id_familiar}}"><span class='icon icon-bin2'></span></button></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						@if($solicitante->conyuge)
							<button type="button" class="btn BtnGray" id="BtnBackConyuge"><span class="icon icon-arrow-left"></span> Regresar</button>
						@else
							<button type="button" class="btn BtnGray" id="BtnBackSolcitante"><span class="icon icon-arrow-left"></span> Regresar</button>
						@endif
						<button type="button" class="btn BtnOrange" id="BtnFamiliares">Continuar <span class="icon icon-arrow-right"></span></button>
					</div>
				</div>

			</div><!-- /TabPanel Familiares -->

			<div role="tabpanel" class="tab-pane" id="referencias"><!-- TabPanel Referencia-->
				
				<div class="row">
					<input type="hidden" value="{{ csrf_token() }}" id="token_referencia">
					<div class="form-group col-md-3">
						<label for="nombre_referencia">Nombre</label>
						<input type="text" class="limpiar form-control" id="nombre_referencia">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_p_referencia">Apellido Paterno</label>
						<input type="text" class="limpiar form-control" id="paterno_referencia">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_m_referencia">Apellido Materno</label>
						<input type="text" class="limpiar form-control" id="materno_referencia">
					</div>

					<div class="form-group col-md-3">
						<label for="parentesco_referencia">Parentesco</label>
						<select class="limpiar SimpleSelect" id="parentesco_referencia">
							<option selected hidden value="">Seleccionar</option>
							@foreach($parentescoR as $parentR)
								<option value="{{$parentR}}">{{$parentR}}</option>	
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="genero_referencia">Genero</label>
						<select class="limpiar SimpleSelect" id="genero_referencia">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($generoR as $genR)
								<option value="{{$genR}}">{{$genR}}</option>	
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="telefono_referencia">Telefono</label>
						<input type="text" class="limpiar form-control OnlyNumber" maxlength="13" id="telefono_referencia">
					</div>

					<div class="form-group col-md-6">
						<label for="domicilio_referencia">Domicilio</label>
						<input type="text" class="limpiar form-control" id="domicilio_referencia">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnOrange" id="btnAdd-referencia"><span class="icon-plus"></span> Agregar</button>
					</div>
				</div>
				<br>
				<div class="alert alert-danger msj-error2" role="alert" style="display:none;">
			    	<button type="button" class="close close-alert">
					  <span aria-hidden="true">&times;</span>
					</button>
			        <div id="msj2"></div>
			    </div>
				<br>
				<div class="row">
					<table class="table">
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Parentesco</th>
								<th>Genero</th>
								<th>Domicilio</th>
								<th>Telefono</th>
							</tr>
						</thead>
						<tbody id="mostrar-referencias">
							@foreach($solicitante->referencias as $ref)
								<tr>
									<td>{{$ref->nombre}} {{$ref->ape_paterno}} {{$ref->ape_materno}}</td>
									<td>{{$ref->parentesco}}</td>
									<td>{{$ref->genero}}</td>
									<td>{{$ref->domicilio}}</td>
									<td>{{$ref->telefono}}</td>
									<td><button type="button" class="btn btn-danger btn-sm btnquitar" value="{{$ref->id_referencia}}"><span class='icon icon-bin2'></span></button></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="button" class="btn BtnGray" id="BtnBackIngresosGastos"><span class="icon icon-arrow-left"></span> Regresar</button>
						<button type="button" class="btn BtnOrange" id="BtnReferencias">Continuar <span class="icon icon-arrow-right"></span></button>
					</div>
				</div>

			</div><!-- /TabPanel Referencia -->

			<div role="tabpanel" class="tab-pane" id="vivienda"><!-- TabPanel Vivienda-->
				
				<div class="row">

					<div class="form-group col-md-4">
						<label>No. Cuartos</label>
						<input type="number" class="form-control" name="cuartos" max="99" value="0">
					</div>

					<div class="form-group col-md-4">
						<label>Piso</label>
						<select name="piso" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($pisos as $piso)
								<option value="{{ $piso->id_piso }}">{{ $piso->nombre }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4">
						<label>Muro</label>
						<select name="muro" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($muros as $muro)
								<option value="{{ $muro->id_muro }}">{{ $muro->nombre }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">

					<div class="form-group col-md-4">
						<label>Techo</label>
						<select name="techo" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($techos as $techo)
								<option value="{{ $techo->id_techo }}">{{ $techo->nombre }}</option>
							@endforeach
						</select>
					</div>
					
					<div class="form-group col-md-4">
						<label>Estado Vivienda</label>
						<select name="estado_vivienda" required class="SimpleSelect" id="Vivienda">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($viviendas as $viv)
								<option value="{{$viv}}">{{$viv}}</option>	
							@endforeach
						</select>
					</div>

					<div class="checkbox col-md-3 margin-checkbox" style="display:none;" id="DivEscrituracion">
						<label>
							<input type="checkbox" value="1" name="escrituracion" id="CheckBoxEscrituracion"> Escrituración
						</label>
					</div>

				</div>

				<br><br>
				<div class="row">
					<div class="form-group text-center">
						<button type="button" class="btn BtnGray" id="BtnBackReferencias"><span class="icon icon-arrow-left"></span> Regresar</button>
						<a href="{{route('insuvi')}}" class="btn BtnRed"><span class="icon icon-exit"></span> Cancelar</a>
						<button type="submit" class="btn BtnGreen"><span class="icon icon-upload"></span> Guardar</button>
					</div>
				</div>
			</div><!-- /TabPanel Vivienda -->
		</div><!-- /Content PanelBody -->
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
	<script src="{{ asset('js/estudio.js') }}"></script>
@stop