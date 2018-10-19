@extends('sistema_interno.template.cuerpo')

@section('titulo','Estudio Socioeconomico')

@section('contenido')

@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('estudio_index')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
			   		</select>
			   	</div>
				<b><input type="text" class="form-control" name="buscar" maxlength="18" id="dato"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	<br>

	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover">
				<thead class="BtnGray">
					<tr>
						<th class="text-center">Clave Cliente</th>
						<th>Nombre</th>
						<th>CURP</th>
						<th>Domicilio</th>
						<!-- <th>Estado Civil</th>
						<th>Ocupación</th> -->
						<th class="text-center">Estudio</th>
					</tr>
				</thead>
				<tbody>
					@if(count($clientes) < 1)
						<tr>
							<td colspan="6" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($clientes as $cl)
					<tr>
						<th class="text-center"><?php echo str_pad($cl->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
						<td>{{ $cl->nombre }} {{ $cl->ape_paterno }} {{ $cl->ape_materno }}</td>
						<td>{{ $cl->curp }}</td>
						<td>
							<?php 
								$domicilio = $cl->calle->nombre . ", " . $cl->calle->colonia->nombre. " #" .$cl->num_ext;
								if ($cl->num_int != "0") {
									$domicilio .= $cl->num_int;
								}
								echo $domicilio; 
							?>
						</td>
						<!-- <td>{{ $cl->estado_civil }}</td>
						<td>{{ $cl->ocupacion->nombre }}</td> -->
						<td class="text-center"><a href="{{route('socioeconomico',$cl->id_cliente)}}" class="btn btn-xs BtnOrange"><span class="icon icon-play3"></span> Iniciar</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		{{ $clientes->render() }}
	</div>

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