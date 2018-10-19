@extends('sistema_interno.template.cuerpo')

@section('titulo','Catálogos')

@section('contenido')
	<div class="row">
		<h1 class="text-center"><b>Catálogos</b></h1>
		<div class="btn-menu-margin">
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('estado_index')}}">
					<img src="{{ asset('img/zona.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Estados</div>
			</div>	
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('municipio_index')}}">
					<img src="{{ asset('img/zona.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Municipios</div>
			</div>	
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('localidad_index')}}">
					<img src="{{ asset('img/zona.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Localidades</div>
			</div>	
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('colonia_index')}}">
					<img src="{{ asset('img/zona.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Colonias/Fraccionamientos</div>
			</div>	
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('calle_index')}}">
					<img src="{{ asset('img/zona.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Calles</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('programa_index')}}">
					<img src="{{ asset('img/prog.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Programas</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('tiprog_index')}}">
					<img src="{{ asset('img/tipos.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Tipos de Programas</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('documento_index')}}">
					<img src="{{ asset('img/documentos.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Documentos</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('empleado_index')}}">
					<img src="{{ asset('img/perfiles.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Personal Insuvi</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('catalogo_caja')}}">
					<img src="{{ asset('img//cajaimg.jpg') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Asignación Caja</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('ocupacion_index')}}">
					<img src="{{ asset('img/ocupacion.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Ocupación</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('techo_index')}}">
					<img src="{{ asset('img/techos.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Techos</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('piso_index')}}">
					<img src="{{ asset('img/pisos.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Pisos</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('muro_index')}}">
					<img src="{{ asset('img/muros.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Muros</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('grupo_index')}}">
					<img src="{{ asset('img/grupoatencion.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Grupo de Atención</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('regimen_index')}}">
					<img src="{{ asset('img/regimen.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Régimen de Propiedad</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('mejoramiento_index')}}">
					<img src="{{ asset('img/mejoramiento.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Mejoramientos</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('subsidio_index')}}">
					<img src="{{ asset('img/subsidio.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Subsidios</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('lote_index')}}">
					<img src="{{ asset('img/lotes.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Lotes</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('banco_index')}}">
					<img src="{{ asset('img/bancos.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Bancos</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('contrato_index')}}">
					<img src="{{ asset('img/contratos.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Contratos</div>
			</div>
			<div class="col-md-4 col-lg-3 text-center">
				<a href="{{route('situacion_index')}}">
					<img src="{{ asset('img/situacion.png') }}" class="imgcatalogos">
				</a>
				<div class="cont-btn">Situaciones</div>
			</div>
		</div>

	</div>		
@stop