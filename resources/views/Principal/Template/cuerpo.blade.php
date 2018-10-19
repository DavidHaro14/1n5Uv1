<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<title>@yield('titulo')</title>

	<link rel="stylesheet" href="{{ asset('bootstrap/bootstrap-theme.min.css') }}">
	<link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
	<link rel="stylesheet" href="{{ asset('css/principal.css') }}">
	<link rel="stylesheet" href="{{ asset('fonts/style.css') }}">
	<script src="{{ asset('jquery/jquery.js') }}"></script>
	<script src="{{ asset('bootstrap/bootstrap.min.js') }}"></script>

	@yield('head')
</head>
<body>
	<div class="container">
		
		<!-- Menu Top -->
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="{{route('home')}}">
		        <span class="icon icon-home"></span> INSUVI
		      </a>
		    </div>
		    <ul class="nav navbar-nav text-center">
       	 		<li class="li-insuvi"><a href="#"> ¿Que es INSUVI?</a></li>
       	 		<li class="li-transparencia"><a href="#"> Transparencia</a></li>
       	 		<li class="li-directorio"><a href="#"> Directorio</a></li>
       	 		<li class="li-programas"><a href="/InsuviProgramas"> Programas</a></li>
       	 		<li class="li-consultas"><a href="#"> Consulta</a></li>
       	 	</ul>
       	 	<ul class="nav navbar-nav navbar-right">
       	 		<li><a href="{{ route('pre-registro') }}"><span class="icon icon-enter"></span> Sesión</a></li>
       	 	</ul>
		  </div>
		</nav>
				
		@yield('contenido')
	</div>

	@yield('js')
</body>
</html>