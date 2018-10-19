@extends('sistema_interno.template.cuerpo')

@section('titulo','Colonias')

@section('contenido')
	
	<div class="row">
		<div class="col-md-3">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-6 text-center">
			<a href="{{route('estado_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Estado</a>
			<a href="{{route('municipio_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Municipio</a>
			<a href="{{route('localidad_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Localidad</a>
		</div>
		<div class="col-md-3 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#colonias"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Estado</th>
						<th>Municipio</th>
						<th>Localidad</th>
						<th>Colonia/Fraccionamiento</th>
						<th class="text-center">Abreviación</th>
						<th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($colonias as $col)
					@if($col->localidad->estatus && $col->localidad->municipio->estatus)
						<tr id="tredit">
							<td>
								@if($col->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<td>{{$col->localidad->municipio->estado->nombre}}</td>
							<td>{{$col->localidad->municipio->nombre}}</td>
							<td>{{$col->localidad->nombre}}</td>
							<td>{{$col->nombre}}</td>
							<td class="text-center">{{$col->abreviacion}}</td>
							<td class="text-center">
								@if($col->estatus)
									<a href="{{route('colonia_estatus',$col->id_colonia)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
								@else
									<a href="{{route('colonia_estatus',$col->id_colonia)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
								@endif
							</td>
						</tr>
					@endif
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar colonias -->
		<div class="modal fade" id="colonias" tabindex="-1" role="dialog" aria-labelledby="head-colonias">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-colonias">Nueva Colonia</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('colonia_agregar')}}" method="POST">
		        	{{ csrf_field() }}
		        	<div class="form-group">
						<label for="Estado">Estado</label>
						<select class="form-control SimpleSelect" id="state" required>
							<option selected hidden value="">Seleccionar</option>
							@foreach($estados as $est)
								<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label for="Municipio">Municipio</label>
						<select name="municipio" class="form-control SimpleSelect" id="town" required>
							<option selected value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group">
						<label for="Localidad">Localidad</label>
						<select name="localidad" class="form-control SimpleSelect" id="location" required>
							<option selected value="">Seleccionar</option>
						</select>
					</div>

		        	<div class="form-group">
			            <label for="nombre">Nombre Colonia:</label>
			            <input type="text" class="form-control" name="nombre" required>
		          	</div>

		          	<div class="form-group">
			            <label>Abreviación:</label>
			            <input type="text" class="form-control" maxlength="4" name="abreviacion" required>
		          	</div>

				    <div class="modal-footer">
				    	<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
				        <input type="submit" class="btn BtnGreen" value="Guardar">
				    </div>
		        </form>
		      </div>
		    </div>
		  </div>
		</div>
	</div>

@stop

@section('js')
	<script src="{{ asset('js/colonia.js') }}"></script>
@stop