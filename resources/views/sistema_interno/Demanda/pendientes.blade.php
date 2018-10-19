@extends('sistema_interno.template.cuerpo')

@section('titulo','Demandas Pendientes')

@section('contenido')

@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('credito_index')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
			   		</select>
			   	</div>
				<b><input type="text" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" name="buscar" maxlength="18" id="dato"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	<br>

	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover">
				<thead class="BtnGray">
					<tr>
						<th class="text-center">Clave</th>
						<th>Modulo</th>
						<th>Programa</th>
						<th>Tipo Programa</th>
						<th>Enganche</th>
						<th>Tipo Cliente</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@if(count($demandas) < 1)
						<tr>
							<td colspan="9" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($demandas as $demanda)
					<tr>
						<th class="text-center"><?php echo str_pad($demanda->id_demanda, 4, "0", STR_PAD_LEFT); ?></th>
						<td>{{ substr($demanda->modulo, 4) }}</td>
						<td>{{ $demanda->tipo }}</td>
						<td>{{ $demanda->programa }}</td>
						<td>
							${{ number_format($demanda->enganche,2) }}					
						</td>
						<td>
							{{$demanda->tipo_cliente}}
						</td>
						<td class="text-center">
							<button class="btn BtnOrange btn-xs" type="button" data-toggle="collapse" data-target=".Collapse-{{$demanda->id_demanda}}" aria-expanded="false" aria-controls="Collapse-{{$demanda->id_demanda}}">
							  <span class="icon icon-enlarge2"></span> Detalle
							</button>
						</td>
						<td>
							<a class="btn BtnRed btn-xs" href="{{route('delete_demanda',$demanda->id_demanda)}}" onClick="return confirm('Al eliminar la demanda ya no se podrá recuperar, desea continuar?')">
							  <span class="icon icon-bin"></span> Eliminar
							</a>
						</td>
						<td>
							<button class="btn BtnGreen btn-xs" type="button" data-toggle="modal" data-target="#updated-{{$demanda->id_demanda}}">
							  <span class="icon icon-cog"></span> Modificar
							</button>
						</td>
					</tr>
					<tr class="collapse Collapse-{{$demanda->id_demanda}}" style="background-color: rgba(250,105,0,0.4);">
						<td><b>Observaciones:</b></td>
					  	<td colspan="4">{{ ($demanda->observaciones != "") ? $demanda->observaciones : "SIN REGISTRO" }} </td>
					  	<td><b>Cliente:</b></td>
					  	<td colspan="3">{{$demanda->nombre . " " . $demanda->ape_materno . " " . $demanda->ape_paterno}}</td>
					</tr>
					@if($demanda->clave_ahorrador)
						<tr class="collapse Collapse-{{$demanda->id_demanda}}" style="background-color: rgba(250,105,0,0.4);">
							<td><b>Clave Ahorrador:</b></td>
						  	<td colspan="4">{{ $demanda->clave_ahorrador }} </td>
						  	<td><b>Total Abonado:</b></td>
						  	<td colspan="3">${{ number_format($demanda->total_abonado,2) }}</td>
						</tr>
					@endif
					<!-- Modal Updated -->
					<div class="modal fade" id="updated-{{$demanda->id_demanda}}" tabindex="-1" role="dialog" aria-labelledby="head-updated">
					  <div class="modal-dialog modal-lg" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					      	<h4 class="modal-title text-center" id="head-updated"><b>Modificación</b></h4>
					      </div>
					      <div class="modal-body">
						    <form action="{{route('modificar_demanda',$demanda->id_demanda)}}" method="POST">
					      		<div class="row">
						      		<input type="hidden" name="_method" value="PUT">
						      		<input type="hidden" name="_token" value="{{csrf_token()}}">
						      		<div class="form-group col-md-6">
						      			<label>Modulo</label>
						      			<select name="modulo" class="form-control SimpleSelect" required>
						      				@foreach($modulos as $mod)
						      					<option value="{{$mod}}" <?php if($demanda->modulo == $mod) echo "selected" ?> >{{$mod}}</option>
						      				@endforeach
						      			</select>
						      		</div>
						      		<div class="form-group col-md-6">
						      			<label>Engache</label>
						      			<input type="number" step="0.01" name="enganche" class="form-control" value="{{$demanda->enganche}}" <?php if($demanda->tipo_cliente != "SOLICITANTE" || $demanda->enganche > 0) echo "readonly";?>>
						      		</div>
						      		<div class="form-group col-md-6">
						      			<label>Programa</label>
						      			<select name="programa" class="form-control SimpleSelect programa" required>
						      				@foreach($programas as $pro)
						      					<option value="{{$pro->id_programa}}" <?php if($demanda->programa == $pro->nombre) echo "selected" ?> >{{$pro->nombre}}</option>
						      				@endforeach
						      			</select>
						      		</div>
						      		<div class="form-group col-md-6 type">
						      			<label>Tipo Programa</label>
						      			<select name="tipo" class="form-control SimpleSelect TiposProgramas" required>
						      				<option value="{{$demanda->tipo_programa_id}}">{{$demanda->tipo}}</option>
						      			</select>
						      		</div>
						      		<div class="form-group col-md-12">
						      			<label>Observaciones (Opcional)</label>
						      			<textarea name="observaciones" rows="4" class="form-control">{{$demanda->observaciones}}</textarea>
						      		</div>
					      		</div>
						      	<div class="modal-footer">
							    	<button type="button" class="btn BtnRed btn-close-modal" data-dismiss="modal">Cerrar</button>
							        <input type="submit" class="btn BtnGreen" value="Guardar" onclick="return confirm('Seguro que desea modificarlo?')">
							    </div>
						    </form>
						  </div>
						</div>
				 	  </div>
					</div>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@stop

@section('js')
	<script>
		$(document).ready(function(){
			$('#tipo').change(function(){
				var type = $(this).val();
				if(type == "curp"){
					$('#dato').attr('maxlength','18').val("");
				} else {
					$('#dato').attr('maxlength', false).val("");
				}	
			});

			$('.programa').change(function(){
		        var id = $(this).val(), form = '';

		        $.ajax({
		            url:"/TiposProgramasGet",
		            type:"GET",
		            dataType:"json",
		            data:{'id':id},
		            async: false,
		            success:function(data){
		                if (data != "") {
		                	form += '<option selected value=""> Seleccionar </option>';
		                    $.each(data, function(index, dato){
		                		form += '<option value="'+dato.id_tipo_programa+'"> '+dato.nombre+' </option>';
		                    });
		                } else {
		                	form += '<option selected value=""> Seleccionar </option>';
		                }
		            }
		        });
		        
		        $(this).parent('.form-group').parent('div.row').find('div.type').find('select.TiposProgramas').empty().append(form);
		        $('.SimpleSelect').trigger('chosen:updated');
		    });
		});
	</script>
@stop