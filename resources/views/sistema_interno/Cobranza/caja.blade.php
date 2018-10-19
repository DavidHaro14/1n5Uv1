@extends('sistema_interno.template.cuerpo')

@section('titulo','Buscar Credito')

@section('contenido')

@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('caja',['descuentos'=>$descuentos,'busqueda'=>true])}}" method="GET">
			<div class="input-group">
			   	<div class="input-group-btn">
					<select name="tipo" class="btn btn-default size-search" id="tipo">
						<option value="credito"><b>Clave Credito</b></option>
						<option value="cliente"><b>Clave Cliente</b></option>
						<option value="curp"><b>CURP</b></option>
						<option value="nombre"><b>Nombre</b></option>
					</select>
			   	</div>
			  	<b><input type="text" class="form-control" name="buscar" id="dato"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	<br>
	
	@if($busqueda)
		<div class="row">
			<div class="panel panel-default">
				<table class="table table-hover">
					<thead class="BtnGray">
						<tr>
							<th class="text-center">Clave Cliente</th>
							<th>Tipo Programa</th>
							<th>Nombre</th>
							<th>Credito</th>
							<th>Estatus</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
						@if(count($creditos) < 1 && count($ahorradores) < 1)
							<tr>
								<td colspan="6" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
							</tr>
						@endif
						@if(count($ahorradores) > 0 && $descuentos && count($creditos) < 1)
							<tr>
								<td colspan="6" class="text-center">NO SE APLICAN DESCUENTOS A LOS AHORRADORES</td>
							</tr>
						@endif
						@if(!$descuentos)
							@foreach($ahorradores as $aho)
								<tr>
									<th class="text-center"><?php echo str_pad($aho->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
									<td>{{ $aho->programa }}</td>
									<td>{{ $aho->nombre }} {{ $aho->ape_paterno }} {{ $aho->ape_materno }}</td>
									<!-- <td>{{ $aho->curp }}</td> -->
									<td>{{ $aho->clave_ahorrador }}</td>
									<td>PAGANDOLA</td>
									<td>
										<a href="{{route('pagos_credito',['credito'=>$aho->clave_ahorrador,'tipo'=>'Ahorrador'])}}" class="btn BtnOrange btn-xs"><span class="icon icon-plus"></span> Cobrar</a>
									</td>
								</tr>
							@endforeach
						@endif
						@foreach($creditos as $cr)
							<tr>
								<th class="text-center"><?php echo str_pad($cr->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
								<td>{{ $cr->programa }}</td>
								<td>{{ $cr->nombre }} {{ $cr->ape_paterno }} {{ $cr->ape_materno }}</td>
								<!-- <td>{{ $cr->curp }}</td> -->
								<td>{{ $cr->clave_credito }}</td>
								<td>{{ $cr->estatus }}</td>
								@if(!$descuentos)
									<td>
										<a href="{{route('pagos_credito',['credito'=>$cr->clave_credito,'tipo'=>'Credito'])}}" class="btn BtnOrange btn-xs"><span class="icon icon-plus"></span> Cobrar</a>
									</td>
								@else
									<?php 
										$restriccion = \DB::table('seguimiento')->select('*')->where('restriccion','NO DESCUENTO')->where('estatus_seguimiento','1')->where('credito_clave',$cr->clave_credito)->count();
									?>
									<td>
										@if($restriccion > 0)
											<button class="btn BtnOrange btn-xs" data-container="body" data-content="Este credito tiene seguimiento de 'No Descuento'" data-toggle="popover" data-placement="top"><span class="icon icon-ticket"></span> Descuento</button>
											<script>
												$(function(){
													$('[data-toggle="popover"]').popover();
												});
											</script>
										@else
											<a href="{{route('descuentos_credito',$cr->clave_credito)}}" class="btn BtnOrange btn-xs"><span class="icon icon-ticket"></span> Descuento</a>
										@endif
									</td>
								@endif
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endif

@stop

@section('js')
	<script>
		$(document).ready(function(){
			$('#tipo').change(function(){
				var type = $(this).val();
				if(type == "curp"){
					$('#dato').attr('maxlength','18').val("");
				} else {
					$('#dato').attr('maxlength', false).val("");
				}	
			});
		});
	</script>
@stop