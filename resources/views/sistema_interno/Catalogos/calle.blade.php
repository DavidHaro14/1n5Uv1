@extends('sistema_interno.template.cuerpo')

@section('titulo','Calles')

@section('contenido')
	
	<div class="row">
		<div class="col-md-2">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-8 text-center">
			<a href="{{route('estado_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Estado</a>
			<a href="{{route('municipio_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Municipio</a>
			<a href="{{route('localidad_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Localidad</a>
			<a href="{{route('colonia_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Colonia/Fraccionamiento</a>
		</div>
		<div class="col-md-2 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#calles"><span class="icon-plus"></span> Nuevo</button>
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
						<th>Colonia</th>
						<th>Calle</th>
						<th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($calles as $call)
					@if(
						$call->colonia->localidad->estatus && 
						$call->colonia->localidad->municipio->estatus && 
						$call->colonia->estatus
						)
						<tr>
							<td>
								@if($call->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<td>{{$call->colonia->localidad->municipio->estado->nombre}}</td>
							<td>{{$call->colonia->localidad->municipio->nombre}}</td>
							<td>{{$call->colonia->localidad->nombre}}</td>
							<td>{{$call->colonia->nombre}}</td>
							<td>{{$call->nombre}}</td>
							<td class="text-center">
								@if($call->estatus)
									<a href="{{route('calle_estatus',$call->id_calle)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
								@else
									<a href="{{route('calle_estatus',$call->id_calle)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
								@endif
							</td>
						</tr>
					@endif
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar calles -->
		<div class="modal fade" id="calles" tabindex="-1" role="dialog" aria-labelledby="head-calles">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-calles">Nueva Calle</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('calle_agregar')}}" method="POST">
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
						<label for="Colonia">Colonia</label>
						<select name="colonia" class="form-control SimpleSelect" id="colonie" required>
							<option selected value="">Seleccionar</option>
						</select>
					</div>

		        	<div class="form-group">
			            <label for="nombre">Nombre Calle:</label>
			            <input type="text" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" name="nombre" required>
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
	<script src="{{ asset('js/calle.js') }}"></script>
@stop