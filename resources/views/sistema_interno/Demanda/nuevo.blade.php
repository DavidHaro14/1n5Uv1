@extends('sistema_interno.template.cuerpo')

@section('titulo','Atención de la Demanda')

@section('contenido')
	<form action="{{ route('demandaC') }}" method="POST">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-12 text-center">
				<h3 class="BtnGray" style="padding:8px;">Nueva Demanda</h3>
			</div>
		</div>
		<div class="row">
			<input type="hidden" value="{{$cli->id_cliente}}" name="cliente">
			<input type="hidden" value="0" name="plantilla" id="plantilla">
			<div class="form-group col-md-4">
				<label for="Programa">Programa</label>
				<select name="programa" class="SimpleSelect" id="programa" required>
					<option selected value="">Seleccionar</option>
					@foreach($programas as $pro)
						<option value="{{ $pro->id_programa}}">{{ $pro->nombre}}</option>
					@endforeach
				</select>
			</div>
				
			<div class="form-group col-md-4">
				<label for="tipo_programa">Tipo Programa</label>
				<select name="tipo_programa" class="SimpleSelect" id="TiposProgramas" required>
					<option selected value="">Seleccionar</option>
					<!-- PHP -->
				</select>
			</div>

			<div class="form-group col-md-4">
				<label for="municipio">Modulo</label>
				<select name="modulo" class="SimpleSelect" required>
					<option selected value="">Seleccionar</option>
					@foreach($modulos as $mod)
						<option value="{{ $mod }}">{{ $mod }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-md-4">
				<label for="tipo_cliente">Tipo Cliente</label>
				<input type="text" name="tipo_cliente" value="SOLICITANTE" id="tipo_cliente" class="form-control readonly" required>
			</div>
			
			<div class="form-group col-md-4">
				<label for="enganche">Enganche <span class="icon icon-pencil ColorRequired"></span></label>
				<input type="number" name="enganche" id="enganche" class="form-control" value="0" required>
			</div>
				
			<div class="col-md-12"><label>Documentos</label></div>
			<div style="display:none;" class="img-carga col-md-12"><img src="{{asset('img/cargando.gif')}}" alt="carga"></div>
			<div class="col-md-12" id="documentos"></div>

			<div class="form-group col-md-12" style="margin-top:10px;">
				<label for="observaciones">Observaciones (Opcional)</label>
				<textarea name="observaciones" rows="5" class="form-control">NO HAY OBSERVACIONES</textarea>
			</div>
		</div>
		<div class="row">
			<div class="form-group text-center">
				<a href="{{route('insuvi')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Cancelar</a>
				<button type="submit" class="btn BtnGreen"><span class="icon icon-upload"></span> Guardar</button>
			</div>
		</div>
	</form>
		
@stop

@section('js')
	<script src="{{ asset('js/demanda.js') }}"></script>
@stop
