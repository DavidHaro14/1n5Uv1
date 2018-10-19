@extends('sistema_interno.template.cuerpo')

@section('titulo','Tipos de Programas')

@section('contenido')
	
	<div class="row">
		<div class="col-md-2">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-8 text-center">
			<a href="{{route('programa_index')}}" class="btn BtnOrange"><span class="icon icon-pushpin"></span> Programas</a>
			<a href="{{route('documento_index')}}" class="btn BtnOrange"><span class="icon icon-profile"></span> Documentos</a>
			<a href="{{route('contrato_index')}}" class="btn BtnOrange"><span class="icon icon-file-text"></span> Contratos</a>
			<a href="{{route('banco_index')}}" class="btn BtnOrange"><span class="icon icon-library"></span> Banco</a>
		</div>
		<div class="col-md-2 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#programas"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th class="text-center">Plantilla</th>
						<th>Banco</th>
						<th>Programa</th>
						<th>Tipo de Programa</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($tiposprogramas as $tiprog)
						<tr>
							<td>
								@if($tiprog->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<td class="text-center">{{$tiprog->plantilla}}</td>
							<td>{{$tiprog->banco->nombre}} <br>{{$tiprog->banco->cuenta}}</td>
							<td>{{$tiprog->programa->nombre}}</td>
							<td>{{$tiprog->nombre}}</td>
							<td>
								<button type="button" class="btn BtnOrange btn-xs" data-target="#info-{{$tiprog->id_tipo_programa}}" data-toggle="modal"><span class="icon-eye"></span> Detalle</button>
							</td>
							<td>
									@if($tiprog->estatus)
										<a href="{{route('tiprog_estatus',$tiprog->id_tipo_programa)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
									@else
										<a href="{{route('tiprog_estatus',$tiprog->id_tipo_programa)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
									@endif
								</form>
							</td>
						</tr>
						<!-- Info -->
						<div class="modal fade" id="info-{{$tiprog->id_tipo_programa}}" tabindex="-1" role="dialog" aria-labelledby="head-programas">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						      	<h4 class="modal-title" id="head-programas">Información</h4>
						      </div>
						      <div class="modal-body">
						      	<div class="text-center">
									<h4 class="BtnGray" style="padding:8px;">Documento(s)</h4>
								</div>
								<ul>
									@foreach($tiprog->documentos as $doc)
										<li>{{$doc->nombre}}</li>
									@endforeach
								</ul>
								<div class="text-center">
									<h4 class="BtnGray" style="padding:8px;">Contrato(s)</h4>
								</div>
								<ul>
									@foreach($tiprog->contratos as $cont)
										<li>{{$cont->nombre}}</li>
									@endforeach
								</ul>
						      </div>
						    </div>
						  </div>
						</div>
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Insertar programas -->
		<div class="modal fade" id="programas" tabindex="-1" role="dialog" aria-labelledby="head-programas">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	<h4 class="modal-title" id="head-programas">Nuevo Tipo Programa</h4>
		      </div>
		      <div class="modal-body">
		        <form action="{{route('tiprog_agregar')}}" method="POST" onSubmit="return validacion()"><!-- enctype="multipart/form-data"-->
		        	{{ csrf_field() }}
		        	<div class="form-group">
						<label for="Programa">Programa</label>
						<select name="programa" class="form-control" required>
							<option selected hidden value="">Seleccionar</option>
							@foreach($programas as $pro)
								<option value="{{$pro->id_programa}}">{{$pro->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label for="documento">Documentos</label>
						<select name="documento[]" class="form-control MultiSelect" multiple required>
							@foreach($documentos as $doc)
								<option value="{{$doc->id_documento}}">{{$doc->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label>Contratos</label>
						<select name="contrato[]" class="form-control MultiSelect" multiple required>
							@foreach($contratos as $con)
								<option value="{{$con->id_contrato}}">{{$con->nombre}}</option>
							@endforeach
						</select>
					</div>

		          	<div class="form-group">
						<label>Banco</label>
						<select name="banco" class="form-control" required>
							<option selected hidden value="">Seleccionar</option>
							@foreach($bancos as $bank)
								<option value="{{$bank->id_banco}}">{{$bank->nombre}} - {{$bank->cuenta}}</option>
							@endforeach
						</select>
					</div>

		        	<div class="form-group">
			            <label for="nombre">Tipo Programa:</label>
			            <input type="text" class="form-control" name="nombre" required>
		          	</div>
					
					<div class="form-group">
						<label>Plantilla</label>
						<select name="plantilla" class="form-control" required>
							<option selected hidden value="">Seleccionar</option>
							@foreach($plantillas as $pl)
								<option value="{{$pl}}">{{$pl}}</option>
							@endforeach
						</select>
					</div>

		          	<div class="form-group">
						<label>Descripción</label><br>
						<textarea name="descripcion" rows="5" class="form-control" required></textarea>
					</div>
		          	<!--div class="form-group">
						<label for="nombre">Contrato:</label>
						<input type="file" name="contrato" required>
					</div-->
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