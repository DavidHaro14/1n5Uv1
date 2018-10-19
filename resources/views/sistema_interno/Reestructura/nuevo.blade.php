@extends('sistema_interno.template.cuerpo')

@section('titulo','Reestructura')

@section('head')
	<link rel="stylesheet" href="{{ asset('datetimepicker/css/bootstrap-datepicker.min.css') }}">
@stop

@section('contenido')
	<div class="row">
		<h4 class="text-center" style="padding:8px;"><b>Reestructura para la clave: {{ $credito->clave_credito . " de " }}</b><b class="text-capitalize">{{ strtolower($credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno)}}</b></h4>
	</div>
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
			<form action="{{route('reestructura_add')}}" method="POST">
				<input type="hidden" value="{{$credito->demanda->id_demanda}}" name="id_demanda">
				<input type="hidden" value="0" name="cantidad">
				<input type="hidden" value="{{$credito->clave_credito}}" name="vieja_clave">
				<input type="hidden" value="{{$credito->enganche}}" name="enganche">
				<!-- <input type="hidden" value="{{$mens_credito->saldo}}" id="h_ca_venc">
				<input type="hidden" value="{{$mens_credito->resto - $mens_credito->saldo}}" id="h_fi_venc">
				<input type="hidden" value="{{$total_moratorios}}" id="mo"> -->
				{{-- <input type="hidden" value="{{$nueva_clave}}" name="nueva_clave"> --}}
				{{ csrf_field() }}
				<div class="row">
					<h4 class="BtnGray text-center" style="padding:8px;"><b>Crédito Actual</b></h4>
				</div>
				<div class="row" style="margin-left:5%;">
					<div class="form-group col-md-3 col-md-offset-1">
						<label>Capital Vencido</label>
						<input type="hidden" name="ca_venc" value="{{$mens_credito->saldo}}" id="ca_venc">
						<input type="text" class="form-control readonly"value="{{number_format($mens_credito->saldo,2)}}">
					</div>
					<div class="form-group col-md-3">
						<label>Financiamiento Vencido</label>
						@if($credito->tabla == "4")
							<input type="hidden" name="fi_venc" value="{{$mens_credito->resto - $mens_credito->saldo }}" id="fi_venc">
							<input type="text" class="form-control readonly" value="{{number_format($mens_credito->resto - $mens_credito->saldo,2) }}">
						@else
							<input type="text" class="form-control" required name="fi_venc" value="0" id="fi_venc" readonly>
						@endif
					</div>
					<div class="form-group col-md-3">
						<label>Moratorios Vencido</label>
						<input type="hidden" value="{{$total_moratorios}}" name="mo_venc" id="mo_venc">
						<input type="text" class="form-control readonly" value="{{number_format($total_moratorios,2)}}">
					</div>
				</div>
				<div class="row" style="margin-left:5%;">
					<div class="form-group col-md-3 col-md-offset-1">
						<label>Desc. Capital <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" class="form-control" value="0" step="0.01" max="{{$mens_credito->saldo}}" min="0" name="ca_desc" id="ca_desc">
							<span class="input-group-addon">$</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Desc. Financiamiento <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" class="form-control" value="0" step="0.01" max="{{$mens_credito->resto - $mens_credito->saldo }}" min="0" name="fi_desc" id="fi_desc" <?php if($credito->tabla == "5") echo 'readonly' ?>>
							<span class="input-group-addon">$</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Desc. Moratorios <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" class="form-control" value="0" step="0.01" max="{{$total_moratorios}}" min="0" name="mo_desc" id="mo_desc">
							<span class="input-group-addon">$</span>
						</div>
					</div>
				</div>
				<div class="row">
					<h4 class="BtnGray text-center" style="padding:8px;"><b>Crédito Nuevo</b></h4>
				</div>
				<div class="row">
					<!-- <div class="form-group col-md-3">
						<label>Intereses Vencido</label>
						<input type="text" class="form-control" required name="int_venc" value="{{$interes_venc}}" id="int_venc" readonly>
					</div> -->
					<div class="form-group col-md-3">
						<label>Monto a Reestructurar</label>
						<input type="text" class="form-control readonly" value="0" name="total_reestructurar" id="to_rees" required>
					</div>
					<div class="form-group col-md-3">
						<label>Plazo <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" class="form-control" min="1" required name="plazo" value="0" id="plazo">
							<span class="input-group-addon">Meses</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Tasa de Interés Anual <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" class="form-control" min="0" required name="tasa" value="0" id="tasa">
							<span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Moratorio <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" min="0" class="form-control" required name="moratorio" value="0">
							<span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Costo de Contado</label>
						<input type="text" class="form-control readonly" required name="costo_contado" value="0" id="costo_contado">
					</div>
					<div class="form-group col-md-3">
						<label>Costo Financiamiento</label>
						<input type="text" class="form-control readonly" required name="costo_finan" value="0" id="financiamiento">
					</div>
					<div class="form-group col-md-3">
						<label>Pago Mensual</label>
						<input type="text" class="form-control readonly" required name="pago_mensual" value="0" id="mensualidad">
					</div>
					<div class="form-group col-md-3">
						<label>Total a Pagar</label>
						<input type="text" class="form-control readonly" required name="pago_total" value="0" id="pago_total">
					</div>
					<div class="form-group col-md-3">
						<label>Fecha Inicio <span class="icon icon-pencil ColorRequired"></span></label>
						<div class='input-group date d_inicio' id="picker-container">
							<input type="text" class="form-control f_inicio readonly" required name="fecha_inicio">
		                    <span class="input-group-addon">
		                        <span class="icon icon-calendar"></span>
		                    </span>
		                </div>
					</div>
					<div class="form-group col-md-3">
						<label>Clave Crédito</label>
						<input type="text" class="form-control readonly" value="{{$nueva_clave}}" name="nueva_clave">
					</div>
					<div class="form-group col-md-3">
						<label>Tabla de cobros</label>
						<select name="tabla_cobros" id="t_cobros" class="form-control">
							<option value="4">TABLA 4</option>
							<option value="5">TABLA 5</option>
						</select>
					</div>
					<div class="col-md-3" style="margin-top:25px;">
						<center><button class="btn BtnOrange btn-corrida" type="button" data-toggle="modal" data-target="#corrida">Tabla Corrida</button></center>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<label>Observaciones (Opcional)</label>
						<textarea name="observaciones" class="form-control" rows="4">NO HAY OBSERVACIONES</textarea>
					</div>
				</div>
				<div class="row">
					<div class="form-group text-center">
						<a type="button" href="{{route('insuvi')}}" class="btn BtnRed"> <span class="icon icon-reply"></span> Cancelar</a>
						<button type="submit" class="btn BtnGreen"> <span class="icon icon-upload"></span> Guardar</button>
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
							<td><strong>Clave Cliente:</strong></td><td><?php printf("%04d",$credito->demanda->cliente->id_cliente);?></td>
							<td><strong>Nombre:</strong></td><td>{{$credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno}}</td>
							<td><strong>CURP:</strong></td><td>{{$credito->demanda->cliente->curp}}</td>
						</tr>
						<tr>
							<td><strong>Estado de Nacimiento:</strong></td><td>{{$credito->demanda->cliente->estado_nac}}</td>
							<td><strong>Lugar de Nacimiento:</strong></td><td>{{$credito->demanda->cliente->lugar_nac}}</td>
							<td><strong>Fecha de Nacimiento:</strong></td><td>{{$credito->demanda->cliente->fecha_nac}}</td>
						</tr>
						@if($credito->demanda->cliente->conyuge)
							<tr>
								<td colspan="6" class="text-center subtitle"><b>Copropietario(a)</b></td>
							</tr>
							<tr>
								<td><strong>Nombre:</strong></td><td>{{$credito->demanda->cliente->conyuge->nombre . " " . $credito->demanda->cliente->conyuge->ape_paterno . " " . $credito->demanda->cliente->conyuge->ape_materno}}</td>
								<td><strong>CURP:</strong></td><td>{{$credito->demanda->cliente->conyuge->curp}}</td>
								<td><strong>Lugar de Nacimiento:</strong></td><td>{{$credito->demanda->cliente->conyuge->lugar_nac}}</td>
							</tr>
						@endif
						<tr>
							<td colspan="6" class="text-center subtitle"><b>Demanda</b></td>
						</tr>
						<tr>
							<td><strong>Clave Demanda:</strong></td><td><?php printf("%04d",$credito->demanda->id_demanda);?></td>
							<td><strong>Programa:</strong></td><td>{{$credito->demanda->tipo_programa->programa->nombre}}</td>
							<td><strong>Tipo Programa:</strong></td><td>{{$credito->demanda->tipo_programa->nombre}}</td>
						</tr>
						<tr>
							<td><strong>Modulo: </strong></td><td>{{ substr($credito->demanda->modulo, 5)}}</td>
							<td><strong>Tipo de Cliente:</strong></td><td>{{$credito->demanda->tipo_cliente}}</td>
							<td><strong>Fecha Demanda: </strong></td><td>{{$credito->demanda->created_at}}</td>
						</tr>
						<tr>
							<td colspan="5"><strong>Observaciones: </strong></td><td>{{$credito->demanda->obervaciones}}</td>
						</tr>
						<tr>
							<td colspan="6" class="text-center subtitle"><b>Credito</b></td>
						</tr>
						<tr>
	                        <td><b>Saldo a pagar:</b></td>
	                        <td>${{number_format($credito->total_pagar,2)}}</td>
	                        <td><b>Pago mensual:</b></td>
	                        <td>${{number_format($credito->pago_mensual,2)}}</td>
	                        <td><b>Total abonado:</b></td>
	                        <td>${{number_format($credito->total_abonado,2)}}</td>
	                    </tr>
	                    <tr>
	                        <td><b>Plazo:</b></td>
	                        <td>{{$credito->plazo}} Meses</td>
	                        <td><b>Fecha inicio:</b></td>
	                        <td>{{ $credito->fecha_inicio }}</td>
	                        <td><b>Enganche:</b></td>
	                        <td>${{number_format($credito->enganche,2)}}</td>
	                    </tr>
	                    <tr>
	                        <td><b>Mens. no vencidos:</b></td>
	                        <td><b style="color:#C02942;">{{ $mens_no_pagados }}</b></td>
	                        <td><b>Mens. vencidos:</b></td>
	                        <td><b style="color:#C02942;">{{$mens_vencidos}}</b></td>
	                        <td><b>Moratorios:</b></td>
	                        <td><b style="color:#C02942;">${{number_format($total_moratorios,2)}}</b></td>
	                    </tr>
	                    <tr>
	                        <td><b>Capital vencido:</b></td>
	                        <td><b style="color:#C02942;">${{number_format($capital_vencido /*- $interes_venc*/,2)}}</b></td>
	                        <td><b>Total vencido:</b></td>
	                        <td><b style="color:#C02942;">${{number_format(($total_moratorios + $capital_vencido /*- $interes_venc*/),2)}}</b></td>
	                        <td><b>Tabla crédito:</b></td>
	                        <td><button type="button" class="btn btn-xs BtnOrange" data-toggle="modal" data-target="#credit"><span class="icon icon-eye"></span> Ver</button></td>
	                    </tr>
	                    @if($credito->lote)
		                    <tr>
								<td colspan="6" class="text-center subtitle"><b>Lote del crédito</b></td>
							</tr>
	                        <tr>
	                            <td><b>Manzana:</b></td>
	                            <td>{{$credito->lote->no_manzana}}</td>
	                            <td><b>Lote:</b></td>
	                            <td>{{$credito->lote->no_lote}}</td>
	                            <td><b>Clave Catastral:</b></td>
	                            <td>{{$credito->lote->clave_catastral}}</td>
	                        </tr>
	                        <tr>
	                            <td><b>Domicilio:</b></td>
	                            <td>{{$credito->lote->calle ." #" . $credito->lote->numero}}</td>
	                            <td><b>Fraccionamiento:</b></td>
	                            <td>{{$credito->lote->fraccionamiento->nombre}}</td>
	                            <td><b>Superficie:</b></td>
	                            <td>{{$credito->lote->superficie}} M2</td>
	                        </tr>
	                    @endif
					</tbody>
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
	      		</table>-->
	      	</div><br>
		  	<div class="text-center"><button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button></div>
		  </div>
		</div>
 	  </div>
	</div>
	<!-- Modal Tabla Credito Anterior -->
	<div class="modal fade" id="credit" tabindex="-1" role="dialog" aria-labelledby="head-credit">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title text-center" id="head-credit"><b>Tabla de Pagos Actual</b></h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row col-md-12">
	      		<table class="table table-hover">
	      			<thead>
	      				<th class="text-center">Mensualidad</th>
	      				<th>Fecha Vencimiento</th>
	      				<th>Contado</th>
	      				<th>Capital</th>
	      				<th>Interés</th>
	      				<th>Pago Mensual</th>
	      				<th>Saldo</th>
	      				<th>Estatus</th>
	      			</thead>
	      			<tbody>
	      				@foreach($mensualidades as $mens)
							<tr>
								<td class="text-center">{{$mens->no_mensualidad}}</td>
								<td class="text-center">{{ $mens->fecha_vencimiento }}</td>
								<td>${{ number_format($mens->saldo,2)}}</td>
								<td>${{ number_format($mens->capital,2)}}</td>
								<td>${{ number_format($mens->interes,2)}}</td>
								<td>${{ number_format($credito->pago_mensual,2)}}</td>
								<td>${{ number_format($mens->resto,2)}}</td>
								<td><b>{{$mens->estatus}}</b></td>
							</tr>
	      				@endforeach
	      			</tbody>
	      		</table>
	      	</div><br>
		  	<div class="text-center"><button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button></div>
		  </div>
		</div>
 	  </div>
	</div>
@stop

@section('js')
	<script src="{{ asset('datetimepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('datetimepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
	<script src="{{ asset('js/reestructura_nuevo.js') }}"></script>
	
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
@stop
