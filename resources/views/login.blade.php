<!-- Plantilla del sistema interno -->
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="UTF-8">

	<title>Inicio Sesión</title>
    <!-- Documentacion datepicker https://bootstrap-datepicker.readthedocs.io/en/latest/options.html#orientation -->
	<link rel="stylesheet" href="{{ asset('bootstrap/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/minilogo.png') }}">
	<link rel="stylesheet" href="{{ asset('fonts/style.css') }}">
	<link rel="stylesheet" href="{{ asset('css/estilos.css') }}">

	<style>

		body{
			background-color: rgb(52,56,56);
			/* text-align: left; */
		}
        .login{
            width: 50%;
            margin: 0 0 0 25%;
        }

        button{
            width: 50%;
        }

    </style>

    <script src="{{ asset('jquery/jquery.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.min.js') }}"></script>
</head>
<body>
	<br>
    <br>
    
    <div class="container">
        <div class="login">
            <div class="text-center BtnOrange" style="padding:8px;border-radius:4px 4px 0 0;margin-bottom:-5px;">
                <h4><b><span class="icon icon-lock"></span> INICIO DE SESION</b></h4>
            </div>
            <div class="panel panel-default" style="padding:28px;background-size: cover;">
            	<div class="text-center">
	        		<img src="{{asset('img/logo.png')}}" style="width:45%;height:43%;">
            	</div>
            	<br><br>
	            <?php if(count($errors) > 0){ ?>
	                <div class="alert BtnRed">
	                	<button type="button" class="close col-md-12 text-right" data-dismiss="alert" aria-label="Close">
						  	<span aria-hidden="true">&times;</span>
						</button>
	                    <p class="text-center"><b>Error:</b></p>
	                    <ul>
	                        <?php foreach($errors->all() as $error) { ?>
	                            <li><?php echo $error ?></li>
	                        <?php } ?>
	                    </ul>
	                </div>
	            <?php } ?>
	            <form action="{{route('autentificacion')}}" method="POST">
	                {{csrf_field()}}
	                <div class="form-group col">
	                    <div class="input-group">
	                        <span class="input-group-addon"><span class="icon icon-user"></span></span>
	                        <input type="text" placeholder="USUARIO" name="usuario" class="form-control input-lg" required>
	                    </div>
	                </div>
	                <div class="form-group col">
	                    <div class="input-group">
	                        <span class="input-group-addon"><span class="icon icon-key"></span></span>
	                        <input type="password" placeholder="PASSWORD" name="password" class="form-control input-lg" required>
	                    </div>
	                </div>
	                <div class="form-group col text-center">
	                    <button type="submit" class="btn BtnOrange"><span class="icon icon-unlocked"></span> Acceder</button>
	                </div>
	            </form>
            </div>
        </div>
        
    </div>

    <div id="footer">

        <footer class="text-center footer-template">
            INSUVI - Instituto de suelo, urbanización y vivienda del estado de colima
        </footer>

    </div>

</body>
</html>