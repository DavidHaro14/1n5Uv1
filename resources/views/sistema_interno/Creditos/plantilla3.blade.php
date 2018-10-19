@extends('sistema_interno.template.cuerpo')

@section('titulo','Crédito')

@section('head')
	<link rel="stylesheet" href="{{ asset('datetimepicker/css/bootstrap-datepicker.min.css') }}">
@stop

@section('contenido')
	<div class="row">
		<h4 class="text-center" style="padding:8px;"><b>Contratación de </b><b class="text-capitalize">{{ strtolower($dem->cliente->nombre . " " . $dem->cliente->ape_paterno . " " . $dem->cliente->ape_materno)}}</b></h4>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			@if($actualizar > 0)
				<a href="{{route('credito',['id'=>$modificar->demanda_id,'credito'=>$modificar->clave_credito])}}" class="btn BtnOrange"><span class="icon icon-magic-wand"></span> Regresar Datos</a>
			@endif
		</div>
	</div>
	<br>
	<div class="row">
		<ul class="nav nav-tabs nav-justified" role="tablist">
		  	<li role="presentation" class="active li-info">
		  		<a href="#panel-info" aria-controls="panel-info" role="tab" data-toggle="tab">Información</a>
			</li>

		  	<li role="presentation" class="li-credito">
		  		<a href="#panel-credito" aria-controls="panel-credito" role="tab" data-toggle="tab">Crédito Escrituración</a>
		  	</li>
		  	
			<li role="presentation" <?php if($lotes){ echo 'style="display:none;"'; }?> class="li-lote">
		  		<a href="#panel-lote" aria-controls="panel-lote" role="tab" data-toggle="tab">Regularización</a>
		  	</li>
		</ul>
	</div>
	<div class="tab-content panel-body">
		<div role="tabpanel" class="tab-pane" id="panel-credito">
			<form action="{{route('creditoC')}}" method="POST" onsubmit="return ValidacionLote()">
				<input type="hidden" value="{{$dem->id_demanda}}" name="demanda">
				<input type="hidden" value="0" name="otro_lote" id="otro_lote">
				@if($actualizar > 0)
					<input type="hidden" value="{{$modificar->clave_credito}}" name="credito_modificar">
					<input type="hidden" name="_method" value="PUT">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				@else
					{{ csrf_field() }}
				@endif
				<div class="row">
					@if($lotes)
						<div class="form-group col-md-3">
							<label>Lotes Pagados Escrituración</label>
							<select name="lote" class="form-control" id="select-lote" required>
								@if($actualizar > 0)
									<option value="{{$modificar->lote->id_lote}}" hidden selected>{{" M-" . $modificar->lote->no_manzana . " " . $modificar->lote->no_lote}}</option>
								@endif
								<option value="" hidden>Seleccionar</option>
									@foreach($lotes as $lot)
										<option value="{{$lot->id_lote}}">{{" M-" . $lot->no_manzana . " " . $lot->no_lote}}</option>
									@endforeach
								<option value="0">OTRO LOTE</option>
							</select>				
						</div>
					@else
						<input type="hidden" value="0" name="lote" id="select-lote">
					@endif
					<div class="form-group col-md-3">
						<label>Monto del Crédito <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" class="form-control" required name="monto_credito" min="100" value="<?php if($actualizar>0)echo $modificar->valor_solucion; else echo '0';?>" id="monto_credito">
					</div>
					<div class="form-group col-md-3">
						<label>Plazo <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" class="form-control" required min="1" name="plazo" value="<?php if($actualizar>0)echo $modificar->plazo; else echo '0';?>" id="plazo">
							<div class="input-group-addon">Meses</div>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Moratorio <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" class="form-control" required min="0" name="moratorio" value="<?php if($actualizar>0)echo $modificar->moratorio * 100; else echo '0';?>">
							<div class="input-group-addon">%</div>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Enganche</label>
						<input type="hidden" name="enganche" value="{{$dem->enganche}}" id="enganche">
						<input type="text" class="form-control readonly" required value="{{number_format($dem->enganche,2)}}">
					</div>
					<div class="form-group col-md-3">
						<label>Pago Mensual</label>
						<input type="hidden" name="pago_mensual" value="<?php if($actualizar>0)echo $modificar->pago_mensual; else echo '0';?>" id="mensualidad">
						<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->pago_mensual,2); else echo '0';?>" id="format_mensualidad">
					</div>
					<div class="form-group col-md-3">
						<label>Total a Pagar</label>
						<input type="hidden" name="pago_total" value="<?php if($actualizar>0)echo $modificar->total_pagar; else echo '0';?>" id="pago_total">
						<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->total_pagar,2); else echo '0';?>" id="format_pago_total">
					</div>
					<div class="form-group col-md-3">
						<label>Fecha Inicio <span class="icon icon-pencil ColorRequired"></span></label>
						<div class='input-group date d_inicio' id="picker-container">
							<input type="text" class="form-control readonly" value="<?php if($actualizar>0)echo $modificar->fecha_inicio;?>" required name="fecha_inicio" id="fecha">
		                    <span class="input-group-addon">
		                        <span class="icon icon-calendar"></span>
		                    </span>
		                </div>
					</div>
					<div class="col-md-3">
						<center><button class="btn BtnOrange btn-corrida" type="button" data-toggle="modal" data-target="#corrida" style="margin-top:25px;"><span class="icon icon-list"></span> Tabla Corrida</button></center>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<label>Observaciones (Opcional)</label>
						<textarea name="observaciones" class="form-control" rows="4">@if($actualizar > 0){{$modificar->observaciones}}@else {{"NO HAY OBSERVACIONES"}} @endif</textarea>
					</div>
				</div>
				<div class="row">
					<div class="form-group text-center">
						<a type="button" href="{{route('insuvi')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Cancelar</a>
						<button type="submit" value="Guardar" class="btn BtnGreen"><span class="icon icon-upload"></span> Guardar</button>
					</div>
				</div>
			</form>
		</div><!-- /Div Panel Credito -->
		<div role="tabpanel" class="tab-pane active" id="panel-info">
			<div class="row">
				<table class="table table-hover">
					<tbody>
						<tr>
							<td colspan="6" class="text-center subtitle"><b>Solicitante</b></td>
						</tr>
						<tr>
							<td><strong>Clave Cliente:</strong></td><td><?php printf("%04d",$dem->cliente->id_cliente);?></td>
							<td><strong>Nombre:</strong></td><td>{{$dem->cliente->nombre . " " . $dem->cliente->ape_paterno . " " . $dem->cliente->ape_materno}}</td>
							<td><strong>CURP:</strong></td><td>{{$dem->cliente->curp}}</td>
						</tr>
						<tr>
							<td><strong>Estado de Nacimiento:</strong></td><td>{{$dem->cliente->estado_nac}}</td>
							<td><strong>Lugar de Nacimiento:</strong></td><td>{{$dem->cliente->lugar_nac}}</td>
							<td><strong>Fecha de Nacimiento:</strong></td><td>{{$dem->cliente->fecha_nac}}</td>
						</tr>
						@if($dem->cliente->conyuge)
							<tr>
								<td colspan="6" class="text-center subtitle"><b>Copropietario(a)</b></td>
							</tr>
							<tr>
								<td><strong>Nombre:</strong></td><td>{{$dem->cliente->conyuge->nombre . " " . $dem->cliente->conyuge->ape_paterno . " " . $dem->cliente->conyuge->ape_materno}}</td>
								<td><strong>CURP:</strong></td><td>{{$dem->cliente->conyuge->curp}}</td>
								<td><strong>Lugar de Nacimiento:</strong></td><td>{{$dem->cliente->conyuge->lugar_nac}}</td>
							</tr>
						@endif
						<tr>
							<td colspan="6" class="text-center subtitle"><b>Demanda</b></td>
						</tr>
						<tr>
							<td><strong>Clave Demanda:</strong></td><td><?php printf("%04d",$dem->id_demanda);?></td>
							<td><strong>Programa:</strong></td><td>{{$dem->tipo_programa->programa->nombre}}</td>
							<td><strong>Tipo Programa:</strong></td><td>{{$dem->tipo_programa->nombre}}</td>
						</tr>
						<tr>
							<td><strong>Modulo: </strong></td><td>{{ substr($dem->modulo, 5)}}</td>
							<td><strong>Tipo de Cliente:</strong></td><td>{{$dem->tipo_cliente}}</td>
							<td><strong>Fecha Demanda: </strong></td><td>{{$dem->created_at}}</td>
						</tr>
						<tr>
							<td><strong>Enganche: </strong></td><td>${{$dem->enganche}}</td>
							<td colspan="3"><strong>Observaciones: </strong></td><td>{{$dem->obervaciones}}</td>
						</tr>
						<!-- <tr class="img-carga text-center" style="display:none;">
							<td colspan="6"><img src="{{asset('img/cargando.gif')}}" alt="carga"></td>
						</tr> -->
					</tbody>
					<tbody class="tbody_lote"></tbody>
				</table>
			</div><!-- /Div row -->
		</div><!-- /Div Panel Info -->
		<div role="tabpanel" class="tab-pane" id="panel-lote">
			<div class="row DivSelectLote">
				<div class="form-group col-md-3">
					<label>Estado</label>
					<select class="SimpleSelect" id="state" required>
						@if(count($lotes) < 1 && $actualizar > 0)
							<option value="0" hidden>{{$modificar->lote->fraccionamiento->localidad->municipio->estado->nombre}}</option>
						@endif
						<option value="">Seleccionar</option>
						@foreach($estados as $est)
							<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Municipio</label>
					<select class="SimpleSelect" id="town" required>
						@if(count($lotes) < 1 && $actualizar > 0)
							<option value="0" hidden>{{$modificar->lote->fraccionamiento->localidad->municipio->nombre}}</option>
						@endif
						<option value="">Seleccionar</option>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Localidad</label>
					<select class="SimpleSelect" id="location" required>
						@if(count($lotes) < 1 && $actualizar > 0)
							<option value="0" hidden>{{$modificar->lote->fraccionamiento->localidad->nombre}}</option>
						@endif
						<option value="">Seleccionar</option>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Fraccionamiento</label>
					<select name="fraccionamiento" class="SimpleSelect" id="colonie" required>
						@if(count($lotes) < 1 && $actualizar > 0)
							<option value="0" hidden>{{$modificar->lote->fraccionamiento->nombre}}</option>
						@endif
						<option value="">Seleccionar</option>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Manzana</label>
					<select class="SimpleSelect" id="manzana" required>
						@if(count($lotes) < 1 && $actualizar > 0)
							<option value="0" hidden>{{$modificar->lote->no_manzana}}</option>
						@endif
						<option value="">Seleccionar</option>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Lote</label>
					<select name="lote" id="lote" class="form-control SimpleSelect" required>
						@if(count($lotes) < 1 && $actualizar > 0)
							<option value="{{$modificar->lote->id_lote}}" hidden>{{$modificar->lote->no_lote}}</option>
						@endif
						<option value="">Seleccionar</option>
					</select>
					<!-- <div class="input-group">
						<span class="input-group-btn">
							<button class="btn BtnOrange btn-ViewLote" type="button"><span class="icon icon-eye"></span></button>
						</span>
					</div> -->
				</div>
				<div class="form-group col-md-3 text-center" style="margin-top:20px;">
					<button class="btn BtnOrange btn-ViewLote" type="button"><span class="icon icon-eye"></span> Ver Información Lote</button>
				</div>
				<div class="form-group col-md-12 text-center">
					<button class="btn BtnRed btn-AddLote" type="button">Lote no registrado</button>
				</div>
			</div>
			<form id="form-lote" style="display:none;" >
				{{ csrf_field() }}
				<p class="subtitle text-center" style="padding:8px;"><b>Datos Generales</b></p>
				<div class="row">

					<div class="form-group col-md-3">
						<label for="fracc_lote">Estado</label>
						<select class="SimpleSelect" id="state2">
							<option value="" selected hidden>Seleccionar</option>
							@foreach($estados as $est)
								<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="fracc_lote">Municipio</label>
						<select class="SimpleSelect" id="town2">
							<option value="" selected hidden>Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="fracc_lote">Localidad</label>
						<select class="SimpleSelect" id="location2">
							<option value="" selected hidden>Seleccionar</option>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="fracc_lote">Fraccionamiento</label>
						<select class="SimpleSelect" name="fraccionamiento" id="colonie2">
							<option value="" selected hidden>Seleccionar</option>
						</select>
					</div>

				</div>

				<div class="row">

					<div class="form-group col-md-3">
						<label for="manzana">Manzana</label>
						<div class="input-group">
							<div class="input-group-addon"><b>M -</b></div>
							<input type="text" class="form-control OnlyNumber" required name="no_manzana">
						</div>
					</div>

					<div class="form-group col-md-3">
						<label for="lote">Lote</label>
						<div class="input-group">
							<div class="input-group-addon"><b>L -</b></div>
							<input type="text" class="form-control OnlyNumber" required name="no_lote">
						</div>
					</div>

					<div class="form-group col-md-3">
						<label>Calle</label>
						<input type="text" class="form-control" name="calle" required>
					</div>

					<div class="form-group col-md-3">
						<label>Numero</label>
						<input type="text" class="form-control OnlyNumber" name="numero" required>
					</div>

				</div>

				<div class="row">

					<div class="form-group col-md-3">
						<label for="clave_catastral">Clave Catastral</label>
						<input type="text" class="form-control" name="clave_catastral">
					</div>

					<div class="form-group col-md-3">
						<label>Uso Suelo</label>
						<input type="text" class="form-control" name="uso_suelo">
					</div>

					<div class="form-group col-md-3">
						<label for="superficie">Superficie</label>
						<div class="input-group">
							<input type="number" class="form-control" value="0" required name="superficie">
							<div class="input-group-addon"><b>M2</b></div>
						</div>
					</div>

					<div class="form-group col-md-3">
						<label for="precio_m2">Valor/M2</label>
						<div class="input-group">
							<div class="input-group-addon"><b>$</b></div>
							<input type="number" class="form-control" value="0.00" required name="valor_metro">
						</div>
					</div>
				</div>
				<div class="row">

					<div class="form-group col-md-3">
						<label>Ochavo</label>
						<input type="text" class="form-control" name="ochavo">
					</div>

					<div class="form-group col-md-3">
						<label for="Techo">Techo</label>
						<select name="techo" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($techos as $tech)
								<option value="{{$tech->id_techo}}">{{$tech->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="Piso">Piso</label>
						<select name="piso" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($pisos as $pis)
								<option value="{{$pis->id_piso}}">{{$pis->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="Muro">Muro</label>
						<select name="muro" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($muros as $mur)
								<option value="{{$mur->id_muro}}">{{$mur->nombre}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">

					<div class="form-group col-md-3">
						<label for="regimen">Régimen Propiedad</label>
						<select name="regimen" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($regimen as $reg)
								<option value="{{$reg->id_propiedad}}">{{$reg->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="estado_vivienda">Estado Vivienda</label>
						<select name="estado_vivienda" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($viviendas as $estado)
								<option value="{{$estado}}">{{$estado}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="escrituracion">Escrituracion</label>
						<select name="escrituracion" required class="SimpleSelect">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($escrituracion as $escr)
								<option value="{{$escr}}">{{$escr}}</option>
							@endforeach
						</select>
					</div>

					<div class="col-md-3" style="margin-top:25px;">
						<div class="checkbox-inline">
							<label>
								<input type="checkbox" name="drenaje" value="1"> Drenaje
							</label>
						</div>

						<div class="checkbox-inline">
							<label>
								<input type="checkbox" name="agua" value="1"> Agua
							</label>
						</div>

						<div class="checkbox-inline">
							<label>
								<input type="checkbox" name="electricidad" value="1"> Electricidad
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="col-md-12">
							<p class="subtitle text-center" style="padding:8px;"><b>Valor Catastral</b></p>
						</div>
						<div class="form-group col-md-6">
							<label for="val_cat_lote">Lote</label>
							<div class="input-group">
								<div class="input-group-addon"><b>$</b></div>
								<input type="number" class="form-control" value="0.00" required name="catastral_lote">
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="val_cat_construccion">Construcción</label>
							<div class="input-group">
								<div class="input-group-addon"><b>$</b></div>
								<input type="number" class="form-control" value="0.00" required name="catastral_construccion">
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="col-md-12">
							<p class="subtitle text-center" style="padding:8px;"><b>Valor Operación INSUVI</b></p>
						</div>
						<div class="form-group col-md-6">
							<label for="val_oper_lote">Lote</label>
							<div class="input-group">
								<div class="input-group-addon"><b>$</b></div>
								<input type="number" class="form-control" value="0.00" required name="insuvi_lote">
							</div>
						</div>

						<div class="form-group col-md-6">
							<label for="val_oper_piecasa">Pie Casa</label>
							<div class="input-group">
								<div class="input-group-addon"><b>$</b></div>
								<input type="number" class="form-control" value="0.00" required name="insuvi_pie_casa">
							</div>
						</div>
					</div>
				</div>

				<p class="subtitle text-center" style="padding:8px;"><b>Colindancias</b></p>

				<div class="row">
					<div class="form-group col-md-6">
						<label>Norte</label>
						<input name="norte" class="form-control" value="SIN REGISTRO" required>
					</div>
					<div class="form-group col-md-6">
						<label>Sur</label>
						<input name="sur" class="form-control" value="SIN REGISTRO" required>
					</div>
					<div class="form-group col-md-6">
						<label>Este</label>
						<input name="este" class="form-control" value="SIN REGISTRO" required>
					</div>
					<div class="form-group col-md-6">
						<label>Oeste</label>
						<input name="oeste" class="form-control" value="SIN REGISTRO" required>
					</div>
				</div>

				<div class="row" style="margin-top:25px;">
					<div class="form-group text-center">
						@if($actualizar < 1)
							<a href="{{route('credito',$dem->id_demanda)}}" class="btn BtnRed">Regresar</a>
						@else
							<a href="{{route('credito',['id'=>$modificar->demanda_id,'credito'=>$modificar->clave_credito])}}" class="btn BtnRed">Regresar</a>
						@endif
						<button type="button" class="btn BtnGreen btnSubmitLote">Registrar Lote</button>
					</div>
				</div>
			</form>
		</div><!-- /Div Panel Lote -->
	</div><!-- /Div Panel Body-->

	<!-- Modal Corrida -->
	<div class="modal fade" id="corrida" tabindex="-1" role="dialog" aria-labelledby="head-corrida">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title text-center" id="head-corrida"><b>Tabla de Pagos</b></h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row col-md-12">
	      		<table class="table table-hover">
	      			<thead>
	      				<th class="text-center">Mensualidad</th>
	      				<!-- <th>Fecha de Vencimiento</th> -->
	      				<th class="text-center">Pago Mensual</th>
	      				<th class="text-center">Saldo</th>
	      			</thead>
	      			<tbody id="mostrar_corrida">
	      				
	      			</tbody>
	      		</table>
	      	</div><br>
		  	<div class="text-center"><button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button></div>
		  </div>
		</div>
 	  </div>
	</div>

	<!-- Modal Mensaje Error Lote Regularizacion -->
	<div class="modal fade" id="msj_error" tabindex="-1" role="dialog" aria-labelledby="head-msj_error">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title" id="head-msj_error">Error</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="mensaje-error"></div>
		  </div>
		</div>
 	  </div>
	</div>
@stop

@section('js')
	<script src="{{ asset('datetimepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('datetimepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

	<script src="{{ asset('js/credito3.js') }}"></script>

	<script>
		var date   = new Date();
		
      	$('.d_inicio').datepicker({
		    format: 'yyyy-mm-dd',
		    autoclose:true,
		    language:'es',
		    startDate: date,
		    clearBtn: true,
		    container: "#picker-container",
		    title:"Inicio"
		});

	</script>

	<script>
		function ValidacionLote(){
			var lote   = $('#otro_lote').val();
			var pagado = $('#select-lote').val();
			if (lote == 0 && pagado == 0) {
				alert('Falta seleccionar el lote de la siguiente pestaña para continuar el proceso')
				return false;
			} else {
				return true;
			}
		}
	</script>
@stop
