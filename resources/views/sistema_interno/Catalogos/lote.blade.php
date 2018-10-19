@extends('sistema_interno.template.cuerpo')

@section('titulo','Lotes')

@section('contenido')

	@include('errors.errores')

	<div class="row">
		<div class="col-md-6">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-exit"></span> Salir</a>
		</div>
		<div class="col-md-6 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#lote"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Fraccionamiento</th>
						<th>Manzana</th>
						<th>Lote</th>
						<th>Superficie</th>
						<th>Clave Catastral</th>
						<th>Ochavo</th>
						<th>Uso de Suelo</th>
						<th>Domicilio</th>
						<th class="text-center">Colindancias</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($lotes as $lot)
					<tr>
						@if($lot->estatus == "DISPONIBLE")
							<td style="color:green;"><strong>{{$lot->estatus}}</strong></td>
						@else
							<td><strong>{{$lot->estatus}}</strong></td>
						@endif
						<td>{{$lot->fraccionamiento->nombre}}</td>
						<td>{{$lot->no_manzana}}</td>
						<td>{{$lot->no_lote}}</td>
						<td>{{$lot->superficie}} M2</td>
						<td>{{$lot->clave_catastral}}</td>
						<td>{{$lot->ochavo}}</td>
						<td>{{$lot->uso_suelo}}</td>
						<td>{{$lot->calle}} #{{$lot->numero}}</td>
						<td class="text-center"><button type="button" class="btn BtnOrange btn-xs" data-toggle="modal" data-target="#colindancias-{{$lot->id_lote}}"><span class="icon-location"></span></button></td>
						<td class="text-center">
							@if($lot->estatus == "DISPONIBLE" && $lot->superficie >= 192)
								<a href="{{route('division_lote',$lot->id_lote)}}" class="btn BtnGreen btn-xs Letrero" title="Dividir Loter"><span class="icon-scissors"></span></a>
							@endif
						</td>
					</tr>
					<!-- Modal Colindancias -->
					<div class="modal fade" id="colindancias-{{$lot->id_lote}}" tabindex="-1" role="dialog" aria-labelledby="head-colin">
					  <div class="modal-dialog modal-lg" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					      	<h4 class="modal-title" id="head-colin">Colindancias</h4>
					      </div>
					      <div class="modal-body">
					      	<strong>Norte: </strong>{{$lot->norte}}       <hr>
					      	<strong>Noreste: </strong>{{$lot->noreste}}   <hr>
					      	<strong>Este: </strong>{{$lot->este}}         <hr>
					      	<strong>Sureste: </strong>{{$lot->sureste}}   <hr>
					      	<strong>Sur: </strong>{{$lot->sur}}           <hr>
					      	<strong>Suroeste: </strong>{{$lot->suroeste}} <hr>
					      	<strong>Oeste: </strong>{{$lot->oeste}}       <hr>
					      	<strong>Noroeste: </strong>{{$lot->noroeste}} 
							<div class="modal-footer">
							  	<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
							</div>
					      </div>
					    </div>
					  </div>
					</div>
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar Lotes -->
		<div class="modal fade" id="lote" tabindex="-1" role="dialog" aria-labelledby="head-lote">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-lote">Importar Lotes</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('lote_agregar')}}" method="POST" enctype="multipart/form-data">
		        	{{ csrf_field() }}
		        	<div class="form-group">
						<label>Estado</label>
						<select class="form-control SimpleSelect" id="state" required>
							<option selected hidden value="">Seleccionar</option>
							@foreach($estados as $est)
								<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label>Municipio</label>
						<select class="form-control SimpleSelect" id="town" required>
							<option selected value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group">
						<label>Localidad</label>
						<select class="form-control SimpleSelect" id="location" required>
							<option selected value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group">
						<label>Fraccionamiento</label>
						<select name="fraccionamiento" class="form-control SimpleSelect" id="colonie" required>
							<option selected value="">Seleccionar</option>
						</select>
					</div>

		        	<div class="form-group">
			            <label>Lote (Solo Archivo en Excel):</label>
			            <input type="file" name="lotes" required>
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
	<script>
		$('.close-alert').click(function(){
			$('.msj-error').fadeOut();
		});
	</script>
@stop