@extends('sistema_interno.template.cuerpo')

@section('titulo','Modificación Crédito')

@section('contenido')

@include('errors.errores')

	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover">
				<thead class="BtnGray">
					<tr>
						<th>Fecha</th>
						<!-- <th>Programa</th> -->
						<th>Tipo Programa</th>
						<th>CURP</th>
						<th>Cliente</th>
						<th>Crédito</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@if(count($creditos) < 1)
						<tr>
							<td colspan="6" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($creditos as $credito)
					<tr>
						<td>{{$credito->created_at}}</td>
						<!-- <td>{{$credito->programa}}</td> -->
						<td>{{ $credito->tipo }}</td>
						<td>{{ $credito->curp }}</td>
						<td>{{ $credito->nombre }} {{ $credito->ape_paterno }} {{ $credito->ape_materno }}</td>
						<td>{{ $credito->clave_credito }}</td>
						<td>
							<a href="{{route('credito',['id'=>$credito->demanda_id,'credito'=>$credito->clave_credito])}}" class="btn btn-xs BtnOrange"><span class="icon icon-pencil"></span> Modificar</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@stop
