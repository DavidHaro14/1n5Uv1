@extends('sistema_interno.template.cuerpo')

@section('titulo','Información Cliente')

@section('contenido')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('view_cliente')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
			   			<option value="domicilio">Domicilio</option>
			   		</select>
			   	</div>
				<b><input type="text" class="form-control" name="buscar" maxlength="18" id="dato"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	<br>
	<div class="row">
		<p><b>Registrado por: {{$cliente->creado}} - {{$cliente->created_at}}</b></p>
	</div>
	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover">
				<tbody>
					<tr>
						<td colspan="6" class="subtitle text-center"><b>Datos Generales del Cliente</b></td>
					</tr>
					<tr>
						<td><b>Nombre:</b></td>
						<td>{{$cliente->nombre . " " . $cliente->ape_materno . " " . $cliente->ape_paterno}}</td>
						<td><b>CURP:</b></td>
						<td>{{$cliente->curp}}</td>
						<td><b>Fecha Nacimiento:</b></td>
						<td>{{$cliente->fecha_nac}}</td>
					</tr>
					<tr>
						<td><b>Genero:</b></td>
						<td>{{$cliente->genero}}</td>
						<td><b>Correo:</b></td>
						<td>{{$cliente->correo or "Sin Registro"}}</td>
						<td><b>Estado Civil:</b></td>
						<td>{{$cliente->estado_civil}}</td>
					</tr>
					<tr>
						<td><b>Escolaridad:</b></td>
						<td>{{$cliente->escolaridad}}</td>
						<td><b>Teléfono:</b></td>
						<td>{{$cliente->telefono or "Sin Registro"}}</td>
						<td><b>Estado Nacimiento:</b></td>
						<td>{{$cliente->estado_nac}}</td>
					</tr>
					<tr>
						<td><b>No. Dependientes:</b></td>
						<td>{{$cliente->dependientes}}</td>
						<td><b>Grupo Social:</b></td>
						<td>{{$cliente->grupo_social}}</td>
						<td><b>Lugar Nacimiento:</b></td>
						<td>{{$cliente->lugar_nac}}</td>
					</tr>
					<tr>
						<td><b>Grupo Atención:</b></td>
						<td>{{$cliente->grupo_atencion->nombre}}</td>
						<td><b>Ocupación:</b></td>
						<td colspan="3">{{$cliente->ocupacion->nombre}}</td>
					</tr>
					<tr>
						<td><b>Domicilio:</b></td>
						<td colspan="3">
							{{
								$cliente->calle->nombre . " #" . $cliente->num_ext . $cliente->num_int . ", " .
								$cliente->calle->colonia->nombre . ", " . $cliente->calle->colonia->localidad->nombre
							}}
						</td>
						<td><b>Código Postal:</b></td>
						<td>{{$cliente->codigo_postal}}</td>
					</tr>
					<tr>
						<td><b>Referencias:</b></td>
						<td colspan="5">{{$cliente->referencia_calles}}</td>
					</tr>
					<tr>
						<td><b>Descripción Ubicación:</b></td>
						<td colspan="5">{{$cliente->descripcion_ubicacion}}</td>
					</tr>
					@if($cliente->conyuge)
						<tr>
							<td colspan="6" class="subtitle text-center"><b>Datos Generales del Cónyuge</b></td>
						</tr>
						<tr>
							<td><b>Nombre:</b></td>
							<td>{{$cliente->conyuge->nombre . " " . $cliente->conyuge->ape_materno . " " . $cliente->conyuge->ape_paterno}}</td>
							<td><b>CURP:</b></td>
							<td>{{$cliente->conyuge->curp}}</td>
							<td><b>Fecha Nacimiento:</b></td>
							<td>{{$cliente->conyuge->fecha_nac}}</td>
						</tr>
						<tr>
							<td><b>Bienes:</b></td>
							<td>{{$cliente->conyuge->bienes}}</td>
							<td><b>Lugar Nacimiento:</b></td>
							<td colspan="3">{{$cliente->conyuge->lugar_nac}}</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 text-center">
			<a href="{{route('insuvi')}}" class="btn BtnRed"><span class="icon icon-reply"></span>Salir</a>
		</div>
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