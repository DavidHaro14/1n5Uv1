<!-- Plantilla del sistema interno -->
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="UTF-8">

	<title>@yield('titulo')</title>
    <!-- Documentacion datepicker https://bootstrap-datepicker.readthedocs.io/en/latest/options.html#orientation -->
	<link rel="stylesheet" href="{{ asset('bootstrap/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('chosen/chosen.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
	<link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
	<link rel="stylesheet" href="{{ asset('fonts/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/minilogo.png') }}">
    <!-- <script>if(window.history.forward(1) != null) window.history.forward(1)</script> -->
    <script src="{{ asset('jquery/jquery.js') }}"></script>
	<script src="{{ asset('chosen/chosen.jquery.min.js') }} "></script>
    <script src="{{ asset('bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/cuerpo.js') }} "></script>

    @yield('head')

</head>
<body>
    <?php  
        $v_modulos    = Auth::user()->modulos;
        $admon        = str_contains($v_modulos,'ADMON');
        $solicitud    = str_contains($v_modulos,'SOLICITUD');
        $estudio      = str_contains($v_modulos,'ESTUDIO');
        $demanda      = str_contains($v_modulos,'DEMANDA');
        $contratacion = str_contains($v_modulos,'CONTRATACION');
        $caja         = str_contains($v_modulos,'CAJA');
        $enganche     = str_contains($v_modulos,'ENGANCHE');
        $domicilio    = str_contains($v_modulos,'DOMICILIO');
        $cancelacion  = str_contains($v_modulos,'CANCELACION');
        $seguimiento  = str_contains($v_modulos,'SEGUIMIENTO');
        $reestructura = str_contains($v_modulos,'REESTRUCTURA');
        $saiv         = str_contains($v_modulos,'SAIV');
        $cesion       = str_contains($v_modulos,'CESION');
        $descuento    = str_contains($v_modulos,'DESCUENTO');
    ?>

    @include('sistema_interno.template.menu-top')<!-- Menu superior-->

    <div id="wrapper" class="toggled margin-lateral"><!-- Menu lateral-->
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand text-center">
                    <a href="{{route('insuvi')}}">
                        <img src="{{ asset('img/logo-inicio.png') }}" class="logoinsuvi">
                    </a>
                </li>
                @if($admon)
                    <li>
                        <a href="{{route('catalogos')}}"><span class="icon-database"></span> Catálogos</a>
                    </li>
                @endif
                @if($admon || $solicitud)
                    <li>
                        <a href="{{route('solicitudes_formulario')}}"><span class="icon-man"></span> Atención Solicitud</a>
                    </li>
                @endif
                @if(!$caja)
                    <li>
                        <a href="{{route('view_cliente')}}"><span class="icon-users"></span> Clientes</a>
                    </li>
                @endif
                @if($admon || $estudio)
                    <li>
                        <a href="{{route('estudio_index')}}"><span class="icon-man-woman"></span> Estudio Socioeconómico</a>
                    </li>
                @endif
                @if($admon || $demanda)
                    <li>
                        <a href="{{route('demanda_index')}}"><span class="icon-bubbles2"></span> Atención Demanda</a>
                    </li>
                    <li>
                        <a href="{{route('view_demandas')}}"><span class="icon-cogs"></span> Modificación Demanda</a>
                    </li>
                @endif
                @if($admon || $contratacion)
                    <li>
                        <a href="{{route('credito_index')}}"><span class="icon-coin-dollar"></span> Contratación</a>
                    </li>
                    <li>
                        <a href="{{route('creditos_virgen')}}"><span class="icon-cogs"></span> Modificación Crédito</a>
                    </li>
                @endif
                @if($admon || $caja)
                    <li>
                        <a href="{{route('caja')}}"><span class="icon-credit-card"></span> Caja</a>
                    </li>
                @endif
                @if($admon || $enganche)
                    <li>
                        <a href="{{route('demanda_enganche')}}"><span class="icon-cogs"></span> Modificación Enganche</a>
                    </li>
                @endif
                @if($admon || $domicilio)
                    <li>
                        <a href="{{route('cliente_view_update')}}"><span class="icon-location"></span> Cambio Domicilio</a>
                    </li>
                @endif
                @if($admon || $cancelacion)
                    <li>
                        <a href="{{route('cancelacion_index')}}"><span class="icon-arrow-down"></span> Cancelación</a>
                    </li>
                @endif
                @if($admon || $seguimiento)
                    <li>
                        <a href="{{route('seguimiento')}}"><span class="icon-history"></span> Seguimientos</a>
                    </li>
                @endif
                @if($admon || $reestructura)
                    <li>
                        <a href="{{route('reestructura_index')}}"><span class="icon-cog"></span> Reestructura</a>
                    </li>
                    <li>
                        <a href="{{route('solicitud_eliminacion')}}"><span class="icon-mail4"></span> Solicitud Eliminación</a>
                    </li>
                @endif
                @if($admon || $saiv)
                    <li>
                        <a href="{{route('reestructura_saiv')}}"><span class="icon-cog"></span> Reestructura SAIV</a>
                    </li>
                @endif
                @if($admon || $cesion)
                    <li>
                        <a href="{{route('cesion_derecho')}}"><span class="icon-share"></span> Cesión Derecho</a>
                    </li>
                @endif
                @if($admon || $descuento)
                    <li>
                        <a href="{{route('caja',1)}}"><span class="icon-ticket"></span> Descuentos</a>
                    </li>
                @endif
            </ul>
        </div><!-- /#sidebar-wrapper -->
        
        <!-- Contenido -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                @include('flash::message')<!-- Notificaciones con Flash -->
                @yield('contenido')
            </div>
        </div>

    </div><!-- /wrapper -->

    <div id="footer">

        <footer class="text-center footer-template">
            INSUVI - Instituto de suelo, urbanización y vivienda del estado de colima
        </footer>

    </div>

    <script src="{{ asset('js/menu.js') }} "></script>

	@yield('js')
    
    @if($seguimiento || $admon)
        <script>
            $(document).ready(function(){

                seguimientos();

                function seguimientos(){
                    $.ajax({
                        url: "/SeguimientosPendientes",
                        type: "GET",
                        dataType: "json",
                        success:function(data){
                            var mensj = '';
                            if(data > 0){
                                $('.badge-seg').empty().text(data);
                                mensj = '<a href="{{route("seguimiento")}}"><span class="icon-eye"></span> Ver Pendientes</a>';
                            } else {
                                mensj = 'Ningún Seguimiento Pendiente';
                            }
                            $('.li-seg').empty().append(mensj);
                        }
                    });
                }
                setInterval(seguimientos, 10000);
                
            });
        </script>
    @endif

</body>
</html>