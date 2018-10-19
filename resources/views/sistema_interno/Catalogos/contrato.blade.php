@extends('sistema_interno.template.cuerpo')

@section('titulo','Contratos')

@section('contenido')
	
	<div class="row">
		<div class="col-md-6">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-6 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#contrato"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default table-width">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Contrato</th>
						<th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($contratos as $cont)
						<tr>
							<td>
								@if($cont->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<td>{{$cont->nombre}}</td>
							<td class="text-center">
								@if($cont->estatus)
									<a href="{{route('contrato_estatus',$cont->id_contrato)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
								@else
									<a href="{{route('contrato_estatus',$cont->id_contrato)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar contrato -->
		<div class="modal fade" id="contrato" tabindex="-1" role="dialog" aria-labelledby="head-contratos">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-contratos">Nuevo contrato</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('contrato_agregar')}}" method="POST" enctype="multipart/form-data">
		        	{{ csrf_field() }}
		        	<div class="form-group">
			            <label>Contrato (Solo Archivo en Word):</label>
			            <input type="file" name="contrato" required>
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