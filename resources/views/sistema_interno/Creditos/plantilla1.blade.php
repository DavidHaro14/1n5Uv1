@extends('sistema_interno.template.cuerpo')

@section('titulo','Contratación')

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
			<li role="presentation" class="active">
		  		<a href="#panel-info" aria-controls="panel-info" role="tab" data-toggle="tab">Información</a>
			</li>

		  	<li role="presentation">
		  		<a href="#panel-credito" aria-controls="panel-credito" role="tab" data-toggle="tab">Crédito</a>
		  	</li>
		</ul>
	</div>
	<div class="tab-content panel-body">
		<div role="tabpanel" class="tab-pane" id="panel-credito">
			<form action="{{route('creditoC')}}" method="POST">
				<input type="hidden" value="{{$dem->id_demanda}}" name="demanda">
				@if($actualizar > 0)
					<input type="hidden" value="{{$modificar->clave_credito}}" name="credito_modificar">
					<input type="hidden" name="_method" value="PUT">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				@else
					{{ csrf_field() }}
				@endif
				<div class="row">
					<h4 class="BtnGray" style="padding:8px;" data-toggle="collapse" data-target="#CollapseLote" aria-expanded="false" aria-controls="CollapseLote"><span class="icon icon-circle-down"></span> <b style="margin-left:45%;">Datos del Lote</b></h4>
				</div>
				<div id="CollapseLote" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
					<div class="row">
						<div class="form-group col-md-3">
							<label>Estado</label>
							<select class="SimpleSelect" id="state" required>
								@if($actualizar > 0)
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
								@if($actualizar > 0)
									<option value="0" hidden>{{$modificar->lote->fraccionamiento->localidad->municipio->nombre}}</option>
								@endif
								<option value="">Seleccionar</option>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Localidad</label>
							<select class="SimpleSelect" id="location" required>
								@if($actualizar > 0)
									<option value="0" hidden>{{$modificar->lote->fraccionamiento->localidad->nombre}}</option>
								@endif
								<option value="">Seleccionar</option>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Fraccionamiento</label>
							<select name="fraccionamiento" class="SimpleSelect" id="colonie" required>
								@if($actualizar > 0)
									<option value="0" hidden>{{$modificar->lote->fraccionamiento->nombre}}</option>
								@endif
								<option value="">Seleccionar</option>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Manzana</label>
							<select class="SimpleSelect" id="manzana" required>
								@if($actualizar > 0)
									<option value="0" hidden>{{$modificar->lote->no_manzana}}</option>
								@endif
								<option value="">Seleccionar</option>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Lote</label>
							<select name="lote" id="lote" class="SimpleSelect" required>
								@if($actualizar > 0)
									<option value="{{$modificar->lote->id_lote}}" hidden>{{$modificar->lote->no_lote}}</option>
								@endif
								<option value="">Seleccionar</option>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Superficie M2</label>
							<input type="hidden" value="<?php if($actualizar>0)echo $modificar->lote->superficie; else echo '0';?>" id="superficie">
							<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->lote->superficie,2); else echo '0';?>" id="format_superficie">
						</div>
						<div class="form-group col-md-3">
							<label>Costo por Metro Cuadrado <span class="icon icon-pencil ColorRequired"></span></label>
							<input type="number" class="form-control" min="1" required name="metro_cuadrado" value="<?php if($actualizar>0)echo $modificar->costo_metro; else echo '0';?>" id="costo_cuadrado">
						</div>
						<div class="form-group col-md-3">
							<label>Costo Terreno</label>
							<input type="hidden" name="costo_terreno" value="<?php if($actualizar>0)echo $modificar->costo_terreno; else echo '0';?>" id="costo_terreno" >
							<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->costo_terreno,2); else echo '0';?>" id="format_costo_terreno">
						</div>
						<div class="form-group col-md-3">
							<label>Costo Construcción <span class="icon icon-pencil ColorRequired"></label>
							<input type="number" class="form-control" min="1" required name="costo_construccion" value="<?php if($actualizar>0)echo $modificar->costo_construccion; else echo '0';?>" id="costo_construccion">
						</div>
						<div class="form-group col-md-3">
							<label>Valor Solución Habitacional</label>
							<input type="hidden" name="valor_solucion" value="<?php if($actualizar>0)echo $modificar->valor_solucion; else echo '0';?>" id="valor_solucion">
							<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->valor_solucion,2); else echo '0';?>" id="format_valor_solucion">
						</div>
						<div class="form-group col-md-3">
							<label>Enganche</label>
							<input type="hidden" name="enganche" value="{{$dem->enganche}}" id="enganche">
							<input type="text" class="form-control readonly" required value="{{number_format($dem->enganche,2)}}">
						</div>
					</div>
			    </div>
				<!-- <hr> -->
				<div class="row">
					<h4 class="BtnGray" style="padding:8px;" data-toggle="collapse" data-target="#CollapseSubsidios" aria-expanded="false" aria-controls="CollapseSubsidios"><span class="icon icon-circle-down"></span> <b style="margin-left:45%;">Subsidios</b></h4>
				</div>
				<div id="CollapseSubsidios" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
					<div class="row">
						<div class="form-group col-md-3">
							<label>Federal</label>
							<select name="subsidios[]" class="SimpleSelect" id="sub_fed">
								@if($actualizar > 0)
									@foreach($modificar->subsidios as $sub)
										@if($sub->tipo == "FEDERAL")
											<option value="{{$sub->id_subsidio}}" hidden>{{$sub->clave ." - $".number_format($sub->valor,2)}}</option>
										@endif
									@endforeach
								@endif
								<option value=""><b>Ninguno</b></option>
								@foreach($federal as $fed)
									<option value="{{$fed->id_subsidio}}">{{$fed->clave}} - ${{number_format($fed->valor,2)}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Estatal</label>
							<select name="subsidios[]" class="SimpleSelect" id="sub_est">
								@if($actualizar > 0)
									@foreach($modificar->subsidios as $sub)
										@if($sub->tipo == "ESTATAL")
											<option value="{{$sub->id_subsidio}}" hidden>{{$sub->clave ." - $".number_format($sub->valor,2)}}</option>
										@endif
									@endforeach
								@endif
								<option value=""><b>Ninguno</b></option>
								@foreach($estatal as $est)
									<option value="{{$est->id_subsidio}}">{{$est->clave}} - ${{number_format($est->valor,2)}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Municipal</label>
							<select name="subsidios[]" class="SimpleSelect" id="sub_mun">
								@if($actualizar > 0)
									@foreach($modificar->subsidios as $sub)
										@if($sub->tipo == "MUNICIPAL")
											<option value="{{$sub->id_subsidio}}" hidden>{{$sub->clave ." - $".number_format($sub->valor,2)}}</option>
										@endif
									@endforeach
								@endif
								<option value=""><b>Ninguno</b></option>
								@foreach($municipal as $mun)
									<option value="{{$mun->id_subsidio}}">{{$mun->clave}} - ${{number_format($mun->valor,2)}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-3">
							<label>Otros</label>
							<select name="subsidios[]" class="SimpleSelect" id="sub_otro">
								@if($actualizar > 0)
									@foreach($modificar->subsidios as $sub)
										@if($sub->tipo == "OTROS")
											<option value="{{$sub->id_subsidio}}" hidden>{{$sub->clave ." - $".number_format($sub->valor,2)}}</option>
										@endif
									@endforeach
								@endif
								<option value=""><b>Ninguno</b></option>
								@foreach($otros as $ot)
									<option value="{{$ot->id_subsidio}}">{{$ot->clave}} - ${{number_format($ot->valor,2)}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">Folio</div>
								<b><input type="text" class="form-control" name="subsidio_fed" id="txtfed" value="<?php if($actualizar > 0) echo $modificar->no_subsidio_fed ?>" readonly></b>
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">Folio</div>
								<b><input type="text" class="form-control" name="subsidio_est" id="txtest" value="<?php if($actualizar > 0) echo $modificar->no_subsidio_est ?>" readonly></b>
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">Folio</div>
								<b><input type="text" class="form-control" name="subsidio_mun" id="txtmun" value="<?php if($actualizar > 0) echo $modificar->no_subsidio_mun ?>" readonly></b>
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">Folio</div>
								<b><input type="text" class="form-control" name="subsidio_otr" id="txtotro" value="<?php if($actualizar > 0) echo $modificar->no_subsidio_otr ?>" readonly></b>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">$&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
								<input type="number" class="form-control" required 
								value=
								"<?php if($actualizar > 0){
									$encontro = 0;
									foreach($modificar->subsidios as $sub){
										if($sub->tipo == 'FEDERAL'){
											echo $sub->valor;
											$encontro++;
											break;
										}
									}
									if($encontro == 0){
										echo '0';
									}
								}?>"  readonly id="input_federal">
						    </div>
						</div>
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">$&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
								<input type="number" class="form-control" required 
								value=
								"<?php if($actualizar > 0){
									$encontro = 0;
									foreach($modificar->subsidios as $sub){
										if($sub->tipo == 'ESTATAL'){
											echo $sub->valor;
											$encontro++;
											break;
										}
									}
									if($encontro == 0){
										echo '0';
									}
								}?>" readonly id="input_estatal">
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">$&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
								<input type="number" class="form-control" required 
								value=
								"<?php if($actualizar > 0){
									$encontro = 0;
									foreach($modificar->subsidios as $sub){
										if($sub->tipo == 'MUNICIPAL'){
											echo $sub->valor;
											$encontro++;
											break;
										}
									}
									if($encontro == 0){
										echo '0';
									}
								}?>" readonly id="input_municipal">
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="input-group">
						    	<div class="input-group-addon">$&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
								<input type="number" class="form-control" required 
								value=
								"<?php if($actualizar > 0){
									$encontro = 0;
									foreach($modificar->subsidios as $sub){
										if($sub->tipo == 'OTROS'){
											echo $sub->valor;
											$encontro++;
											break;
										}
									}
									if($encontro == 0){
										echo '0';
									}
								}?>" readonly id="input_otros">
							</div>
						</div>
					</div>
				</div>
				<!-- <hr> -->
				<div class="row">
					<h4 class="BtnGray" style="padding:8px;" data-toggle="collapse" data-target="#CollapseCredito" aria-expanded="false" aria-controls="CollapseCredito"><span class="icon icon-circle-down"></span> <b style="margin-left:45%;">Datos del Crédito</b></h4>
				</div>
				<div id="CollapseCredito" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
					<div class="row">
						<div class="form-group col-md-3">
							<label>Fecha Inicio <span class="icon icon-pencil ColorRequired"></label>
							<div class='input-group date d_inicio' id="picker-container">
								<input type="text" class="form-control readonly" value="<?php if($actualizar>0)echo $modificar->fecha_inicio;?>" required name="fecha_inicio">
			                    <span class="input-group-addon">
			                        <span class="icon icon-calendar"></span>
			                    </span>
			                </div>
						</div>
						{{--  <div class="form-group col-md-3">
							<label>Plazo (Meses)</label>
							<div class="input-group">
								<input type="text" class="form-control" required name="plazo" value="0" id="plazo">
								<div class="input-group-btn"><button type="button" class="btn btn-success btn-ActPlaz"><span class="icon icon-arrow-left"></span></button></div>
							</div>
						</div>  --}}
						<div class="form-group col-md-3">
							<label>Plazo <span class="icon icon-pencil ColorRequired"></label>
							<div class="input-group">
								<input type="number" class="form-control" min="1" required name="plazo" value="<?php if($actualizar>0)echo $modificar->plazo; else echo '0';?>" id="plazo">
								<span class="input-group-addon">Meses</span>
							</div>
						</div>
						<div class="form-group col-md-3">
							<label>Tasa de Interés Anual <span class="icon icon-pencil ColorRequired"></label>
							<div class="input-group">
								<input type="number" class="form-control" min="0" required name="tasa" value="<?php if($actualizar>0)echo $modificar->taza_interes * 100; else echo '0';?>" id="tasa">
								<span class="input-group-addon">%</span>
							</div>
						</div>
						<div class="form-group col-md-3">
							<label>Moratorio <span class="icon icon-pencil ColorRequired"></label>
							<div class="input-group">
								<input type="number" class="form-control" min="0" required name="moratorio" value="<?php if($actualizar>0)echo $modificar->moratorio * 100; else echo '0';?>">
								<span class="input-group-addon">%</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<label>Costo de Contado</label>
							<input type="hidden" name="costo_contado" value="<?php if($actualizar>0)echo $modificar->costo_contado; else echo '0';?>" id="costo_contado">
							<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->costo_contado,2); else echo '0';?>" id="format_costo_contado">
						</div>
						{{--  <div class="form-group col-md-3">
							<label>Pago Mensual</label>
							<div class="input-group">
								<input type="text" class="form-control readonly" required name="pago_mensual" value="0" id="mensualidad">
								<div class="input-group-btn"><button type="button" class="btn btn-success btn-ActMen"><span class="icon icon-arrow-left"></span></button></div>
							</div>
						</div>  --}}
						<div class="form-group col-md-3">
							<label>Pago Mensual</label>
							<input type="hidden" name="pago_mensual" value="<?php if($actualizar>0)echo $modificar->pago_mensual; else echo '0';?>" id="mensualidad">
							<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->pago_mensual,2); else echo '0';?>" id="format_mensualidad">
						</div>
						<div class="form-group col-md-3">
							<label>Costo Financiamiento</label>
							<input type="hidden" name="costo_finan" value="<?php if($actualizar>0)echo $modificar->costo_financiamiento; else echo '0';?>" id="financiamiento">
							<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->costo_financiamiento,2); else echo '0';?>" id="format_financiamiento">
						</div>
						<div class="form-group col-md-3">
							<label>Total a Pagar</label>
							<input type="hidden" name="pago_total" value="<?php if($actualizar>0)echo $modificar->total_pagar; else echo '0';?>" id="pago_total" >
							<input type="text" class="form-control readonly" required value="<?php if($actualizar>0)echo number_format($modificar->total_pagar,2); else echo '0';?>" id="format_pago_total">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<label>Tabla de cobros</label>
							<select name="tabla_cobros" id="t_cobros" class="form-control">
								@if($actualizar > 0)
									<option value="{{$modificar->tabla}}" hidden>{{"TABLA " . $modificar->tabla}}</option>
								@endif
								<option value="4">TABLA 4</option>
								<option value="5">TABLA 5</option>
							</select>
						</div>
						<div class="form-group col-md-3" style="margin-top:25px;">
							<center><button class="btn BtnOrange btn-corrida" type="button" data-toggle="modal" data-target="#corrida"><span class="icon icon-list2"></span> Tabla Corrida</button></center>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-12">
							<label>Observaciones (Opcional)</label>
							<textarea name="observaciones" class="form-control" rows="4">@if($actualizar > 0){{$modificar->observaciones}} @else {{ "NO HAY OBSERVACIONES" }} @endif</textarea>
						</div>
					</div>
					<div class="row">
						<div class="form-group text-center">
							<a type="button" href="{{route('insuvi')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Cancelar</a>
							<button type="submit" class="btn BtnGreen"><span class="icon icon-upload"></span> Guardar</button>
						</div>
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
						<tr>
							<td colspan="6" class="text-center subtitle"><b>Lote</b></td>
						</tr>
					</tbody>
					<tbody class="tbody_lote"></tbody>
				</table>
			</div><!-- /Div Panel Cliente/Demanda -->
		</div><!-- /Div Panel Info -->
	</div><!-- /Div Panel Body-->

	<!-- Modal Corrida -->
	<div class="modal fade" id="corrida" tabindex="-1" role="dialog" aria-labelledby="head-corrida">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title text-center" id="head-corrida"><b>Tabla de Pagos</b></h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row col-md-12" id="mostrar_corrida">
	      		<!-- <table class="table table-hover">
	      			<thead>
	      				<th class="text-center">Mensualidad</th>
	      				<th>Contado</th>
	      				<th>Capital</th>
	      				<th>Interés</th>
	      				<th>Pago Mensual</th>
	      				<th>Saldo</th>
	      			</thead>
	      			<tbody id="mostrar_corrida">
	      				
	      			</tbody>
	      		</table> -->
	      	</div><br>
		  	<div class="text-center"><button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button></div>
		  </div>
		</div>
 	  </div>
	</div>
@stop

@section('js')
	<script src="{{ asset('datetimepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('datetimepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

	<script src="{{ asset('js/credito.js') }}"></script>

	<script>
		var date   = new Date();
		/*var f_nac  = $('.fecha').text();

		$('.fecha').text(formato_fecha(f_nac));*/

      	$('.d_inicio').datepicker({
		    format: 'yyyy-mm-dd',
		    autoclose:true,
		    language:'es',
		    startDate: date,
		    clearBtn: true,
		    container: "#picker-container",
		    title:"Inicio"
		});

		/*function formato_fecha(fecha){

			var date  = new Date(fecha);
			var day   = date.getDate() + 1;
			var month = date.getMonth() + 1;
			var nuevo = (day > 9 ? day : "0" + day) + "/" + (month > 9 ? month : "0" + month) + "/" + date.getFullYear();

	    	return nuevo;

	    }*/
	</script>
@stop
