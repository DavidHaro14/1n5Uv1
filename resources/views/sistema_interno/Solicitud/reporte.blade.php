@extends('sistema_interno.template.cuerpo')

@section('titulo','Solicitudes')

@section('contenido')
	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('reporte_solicitudes')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search">
			   			<option value="usuario">Usuario Solicitante</option>
			   			<option value="responsable">Usuario Responsable</option>
			   		</select>
			   	</div>
				<b><input type="text" class="form-control" name="buscar"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	<br>
	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover">
				<thead class="BtnGray">
					<th class="text-center">#</th>
					<th>Crédito</th>
					<th>Descripción</th>
					<th>Solicitante</th>
					<th>Responsable</th>
					<th>Aprobación</th>
					<th>Fecha Solicitud</th>
					<th>Fecha Aprobación</th>
				</thead>
				<tbody>
					@if(count($solicitudes) < 1)
						<tr>
							<td colspan="8">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($solicitudes as $solicitud)
						<tr>
							<th class="text-center">{{ str_pad($solicitud->id_solicitud, 2, "0", STR_PAD_LEFT)}}</th>
							<td>{{$solicitud->credito}}</td>
							<td>{{$solicitud->descripcion}}</td>
							<td>{{$solicitud->usuario}}</td>
							<td>{{$solicitud->responsable}}</td>
							<td>{{$solicitud->status}}</td>
							<td>{{$solicitud->created_at}}</td>
							<td>{{$solicitud->updated_at}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop