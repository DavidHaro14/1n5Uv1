@extends('principal.template.cuerpo')

@section('titulo','Clave Solicitante')

@section('contenido')
	
	<div class="row">
		<div class="text-center">
			<h3>CLAVE SOLICITUD</h3>
			<h1><b>{{$solicitante->clave_registro}}</b></h1>
			<p>Tiene un plazo de una semana para ir a las instalaciones de INSUVI para completar el registro</p>
		</div>
	</div>

	<div class="row">
		<div class="text-center">
			<a href="{{route('home')}}" class="btn btn-primary btn-lg">OK</a>
		</div>
	</div>
@stop