@extends('sistema_interno.template.cuerpo')

@section('titulo','Continuar?')

@section('contenido')
	<div class="row text-center">
		<div class="row">
			<div class="col-md-12 text-center">
				<h3 class="BtnGray" style="padding:8px;">¿Desea continuar con el Estudio Socioeconomico?</h3>
			</div>
		</div>
		<br>
		<a href="{{ route('insuvi') }}" class="btn BtnRed">Ahora no</a>
		<a href="{{ route('socioeconomico', $solicitante->id_cliente) }}" class="btn BtnGreen">Continuar</a>
	</div>
@stop