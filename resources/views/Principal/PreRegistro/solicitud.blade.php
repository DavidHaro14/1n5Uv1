@extends('principal.template.cuerpo')
<!--
@section('head')
	<script src='https://www.google.com/recaptcha/api.js'></script>
@stop
-->
@section('titulo','Sesión')

@section('contenido')

	@include('errors.errores')

    <div class="alert alert-danger msj-error" role="alert" style="display:none;">
    	<button type="button" class="close close-alert">
		  <span aria-hidden="true">&times;</span>
		</button>
        <li id="msj"></li>
    </div>
    <!-- Inicio de Sesion -->
    <div id="sesion" class="margin-sesion">
		<div class="row col-md-4 body-sesion">
			<p class="text-center" style="font-size: 20px; margin-bottom: 25px;"><b>Inicio de sesión</b></p>
			<form action="">
				<div class="input-group">
					<div class="input-group-addon"><span class="icon icon-user"></span></div>
					<input type="text" name="usuario" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" placeholder="CURP" maxlength="18" required>
				</div>
				<br>
				<div class="input-group">
					<div class="input-group-addon"><span class="icon icon-key2"></span></div>
					<input type="password" name="pass" class="form-control" placeholder="**********" required>
				</div>
				<br>
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary btn-sesion">Ingresar</button>
				</div>
			</form>
		</div>
	</div>
	<!-- Preregistro-->
    <div class="row col-md-offset-1 col-md-7" id="body-validacion">
	    <p class="text-center" style="font-size: 20px;"><b>Para registrarse favor de llenar el siguiente campo y continuar con los datos correspondientes.</b></p>
		<div class="row" id="validacion">
			<div class="input-group col-md-offset-2 col-md-8">
				<div class="input-group-addon">CURP</div>
				<input type="text" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" maxlength="18" name="validarcurp" id="dato">
				<div class="input-group-btn"><button type="submit" class="btn btn-default" id="btn-validar">Continuar</button></div>
			</div>
		</div>
		<div style="display:none;" id="formulario" class="row">
			<form action="{{ route('AgregarRegistro') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-group col-md-3">
					<label for="Nombre">Nombre</label>
					<input type="text" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" name="nombre" required value="{{ old('nombre') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="ape_paterno">Apellido Paterno</label>
					<input type="text" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" name="ape_paterno" required value="{{ old('ape_paterno') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="ape_materno">Apellido Materno</label>
					<input type="text" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" name="ape_materno" required value="{{ old('ape_materno') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="curp">CURP</label>
					<input type="text" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" name="curp" id="curp" required value="{{ old('curp') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="genero">Genero</label>
					<select name="genero" required class="form-control">
						<option selected value="" hidden>Seleccionar</option>
						@foreach($generos as $gen)
							<option value="{{$gen}}">{{$gen}}</option>	
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="fecha_nac">Fecha de Nacimiento</label>
					<input type="date" name="fecha_nac" class="form-control" required value="{{ old('fecha_nac') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="correo">Correo (Opcional)</label>
					<input type="email" class="form-control" name="correo" value="{{ old('correo') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="tel">Teléfono (Opcional)</label>
					<input type="text" name="tel" class="form-control OnlyNumber" maxlength="13" value="{{ old('tel') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="estado_nac">Estado de Nacimiento</label>
					<input type="text" name="estado_nac" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" required value="{{ old('estado_nac') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="lugar_nac">Lugar de Nacimiento</label>
					<input type="text" name="lugar_nac" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" required value="{{ old('lugar_nac') }}">
				</div>
				<div class="form-group col-md-3">
					<label for="dependientes">No. Dependientes</label>
					<input type="text" name="dependientes" class="form-control OnlyNumber" required maxlength="1" value="0">
				</div>
				<div class="form-group col-md-3">
					<label for="ocupacion">Ocupación</label>
					<select name="ocupacion" required class="form-control">
						<option selected value="" hidden>Seleccionar</option>
						@foreach($ocupaciones as $ocup)
							<option value="{{$ocup->id_ocupacion}}">{{$ocup->nombre}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="escolaridad">Escolaridad</label>
					<select name="escolaridad" required class="form-control">
						<option selected value="" hidden>Seleccionar</option>
						@foreach($escolaridades as $esc)
							<option value="{{$esc}}">{{$esc}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="estado_civil">Estado Civil</label>
					<select name="estado_civil" required class="form-control" id="EstadoCivil">
						<option selected value="" hidden>Seleccionar</option>
						@foreach($civiles as $civil)
							<option value="{{$civil}}">{{$civil}}</option>	
						@endforeach
					</select>
				</div>
				<div id="Conyuge" style="display:none;">
					<div class="form-group col-md-3">
						<label for="nombre_conyuge">Nombre (Cónyuge)</label>
						<input type="text" name="nombre_conyuge" class="form-control Conyuge text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" value="{{ old('nombre_conyuge') }}">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_p_conyuge">Apellido Paterno (Cónyuge)</label>
						<input type="text" name="ape_p_conyuge" class="form-control Conyuge text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" value="{{ old('ape_p_conyuge') }}">
					</div>

					<div class="form-group col-md-3">
						<label for="ape_m_conyuge">Apellido Materno (Cónyuge)</label>
						<input type="text" name="ape_m_conyuge" class="form-control Conyuge text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" value="{{ old('ape_m_conyuge') }}">
					</div>

					<div class="form-group col-md-3">
						<label for="curp_conyuge">CURP (Cónyuge)</label>
						<input type="text" name="curp_conyuge" class="form-control Conyuge text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" maxlength="18" value="{{ old('curp_conyuge') }}">
					</div>
					<div class="form-group col-md-3">
						<label for="fecha_nac_conyuge">Fecha de Nacimiento (Cónyuge)</label>
						<input type="date" name="fecha_nac_conyuge" class="form-control Conyuge" value="{{ old('fecha_nac_conyuge') }}">
					</div>
					<div class="form-group col-md-3">
						<label for="lugar_nac_conyuge">Lugar de Nacimiento (Cónyuge)</label>
						<input type="text" name="lugar_nac_conyuge" class="form-control Conyuge text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" value="{{ old('lugar_nac_conyuge') }}">
					</div>
					<div class="form-group col-md-3 bienes">
						<label for="bienes">Bienes</label>
						<select name="bienes" class="form-control" id="bienes">
							<option selected value="" hidden>Seleccionar</option>
							@foreach($bienes as $biene)
								<option value="{{$biene}}">{{$biene}}</option>	
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="pass">Contraseña</label>
					<input type="password" name="password" required maxlength="15" class="form-control" id="pass" value="{{ old('password') }}">
				</div>

				<div class="form-group col-md-3">
					<label for="confirmar">Confirmar Contraseña</label>
					<input type="password" name="password_confirmation" required maxlength="15" class="form-control" id="pass2">
				</div>
				<!--div class="row">
					<div class="col-md-12" style="margin-left:40%;">
						{!! app('captcha')->display(); !!}
					</div>
				</div-->
				<!--div class="g-recaptcha" data-sitekey="6Lfw5hIUAAAAAMVvnvyZfadu1mSwMZRpUVcP6XeH"></div-->
				<br><br>
				<div class="col-md-12 text-center">
					<a href="{{route('pre-registro')}}" class="btn btn-danger"><span class="icon icon-exit"></span> Cancelar</a>
				<button type="submit" class="btn btn-success"><span class="icon icon-upload"></span> Guardar</button>
				</div>
			</form>
		</div>
	</div>
	<hr>
	
@stop

@section('js')
	<script src="{{ asset('js/preregistro.js') }}"></script>
@stop