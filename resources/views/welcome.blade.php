@extends('principal.template.cuerpo')

@section('titulo','INSUVI')

@section('contenido')

<div class="row" id="body-pag">
	<img src="{{ asset('img/banner_principal.jpg') }}" alt="banner_principal">
	<div class="padding-content">
		
		<p class="text-justify">El Instituto de Vivienda del Estado de Colima, vino a sustituir a la Inmobiliaria del Estado, ya que esta última había sido rebasada su capacidad de respuesta, ante los requerimientos sociales en materia de vivienda.</p>
		<p class="text-justify">El día 11 de abril de 1992, en su publicación número 15 del Periódico Oficial del Gobierno Constitucional del Estado de Colima, se publico el Decreto numero 44, que contiene la ley del Instituto de Vivienda del Estado de Colima, la cual se integra de 28 artículos, distribuidos en 7 capítulos y 5 artículos transitorios</p>
		<h3 class="text-center"><b>OBJETIVOS</b></h3>
		<p class="text-justify">Promover y realizar la construcción de viviendas y fraccionamientos, preferentemente de interés social, así como coadyuvar en la ejecución de planes para la obtención de créditos para la realización de acciones de vivienda.</p>

		<h3 class="text-center"><b>OBJETIVOS ESPECIFICO</b></h3>
		<p class="text-justify">1.-Promover y realizar la construcción de viviendas y Fraccionamientos, preferentemente de interés social.</p>

		<p class="text-justify">2.-Promover y ejecutar programas de regularización de la tenencia de la tierra y de reubicación de asentamientos humanos irregulares.</p>

		<p class="text-justify">3.- Gestionar y obtener créditos para la realización de acciones de vivienda.</p>
		<hr>
		<h2 class="text-center"><b>¿YA ERES CLIENTE CON NOSOTROS?</b></h2>
		<p class="text-justify">Si lo eres, tienes la oportunidad de checar tus movimientos de pagos y recibir notificaciones de nuevos programas que tenemos para ti, solo tienes que iniciar sesión presionando el siguiente botón:</p>
		<div class="text-center"><a href="{{ route('pre-registro') }}" class="btn btn-default"><span class="icon icon-enter"></span> Iniciar Sesión</a></div>
	</div>

	<nav class="navbar" id="nav-footer">
		<ul class="nav navbar-nav">
			<li><a href=""><img src="{{ asset('img/canadevi.png') }}" alt="" class="img-asociacion"></a></li>
			<li><a href=""><img src="{{ asset('img/cofinavit.jpg') }}" alt="" class="img-asociacion"></a></li>
			<li><a href=""><img src="{{ asset('img/conavi.png') }}" alt="" class="img-asociacion"></a></li>
			<li><a href=""><img src="{{ asset('img/fovissste.png') }}" alt="" class="img-asociacion"></a></li>
			<li><a href=""><img src="{{ asset('img/infonavit.png') }}" alt="" class="img-asociacion"></a></li>
			<li><a href=""><img src="{{ asset('img/conorevi.png') }}" alt="" class="img-asociacion"></a></li>
		</ul>	
		<br><br><br><br><br>
		<p class="text-center" id="size-footer"><b>INSTITUTO DE SUELO, UBRANIZACIÓN Y VIVIENDA DEL ESTADO DE COLIMA, IGNACIO ALDAMA # 552, PISO 1, CENTRO, COLIMA, COLIMA</b> </p>
	</nav>
</div>
    	
@stop
        