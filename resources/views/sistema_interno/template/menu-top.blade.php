<!-- MENU TOP -->
<nav class="navbar navbar-fixed-top" style="background:#000;">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="#menu-toggle" class="navbar-brand ColorMenuTop" id="menu-toggle"><span class="icon-menu3"></span> Menú</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        @if($admon)
        <!-- solicitudes -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle ColorMenuTop" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="icon-mail4"></span> Solicitudes</a>
          <ul class="dropdown-menu">
            <!-- <li><a href="#"><span class="icon-eye"></span> Perfil</a></li>
            <li role="separator" class="divider"></li> -->
            <!-- <li><a href="#"><span class="icon-key2"></span> Cambiar contraseña</a></li> -->
            <!-- <li role="separator" class="divider"></li>
            <li><a href="#"><span class="icon-question"></span> Ayuda</a></li> -->
            <!-- <li role="separator" class="divider"></li> -->
            <li><a href="{{ route('reporte_solicitudes') }}"><span class="icon-pie-chart"></span> Reporte</a></li>
          </ul>
        </li>
        @endif
      </ul>
      <ul class="nav navbar-nav navbar-right">
        @if($seguimiento || $admon)
          <!-- Notificación Seguimientos -->
          <li class="dropdown">
            <a href="#" class="dropdown-toggle ColorMenuTop" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="icon icon-history"><span class="badge" style="background-color:rgb(250,250,250);color:black;">0</span></span></a>
            <ul class="dropdown-menu">
              <li class="text-center li-seg">Ningún Seguimiento Pendiente</li>
            </ul>
          </li>
        @endif
        <!-- USUARIO -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle ColorMenuTop" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="icon-user"></span> {{Auth::user()->empleado->nombre . " " . Auth::user()->empleado->apellido_p . " " . Auth::user()->empleado->apellido_m}}</a>
          <ul class="dropdown-menu">
            <!-- <li><a href="#"><span class="icon-eye"></span> Perfil</a></li>
            <li role="separator" class="divider"></li> -->
            <!-- <li><a href="#"><span class="icon-key2"></span> Cambiar contraseña</a></li> -->
            <!-- <li role="separator" class="divider"></li>
            <li><a href="#"><span class="icon-question"></span> Ayuda</a></li> -->
            <!-- <li role="separator" class="divider"></li> -->
            <li><a href="{{ route('logout') }}"><span class="icon-exit"></span> Salir</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>