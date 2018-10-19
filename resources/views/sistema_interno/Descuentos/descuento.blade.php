@extends('sistema_interno.template.cuerpo')

@section('titulo','Descuentos')

@section('head')
	<link rel="stylesheet" href="{{ asset('datetimepicker/css/bootstrap-datepicker.min.css') }}">
@stop

@section('contenido')
	<!-- DESCUENTOS CREDITO -->
	<form action="{{route('AplicarDescuento',$credito->clave_credito)}}" method="POST">
		<div class="row">
			<div class="col-md-6 text-center">
				<a href="{{route('caja',1)}}" class="btn BtnRed"><span class="icon icon-reply"></span> Cancelar</a>
			</div>
			<div class="col-md-6 text-center">
				<button type="submit" class="btn BtnGreen"><span class="icon icon-ticket"></span> Aplicar Descuento</button>
			</div>
		</div>
		<br>
		{{csrf_field()}}
		<!-- INFORMACION CLIENTE -->
		<table class="table table-hover">
			<tbody>
				<tr>
					<th colspan="4" class="subtitle text-center">Datos Cliente</th>
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
					<th colspan="4" class="subtitle text-center">Datos Descuento</th>
				</tr>
			</tbody>
		</table>
		<!-- DATOS PARA DESCUENTO -->
		<div class="row text-center">
			<div class="col-md-3">
				<label>Amortización</label>
				<p id="txt-capital">$0.00</p>
			</div>
			<div class="col-md-3">
				<label>Interés Financiero</label>
				<p id="txt-interes">$0.00</p>
			</div>
			<div class="col-md-3">
				<label>Interés Moratorio</label>
				<p id="txt-moratorio">$0.00</p>
			</div>
			<div class="col-md-3">
				<label>Total Descuento</label>
				<p id="txt-total-descuento">$0.00</p>
			</div>
		</div>
		<div class="row text-center">
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon">Descuento</span>
					<input type="number" step="0.01" min="0" class="form-control" placeholder="Amortización" name="capital" id="input_capital" required>
				</div>
			</div>
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon">Descuento</span>
					<input type="number" step="0.01" min="0" class="form-control" placeholder="Interés Financiero" name="interes" id="input_interes" required>
				</div>
			</div>
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon">Descuento</span>
					<input type="number" step="0.01" min="0" class="form-control" placeholder="Interés Moratorio" name="moratorio" id="input_moratorio" required>
				</div>
			</div>
			<div class="col-md-2" style="margin-top:5px;">
				<label>Total A Pagar</label>
			</div>
			<div class="col-md-1 text-left" style="margin-top:5px;">
				<p id="txt-total-pagar">$0.00</p>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-9">
				<!-- <label>Observaciones</label> --> <br>
				<div class="input-group">
					<span class="input-group-addon"><span class="icon icon-eye"></span></span>
					<textarea name="observaciones" rows="2" class="form-control" placeholder="Observaciones" required></textarea>
				</div>
			</div>
			<div class="form-group col-md-3" id="picker-container">
				<!-- <label>Vigencia</label> --> <br>
				<div class="input-group">
					<span class="input-group-addon"><span class="icon icon-calendar"></span></span>
					<input type="text" class="form-control readonly vigencia" placeholder="Vigencia" name="vigencia" required>
				</div>
			</div>
		</div>
		<!-- TABLA PAGOS -->
		<div class="row" style="margin-top:20px;">
			<table class="table table-hover">
				<thead>
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
					<?php 
						$cont = true;  //Traer solo el primer registro de la iteracion foreach
					?>
					@foreach($credito->mensualidades as $index=>$mensualidad)
						@if($mensualidad->estatus != "PAGADO")
							<tr class="text-center" <?php if(count($mensualidad->abonos_credito) > 0){echo 'data-toggle="modal" data-target="#pagos_credito-'.$mensualidad->no_mensualidad.'" style="background-color:rgba(243,134,48,0.7);font-weight:bold;"';} ?>>
								<?php if($cont){ ?>
									<input type="hidden" id="abonado" value="{{$mensualidad->abonos_credito->sum('abono')}}">
									<input type="hidden" id="moratorios_abonados" value="{{$mensualidad->abonos_credito->sum('moratorio')}}">
								<?php } ?>
								<td>
									<?php 
										$disponibles = false;
										foreach ($descuentos as $descuento) {
											if ($descuento == $mensualidad->no_mensualidad) {
												$disponibles = true;
											}
										} 
									?>
									<input type="checkbox" value="{{$mensualidad->no_mensualidad}}" name="pagos[]"<?php 
										if($cont){
											echo ' required'; 
											$cont = false; 
										} 
										if($disponibles){
											echo ' disabled'; 
											$cont = true; 
										} 
									?>>
								</td>
								<td>{{ $mensualidad->no_mensualidad }}</td>
								<td>{{ $mensualidad->fecha_vencimiento }}</td>
								<td>${{ number_format($credito->pago_mensual,2) }}</td>
								<td>${{ number_format($mensualidad->saldo,2) }}</td>
								<td>${{ number_format($mensualidad->capital,2) }}</td>
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
						            
									<input type="hidden" name="moratorios[]" class="h_moratorio" value="{{$total_moratorios}}" <?php if($disponibles) echo "disabled"; ?>>
									<input type="hidden" name="PlazoCapital[]" class="h_capital" value="{{$mensualidad->capital}}" <?php if($disponibles) echo "disabled"; ?>>
									<input type="hidden" name="PlazoInteres[]" class="h_interes" value="{{$mensualidad->interes}}" <?php if($disponibles) echo "disabled"; ?>>
								</td>
								<td style="color: <?php if($mensualidad->estatus == 'VENCIDO' && count($mensualidad->abonos_credito) < 1) echo 'red' ?>"><b>{{ $mensualidad->estatus }}</b></td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</form>
@stop

@section('js')
	<script src="{{ asset('datetimepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('datetimepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
	<script src="{{asset('js/descuentos.js')}}"></script>
	<script>
		var date = new Date();

      	$('.vigencia').datepicker({
		    format: 'yyyy-mm-dd',
		    autoclose:true,
		    language:'es',
		    startDate: date,
		    clearBtn: true,
		    container: "#picker-container",
		    title:"Vigencia"
		});
	</script>
@stop