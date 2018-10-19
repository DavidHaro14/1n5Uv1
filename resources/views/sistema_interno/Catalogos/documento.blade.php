@extends('sistema_interno.template.cuerpo')

@section('titulo','Documentos')

@section('contenido')
	
	<div class="row">
		<div class="col-md-6">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-6 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#documentos"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default table-width">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Nombre</th>
						<th class="text-center">Documento Opcional</th>
						<th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($documentos as $doc)
						<tr>
							<td>
								@if($doc->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<td>{{$doc->nombre}}</td>
							<td class="text-center">
								@if($doc->opcional)
									<span class="icon icon-checkmark"></span>
								@else
									<span class="icon icon-cross"></span>
								@endif
							</td>
							<td class="text-center">
								@if($doc->estatus)
									<a href="{{route('documento_estatus',$doc->id_documento)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
								@else
									<a href="{{route('documento_estatus',$doc->id_documento)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar documentos -->
		<div class="modal fade" id="documentos" tabindex="-1" role="dialog" aria-labelledby="head-documento">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-documento">Nuevo Documento</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('documento_agregar')}}" method="POST">
		        	{{ csrf_field() }}
		        	<div class="form-group">
			            <label>Nombre Documento:</label>
			            <input type="text" class="form-control" name="nombre" required>
		          	</div>
		          	<label>
		          		<input type="checkbox" value="1" name="opcional"> Documento Opcional
		          	</label>
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