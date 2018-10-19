@extends('sistema_interno.template.cuerpo')

@section('titulo','Pagos')

@section('contenido')
	<div class="col-md-12 text-center">
		<a href="{{route('caja')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
	</div>
	<br><br><br>
	@if($mensaje != "")
		<blockquote>
			<p class="text-center text-uppercase"><b>se a generado una nueva clave del crédito, si desea realizar algún pago se necesitara la siguiente clave: {{$mensaje}}</b></p>
		</blockquote>
	@endif
	<!-- ACREDITADO -->
	@if($tipo_credito == "Credito" && $credito->estatus == "PAGANDOLA")
		<form action="{{route('PagoCredito',$credito->clave_credito)}}" method="POST">
			<input type="hidden" value="{{$credito->tabla}}" name="tabla_cobro" id="tabla_cobro">
			{{csrf_field()}}

			<!-- INFORMACION CLIENTE -->
			<table class="table table-hover">
				<tbody>
					<tr>
						<td class="subtitle text-center" colspan="4"><b>Datos Cliente</b></td>
					</tr>
					<tr>
						<td><b>Clave Cliente:</b></td>
						<td>{{str_pad($credito->demanda->cliente->id_cliente, 4, "0", STR_PAD_LEFT)}}</td>
						<td><b>Cliente:</b></td>
						<td>{{$credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno}}</td>
					</tr>
					<tr>
						<td><b>CURP:</b></td>
						<td>{{$credito->demanda->cliente->curp}}</td>
						<td><b>Crédito:</b></td>
						<td>{{$credito->clave_credito}}</td>
					</tr>
					<tr>
						<td class="subtitle text-center" colspan="4"><b>Registrar Pago</b></td>
					</tr>
				</tbody>
			</table>

			<!-- BOTONES -->
			<div class="row">
				<div class="col-md-6 text-center">
					<div class="input-group">
						<div class="input-group-addon"><b>Canje de Recibo $</b></div>
					  	<select name="canje_recibo" class="form-control">
					  		<option value="">NINGUNO</option>
					  		<?php 
					  			$convenios = \DB::table('seguimiento')->select('id_seguimiento','monto_convenio')->where('credito_clave',$credito->clave_credito)->where('convenio_canjeado','0')->where('estatus_seguimiento','0')->where('convenio_pagado','1')->where('abonado_convenio','>',0)->get();
					  		?>
					  		@foreach($convenios as $convenio)
					  			<option value="{{$convenio->id_seguimiento}}">{{number_format($convenio->monto_convenio,2)}}</option>
					  		@endforeach
					  	</select>
					</div>
				</div>

				<div class="col-md-3 text-center">
					<div class="input-group">
						<div class="input-group-addon"><b>Paga con $</b></div>
					  	<input type="number" class="txt-paga form-control" value="0.00">
					</div>
				</div>

				<div class="col-md-3 text-center">
					<div class="input-group">
						<div class="input-group-addon"><b>Cambio $&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></div>
					  	<input type="number" class="txt-cambio form-control" value="0.00" readonly>
					</div>
				</div>

				<div class="col-md-6 text-center" style="margin-top:25px;">
					<div class="input-group div-abonar">
						<div class="input-group-addon"><b>Abono $&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></div>
					  	<input type="text" class="txt-abono form-control" name="abono_credito" value="0.00" readonly>
					</div>
				</div>

				<div class="col-md-6 text-center" style="margin-top:25px;">
					<div class="input-group">
						<div class="input-group-addon"><b>Total a Pagar $</b></div>
					  	<input type="number" min="100" class="total_pagar_credito form-control" name="pago_total_credito" value="0.00" readonly>
					  	<div class="input-group-btn">
							<button type="submit" class="btn BtnGreen">Cobrar</button>
					   	</div>
					</div>
				</div>
				<div class="col-md-12">
					<table class="table text-center" style="margin-top:25px;">
						<thead class="title-reestructura">
							<th class="subtitle text-center" colspan="3">Reestructura</th>
						</thead>
						<tbody>
							<td width="100">
								<label>
									<input type="radio" name="reestructura" value="MENSUALIDAD" class="RadioReestructura" disabled> Reducir Mensualidades
								</label>
							</td>
							<td width="100">
								<label>
									<input type="radio" name="reestructura" value="PLAZO" class="RadioReestructura" disabled> Reducir el Plazo
								</label>
							</td>
							<td width="100">
								<label>
									<input type="radio" name="reestructura" value="NINGUNO" class="RadioNinguno" checked> Ninguno
								</label>
							</td>
						</tbody>
					</table>
				</div>
				<div class="col-md-12" style="display:none;">
					<div id="forma_pago">
						<div class="col-md-3" style="margin-top:25px;">
							<p><b>Forma Pago: </b></p>
						</div>

						<div class="col-md-3" style="margin-top:25px;">
							<label>
								<input type="radio" name="forma_pago" value="contado" class="PagoContado"> Contado
							</label>
						</div>

						<div class="col-md-3" style="margin-top:25px;">
							<label>
								<input type="radio" name="forma_pago" value="pago_mensual" class="PagoMensual"> PagoMensual
							</label>
						</div>

						<div class="col-md-3" style="margin-top:25px;">
							<label>
								<input type="radio" name="forma_pago" value="abono" class="PagoAbono"> Abono
							</label>
						</div>
					</div>
				</div>
				
				<!-- <div class="row"> 
					<div id="reestructura">
						<div class="col-md-3" style="margin-top:25px;">
							<p><b>Reestructura: </b></p>
						</div>

						<div class="col-md-3" style="margin-top:25px;">
						</div>

						<div class="col-md-3" style="margin-top:25px;">
						</div>

						<div class="col-md-3" style="margin-top:25px;">
						</div>
					</div>
				</div> -->
			</div>

			<!-- TABLA PAGOS -->
			<div class="row">
				<div class="col-md-12">
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="subtitle text-center" colspan="10">Mensualidades</th>
							</tr>
							<tr>
								<th class="text-center"><input type="checkbox" value="true" id="CheckAll"></th>
								<th class="text-center">No. Mensualidad</th>
								<th class="text-center">Vencimiento</th>
								<th class="text-center">Pago Mensual</th>
								<th class="text-center">Contado</th>
								<th class="text-center">Capital</th>
								<th class="text-center">Interés</th>
								<th class="text-center">Saldo</th>
								<th class="text-center">Moratorios</th>
								<th class="text-center">Estatus</th>
							</tr>
						</thead>
						<tbody>
							<!-- <input type="checkbox" id="paga_todo" value="1" name="paga_todo" style="display:none;"> -->
							<input type="hidden" id="mensualidad" value="{{$credito->pago_mensual}}">
							<input type="hidden" id="saldo" value="{{$credito->total_pagar}}">
							<input type="hidden" id="limite" value="{{$limite}}">
							<input type="hidden" id="tipo_limite" value="{{$tipo_limite}}">
							<?php 
								$cont = true;  //Traer solo el primer registro de la iteracion foreach
								$abon = false; //Si la mensualidad esta pendiente por abonos -> disabled checksbox 
							?>
							@foreach($credito->mensualidades as $index=>$mensualidad)
								@if($mensualidad->estatus != "PAGADO")
									<tr class="text-center" <?php if(count($mensualidad->abonos_credito) > 0){echo 'data-toggle="modal" data-target="#pagos_credito-'.$mensualidad->no_mensualidad.'" style="background-color:rgba(243,134,48,0.7);font-weight:bold;"';} ?>>
										<?php if($cont){ ?>
											<input type="hidden" id="abonado" value="{{$mensualidad->abonos_credito->sum('abono')}}">
											<input type="hidden" id="moratorios_abonados" value="{{$mensualidad->abonos_credito->sum('moratorio')}}">
											<input type="hidden" name="contado" id="contado" value="{{$mensualidad->saldo}}">
											<input type="hidden" id="saldo_tabla_5" value="{{$mensualidad->resto}}">
										<?php } ?>
										<!-- <input type="hidden" class="estatus-{{$mensualidad->no_mensualidad}}" value="{{$mensualidad->estatus}}"> -->
										<td>
											<input type="checkbox" value="{{$mensualidad->no_mensualidad}}" name="pagos[]" <?php 
												if($abon){
													echo 'disabled';
												}  
												if(count($mensualidad->abonos_credito) > 0){
													echo 'checked';
												} 
												if($cont) {
													echo 'required';
												}
												$cont = false; 
											?>
											>
										</td>
										<td>{{ $mensualidad->no_mensualidad }}</td>
										<td>{{ $mensualidad->fecha_vencimiento }}</td>
										<td>${{ number_format($credito->pago_mensual,2) }}</td>
										@if($credito->tabla != "4")
											<td>${{ number_format($mensualidad->resto,2) }}</td>
										@else
											<td>${{ number_format($mensualidad->saldo,2) }}</td>
										@endif
										@if($credito->tabla != "4")
											<td>${{ number_format($credito->pago_mensual,2) }}</td>
										@else
											<td>${{ number_format($mensualidad->capital,2) }}</td>
										@endif
										<td>${{ number_format($mensualidad->interes,2) }}</td>
										<td>${{ number_format($mensualidad->resto,2) }}</td>
										<td>
											<?php
											// Moratorios
								            if($mensualidad->estatus == "VENCIDO"){
								               	$fecha_venc = $mensualidad->fecha_vencimiento;
								            	$total = count($credito->mensualidades);
								            	$posicion = $index + 1;
								            	$f_vencimiento = date("Y-m-d", strtotime($credito->mensualidades[$posicion]->fecha_vencimiento));
								            	if($posicion <= $total){
								            		if($f_vencimiento < date('Y-m-d
								            			')){

								               			$fecha_next = $f_vencimiento;
								               			//$fecha_next = $credito->mensualidades[$posicion]->fecha_vencimiento;
								            		} else {
								            			$fecha_next = date('Y-m-d');
								            		}
								            	} else {
								               		$fecha_next = date('Y-m-d');
								            	}

								            	$monto = $credito->pago_mensual;
								            	//$fecha_next = date("Y-m-d", strtotime($fecha_next));
								            	$fecha_venc = date("Y-m-d", strtotime($fecha_venc));
								               	$dias = \DB::table('mensualidad')->select(\DB::raw("datediff('$fecha_next','$fecha_venc') as dias"))->first();

								               	$total_moratorios = ((floatval($monto) * floatval($credito->moratorio)) / 30) * floatval($dias->dias);

								               	if (count($mensualidad->abonos_credito) > 0) {
								               		$total_moratorios -= $mensualidad->abonos_credito->sum('moratorio');
								               	}
								               	//var_dump($fecha_venc);
								               	echo '$'. number_format($total_moratorios,2);
								            } else {
								            	$total_moratorios = 0;
								            	echo '$'. number_format($total_moratorios,2);
								            }  ?>
											<input type="hidden" name="moratorios[]" class="moratorio" value="{{$total_moratorios}}">
											<input type="hidden" name="PlazoCapital[]" value="{{$mensualidad->capital}}">
											<input type="hidden" name="TodosPagos[]" value="{{$mensualidad->no_mensualidad}}">
											<?php 
												$encontro = 0;
												foreach($descuentos as $pos => $dato):
													if ($pos == $mensualidad->no_mensualidad) {
														$encontro++;
														echo '<input type="hidden" name="IdDescuento[]" value="'.$dato[0].'">';
														echo '<input type="hidden" name="EstatusDescuento[]" value="'.$dato[1].'">';
														echo '<input type="hidden" name="DescuentoCapital[]" value="'.$mensualidad->descuento_capital.'" class="DescuentoCapital">';
														echo '<input type="hidden" name="DescuentoInteres[]" value="'.$mensualidad->descuento_interes.'" class="DescuentoInteres">';
														echo '<input type="hidden" name="DescuentoMoratorio[]" value="'.$mensualidad->descuento_moratorio.'" class="DescuentoMoratorio">';
													}
												endforeach;
												if ($encontro === 0) {
													echo '<input type="hidden" name="IdDescuento[]" value="0">';
													echo '<input type="hidden" name="EstatusDescuento[]" value="NINGUNO">';
													echo '<input type="hidden" name="DescuentoCapital[]" value="0" class="DescuentoCapital">';
													echo '<input type="hidden" name="DescuentoInteres[]" value="0" class="DescuentoInteres">';
													echo '<input type="hidden" name="DescuentoMoratorio[]" value="0" class="DescuentoMoratorio">';
												}
											?>
											<!-- <input type="hidden" id="ultimo_moratorio" value="0"> -->
										</td>
										<td style="color: <?php if($mensualidad->estatus == 'VENCIDO' && count($mensualidad->abonos_credito) < 1) echo 'red' ?>"><b>{{ $mensualidad->estatus }}</b></td>
									</tr>
									<?php if(count($mensualidad->abonos_credito) > 0){ ?>
										<!-- INFO PAGOS ACREDITADO -->
										<div class="modal fade" id="pagos_credito-{{$mensualidad->no_mensualidad}}" tabindex="-1" role="dialog" aria-labelledby="head-pagos">
										  <div class="modal-dialog" role="document">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										      	<h4 class="modal-title" id="head-pagos">Pagos</h4>
										      </div>
										      <div class="modal-body">
										      	<div class="row" style="font-size:18px;padding:5px;">
										      		<div class="col-md-3 text-center"><b>Folio</b></div>
										      		<div class="col-md-3 text-center"><b>No. Pago</b></div>
										      		<div class="col-md-3 text-center"><b>Fecha Pago</b></div>
										      		<div class="col-md-3 text-center"><b>Abono</b></div>
													@foreach($mensualidad->abonos_credito as $abono)
														<div class="col-md-3 text-center">{{$abono->folio}}</div>
														<div class="col-md-3 text-center">{{$abono->no_pago}}</div>
														<div class="col-md-3 text-center">{{$abono->fecha}}</div>
														<div class="col-md-3 text-center">${{number_format($abono->abono,2)}}</div>
													@endforeach
													<div class="col-md-12" style="border-top:1px solid;margin-top:5px;padding:5px;">
														<b>Resto:</b> 
														${{ 
															number_format(
																($credito->pago_mensual + $total_moratorios) 
																- ($mensualidad->abonos_credito->sum('abono') - $mensualidad->abonos_credito->sum('moratorio')) 
															,2) 
														}}
													</div>
										      	</div>
												<div class="modal-footer">
												   	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
												</div>
										      </div>
										    </div>
										  </div>
										</div><!-- /DIV INFO ACREDITADO-->
									<?php $abon = true; } ?>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</form>
	@endif
	<!-- AHORRADOR -->
	@if($tipo_credito == "Ahorrador" && !$credito->pagado && !$credito->eliminado)
		<div class="row">
			<table class="table table-hover">
				<tbody>
					<tr>
						<td class="subtitle text-center" colspan="4"><b>Datos Cliente</b></td>
					</tr>
					<tr>
						<th>Clave:</th>
						<th>{{$credito->clave_ahorrador}}</th>
						<th>Programa:</th>
						<td>{{$credito->demanda->tipo_programa->programa->nombre}}</td>
					</tr>
					<tr>
						<th>Tipo Programa</th>
						<td>{{$credito->demanda->tipo_programa->nombre}}</td>
						<th>Cliente:</th>
						<td>{{$credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno}}</td>
					</tr>
					<tr>
						<td class="subtitle text-center" colspan="4"><b>Registrar Pago</b></td>
					</tr>
				</tbody>
			</table>
			<form action="{{route('PagoAhorrador',$credito->clave_ahorrador)}}" method="POST">
				{{ csrf_field() }}
				<div class="form-group col-md-3">
					<label>Enganche</label>
					<input type="hidden" class="enganche" value="<?php echo $credito->monto_total?>">
					<input type="text" class="form-control readonly" value="<?php echo number_format($credito->monto_total,2)?>">
				</div>

				<div class="form-group col-md-3">
					<label>Total Abonado</label>
					<input type="hidden" class="total_abonado" value="<?php echo $credito->total_abonado?>">
					<input type="text" class="form-control readonly" value="<?php echo number_format($credito->total_abonado,2)?>">
				</div>

				<div class="form-group col-md-3">
					<label>Resto</label>
					<input type="text" class="form-control readonly" value="<?php echo number_format($credito->monto_total - $credito->total_abonado,2)?>">
				</div>

				<div class="form-group col-md-3">
					<label>Pago <span class="icon icon-pencil ColorRequired"></span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<span>$</span>
						</div>
						<input type="number" class="form-control txt-pago-ahorrador" value="0" min="0.1" max="{{$credito->monto_total - $credito->total_abonado}}" step="0.01" name="abono" required>
						<div class="input-group-btn">
							<button type="submit" class="btn BtnGreen">Cobrar</button>
						</div>
					</div>
				</div>
			</form>
			<table class="table">
				<thead>
					<th class="subtitle text-center" colspan="4">Pagos Registrados</th>
				</thead>
			</table>
			<table class="table table-hover table-bordered" style="width:65%;margin-left:15%;">
				<thead>
					<th class="text-center">Folio</th>
					<th class="text-center">No. Pago</th>
					<th class="text-center">Fecha</th>
					<th>Abono</th>
				</thead>
				<tbody>
					@if(count($credito->abonos_ahorrador) < 1)
						<tr>
							<th colspan="4" class="text-uppercase text-center">no se a realizado ningún pago</th>
						</tr>
					@endif
					@foreach($credito->abonos_ahorrador as $pago)
						<tr>
							<th class="text-center">{{$pago->folio}}</th>
							<th class="text-center">{{$pago->no_pago}}</th>
							<td class="text-center">{{$pago->fecha}}</td>
							<td>${{number_format($pago->abono,2)}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>	
	@endif
	<!-- CONGELACION POR CONVENIO -->
	@if($tipo_credito == "Credito" && $credito->estatus == "CONGELADA CONVENIO")
		<?php 
			$convenio = \DB::table('seguimiento')->join('situacion','situacion.id_situacion','=','seguimiento.situacion_id')->select('seguimiento.*','situacion.situacion')->where('seguimiento.credito_clave',$credito->clave_credito)->where('seguimiento.restriccion','CONVENIO')->where('seguimiento.estatus_seguimiento','1')->first();
			$abonado = \DB::table('abono_convenio')->where('seguimiento_id',$convenio->id_seguimiento)->sum('abono');
			$abonado = ($abonado === null) ? 0 : $abonado;
			$pagos   = \DB::table('abono_convenio')->where('seguimiento_id',$convenio->id_seguimiento)->get();
		?>
		<!-- INFORMACION CLIENTE -->
		<table class="table table-hover" >
			<tbody>
				<tr>
					<td class="subtitle text-center" colspan="6"><b>Datos Cliente</b></td>
				</tr>
				<tr>
					<td><b>Clave Cliente:</b></td>
					<td>{{str_pad($credito->demanda->cliente->id_cliente, 4, "0", STR_PAD_LEFT)}}</td>
					<td><b>Cliente:</b></td>
					<td>{{$credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno}}</td>
					<td><b>CURP:</b></td>
					<td>{{$credito->demanda->cliente->curp}}</td>
				</tr>
				<tr>
					<td><b>Crédito:</b></td>
					<td>{{$credito->clave_credito}}</td>
					<td><b>Situación:</b></td>
					<td>{{$convenio->situacion}}</td>
					<td><b>Restricción:</b></td>
					<td>{{$convenio->restriccion}}</td>
				</tr>
				<tr>
					<td><b>Descripción:</b></td>
					<td colspan="5">{{$convenio->descripcion_seguimiento}}</td>
				</tr>
				@if(!$convenio->convenio_pagado)
					<tr>
						<td class="subtitle text-center" colspan="6"><b>Registrar Pago</b></td>
					</tr>
				@endif
			</tbody>
		</table>

		@if(!$convenio->convenio_pagado)
			<form action="{{route('PagoConvenio',$convenio->id_seguimiento)}}" method="POST">
				{{ csrf_field() }}
				<div class="row">

					<div class="col-md-4 text-center" style="margin-top:25px;">
						<div class="input-group">
							<div class="input-group-addon"><b>Convenio $</b></div>
						  	<input type="hidden" class="pago_convenio" name="pago_convenio" value="{{$convenio->monto_convenio}}">
						  	<input type="text" class="form-control readonly" value="{{number_format($convenio->monto_convenio,2)}}">
						</div>
					</div>

					<div class="col-md-4 text-center" style="margin-top:25px;">
						<div class="input-group">
							<div class="input-group-addon"><b>Abonado $</b></div>
						  	<input type="hidden" class="abonado_convenio" name="abonado_convenio" value="{{$abonado}}">
						  	<input type="text" class="form-control readonly" value="{{number_format($abonado,2)}}">
						</div>
					</div>

					<div class="col-md-4 text-center" style="margin-top:25px;">
						<div class="input-group">
							<div class="input-group-addon"><b>Pago $</b></div>
						  	<input type="number" class="txt-pago-convenio form-control" min="0.1" step="0.01" name="pago" value="0.00" max="{{$convenio->monto_convenio - $abonado}}">
						  	<div class="input-group-btn">
								<button type="submit" class="btn BtnGreen">Cobrar</button>
						   	</div>
						</div>
					</div>
				</div>
			</form>
		@else
			<blockquote>
				<p class="text-center text-uppercase"><b>ya se cubrió el convenio de $<?php echo number_format($convenio->monto_convenio,2)?> <br> favor de pasar al departamento de cobranza para remover el convenio.</b></p>
			</blockquote>
		@endif
		<table class="table" style="margin-top:25px;">
			<thead>
				<tr>
					<th class="subtitle text-center" colspan="4">Pagos Registrados</th>
				</tr>
			</thead>
		</table>
		<table class="table table-hover table-bordered" style="width:65%;margin-left:18%;">
			<thead>
				<th class="text-center">Folio</th>
				<th class="text-center">No. Pago</th>
				<th class="text-center">Fecha</th>
				<th>Abono</th>
			</thead>
			<tbody>
				@if($abonado == 0)
					<tr>
						<th colspan="4" class="text-uppercase text-center">no se a realizado ningún pago al convenio</th>
					</tr>
				@endif
				@foreach($pagos as $pago)
					<tr>
						<th class="text-center">{{$pago->folio}}</th>
						<th class="text-center">{{$pago->no_pago}}</th>
						<th class="text-center">{{$pago->fecha}}</th>
						<td>${{number_format($pago->abono,2)}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
	<!-- CONGELACION -->
	@if($tipo_credito == "Credito" && $credito->estatus == "CONGELADA")
		<?php 
			$convenio = \DB::table('seguimiento')->join('situacion','situacion.id_situacion','=','seguimiento.situacion_id')->select('seguimiento.*','situacion.situacion')->where('seguimiento.credito_clave',$credito->clave_credito)->where('seguimiento.restriccion','CONGELACION')->where('seguimiento.estatus_seguimiento','1')->first();
		?>
		<div>
			<!-- INFORMACION CLIENTE -->
			<table class="table table-hover" >
				<tbody>
					<tr>
						<td class="subtitle text-center" colspan="6"><b>Datos Cliente</b></td>
					</tr>
					<tr>
						<td><b>Clave Cliente:</b></td>
						<td>{{str_pad($credito->demanda->cliente->id_cliente, 4, "0", STR_PAD_LEFT)}}</td>
						<td><b>Cliente:</b></td>
						<td>{{$credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno}}</td>
						<td><b>CURP:</b></td>
						<td>{{$credito->demanda->cliente->curp}}</td>
					</tr>
					<tr>
						<td><b>Crédito:</b></td>
						<td>{{$credito->clave_credito}}</td>
						<td><b>Situación:</b></td>
						<td>{{$convenio->situacion}}</td>
						<td><b>Restricción:</b></td>
						<td>{{$convenio->restriccion}}</td>
					</tr>
					<tr>
						<td><b>Descripción:</b></td>
						<td colspan="5">{{$convenio->descripcion_seguimiento}}</td>
					</tr>
				</tbody>
			</table>
			<blockquote>
				<p class="text-center text-uppercase"><b>Este crédito se encuentra congelado.</b></p>
			</blockquote>

		</div>
	@endif
@stop

@section('js')
	<script src="{{asset('js/caja.js')}}"></script>
@stop