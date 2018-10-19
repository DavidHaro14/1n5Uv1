@extends('sistema_interno.template.cuerpo')

@section('titulo','Subsidios')

@section('contenido')
	
	<div class="row">
		<div class="col-md-6">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-6 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#subsidio"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Organización</th>
						<th>Clave</th>
						<th>Valor</th>
						<th>Tipo</th>
						<th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>
					@if(count($subsidios) < 1)
						<tr>
							<td colspan="6" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($subsidios as $sub)
						<tr>
							<td>
								@if($sub->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<td>{{$sub->organizacion}}</td>
							<td>{{$sub->clave}}</td>
							<td>${{$sub->valor}}</td>
							<td>{{$sub->tipo}}</td>
							<td class="text-center">
									@if($sub->estatus)
										<a href="{{route('subsidio_estatus',$sub->id_subsidio)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
									@else
										<a href="{{route('subsidio_estatus',$sub->id_subsidio)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
									@endif
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar Subsidio -->
		<div class="modal fade" id="subsidio" tabindex="-1" role="dialog" aria-labelledby="head-subsidio">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-subsidio">Nuevo Subsidio</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('subsidio_agregar')}}" method="POST">
		        	{{ csrf_field() }}
		        	<div class="form-group">
			            <label>Organización:</label>
			            <input type="text" class="form-control" name="organizacion" required>
		          	</div>
		          	<div class="form-group">
			            <label>Clave:</label>
			            <input type="text" class="form-control" name="clave" required>
		          	</div>
		          	<div class="form-group">
			            <label>Valor ($):</label>
			            <input type="number" class="form-control" name="valor" required>
		          	</div>
		          	<div class="form-group">
			            <label>Tipo:</label>
			            <select name="tipo" class="form-control" required>
			            	<option value="">Seleccionar</option>
			            	@foreach($tipos as $type)
			            		<option value="{{$type}}">{{$type}}</option>
			            	@endforeach
			            </select>
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