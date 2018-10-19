@extends('sistema_interno.template.cuerpo')

@section('titulo','Asignación de Cajas')

@section('contenido')

	@include('errors.errores')
	
	<div class="row table-responsive">
		<div class="col-md-4">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-4 text-center">
			<a href="{{route('empleado_index')}}" class="btn BtnOrange"><span class="icon icon-users"></span> Personal</a>
		</div>
		<div class="col-md-4 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#caja"><span class="icon icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default">
			<table class="table table-hover">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Clave</th>
						<th>Modulo</th>
						<th>Folio Inicial</th>
						<th>Folio Final</th>
						<th>Folio Actual</th>
						<th>Usuario</th>
						<th width="300">Opciones</th>
					</tr>
				</thead>
				<tbody>
					@if(count($cajas) < 1)
						<tr>
							<td colspan="8">No se encontró ningún registro</td>
						</tr>
					@endif
					@foreach($cajas as $caj)
						<tr>
							<td>
								@if($caj->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<th>{{str_pad($caj->id_caja, 2, "0", STR_PAD_LEFT)}}</th>
							<td>{{$caj->modulo}}</td>
							<td>{{$caj->folio_inicio}}</td>
							<td>{{$caj->folio_final}}</td>
							<td>{{$caj->folio_actual}}</td>
							<td>
								@if($caj->usuario_id)
									{{$caj->usuario->usuario}}
								@else
									<span class="label BtnRed">Sin Asignar</span>
								@endif
							</td>
							<td>

								@if(!$caj->usuario_id && $caj->estatus)
									<button type="button" class="btn btn-xs BtnGreen" data-toggle="modal" data-target="#asignar-{{$caj->id_caja}}"><span class="icon icon-user-plus"></span> Asginar Usuario</button>
								@elseif($caj->usuario_id && $caj->estatus)
									<a href="{{route('baja_usuario', $caj->id_caja)}}" class="btn btn-xs BtnRed"><span class="icon icon-user-minus"></span> Quitar Usuario</a>
								@endif

								@if($caj->estatus)
									<a href="{{route('estatus_caja',$caj->id_caja)}}" class="btn btn-xs BtnRed"><span class="icon icon-arrow-down"></span> Suspender Caja</a>
								@else
									<a href="{{route('estatus_caja',$caj->id_caja)}}" class="btn btn-xs BtnGreen"><span class="icon icon-arrow-up"></span> Activar Caja</a>
								@endif

							</td>
						</tr>
						@if(!$caj->usuario_id && $caj->estatus)
							<!-- MODAL ASIGNACION -->
							<div class="modal fade" id="asignar-{{$caj->id_caja}}" tabindex="-1" role="dialog" aria-labelledby="head-asignar">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							      	<h4 class="modal-title" id="head-asignar">Asignación de Caja</h4>
							      </div>
							      <div class="modal-body">
							      	<form action="{{route('asignacion_caja',$caj->id_caja)}}" method="POST">
							      		{{ csrf_field() }}
							      		<div class="form-group">
							      			<label>Usuario</label>
							      			<select name="user" class="form-control" required>
							      				<option value="">Seleccionar</option>
							      				@foreach($users as $us)
							      					<option value="{{$us->id_usuario}}">{{$us->usuario}}</option>
							      				@endforeach
							      			</select>
							      		</div>
							      		<div class="form-group">
							      			<label>Folio Inicial</label>
							      			<input type="number" class="form-control" name="inicial" required value="{{$caj->folio_inicio}}">
							      		</div>
							      		<div class="form-group">
							      			<label>Folio Final</label>
							      			<input type="number" class="form-control" name="final" required value="{{$caj->folio_final}}">
							      		</div>
							      		<div class="form-group">
							      			<label>Folio Actual</label>
							      			<input type="number" class="form-control" name="actual" required value="{{$caj->folio_actual}}">
							      		</div>
										<div class="modal-footer">
										   	<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
										   	<button type="submit" class="btn BtnGreen">Guardar</button>
										</div>
							      	</form>
							      </div>
							    </div>
							  </div>
							</div>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<!-- NUEVA CAJA -->
	<div class="modal fade" id="caja" tabindex="-1" role="dialog" aria-labelledby="head-cajas">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title" id="head-cajas">Nueva Caja</h4>
	      </div>
	      <div class="modal-body">
	        <form action="{{route('add_caja')}}" method="POST">
	        	{{ csrf_field() }}
	        	
	          	<div class="form-group">
	          		<label>Modulo</label>
	          		<select name="modulo" class="form-control" id="modulo" required>
						<option selected value="">Seleccionar</option>
						@foreach($modulos as $mod)
							<option value="{{$mod}}">{{$mod}}</option>
						@endforeach
					</select>
	          	</div>

	          	<div class="form-group">
	          		<label>Folio Inicio</label>
	          		<input type="number" class="form-control" name="inicial" required>
	          	</div>

	          	<div class="form-group">
	          		<label>Folio Final</label>
	          		<input type="number" class="form-control" name="final" required>
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

@stop