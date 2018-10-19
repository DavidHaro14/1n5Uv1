@extends('sistema_interno.template.cuerpo')

@section('titulo','Municipios')

@section('contenido')
	
	<div class="row">
		<div class="col-md-4">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-4 text-center">
			<a href="{{route('estado_index')}}" class="btn BtnOrange"><span class="icon icon-location"></span> Estado</a>
		</div>
		<div class="col-md-4 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#municipios"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default table-width">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Estado</th>
						<th>Municipio</th>
						<th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($municipios as $mun)
					@if($mun->estado->estatus)
						<tr>
							<td>
								@if($mun->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<td>{{$mun->estado->nombre}}</td>
							<td>{{$mun->nombre}}</td>
							<td class="text-center">
								@if($mun->estatus)
									<a href="{{route('municipio_estatus',$mun->id_municipio)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
								@else
									<a href="{{route('municipio_estatus',$mun->id_municipio)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
								@endif
							</td>
						</tr>
					@endif
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar municipios -->
		<div class="modal fade" id="municipios" tabindex="-1" role="dialog" aria-labelledby="head-municipio">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-municipio">Nuevo Municipio</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('municipio_agregar')}}" method="POST">
		        	{{ csrf_field() }}
		        	<div class="form-group">
						<label for="Estado">Estado</label>
						<select name="estado" class="form-control" required>
							<option selected hidden value="">Seleccionar</option>
							@foreach($estados as $est)
								<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
							@endforeach
						</select>
					</div>
		        	<div class="form-group">
			            <label for="nombre">Nombre Municipio:</label>
			            <input type="text" class="form-control" name="nombre" required>
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
	<script>
		$(document).ready(function(){
			$('.alert').fadeOut(5600);
		});	
	</script>
@stop