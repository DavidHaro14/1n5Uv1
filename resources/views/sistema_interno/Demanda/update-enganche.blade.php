@extends('sistema_interno.template.cuerpo')

@section('titulo', 'Enganche Demanda')

@section('contenido')

	@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('demanda_enganche')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="clave_demanda">Clave Demanda</option>
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
			   			<option value="clave_cliente">Clave Cliente</option>
			   		</select>
			   	</div>
				<b><input type="text" class="form-control" name="buscar" id="dato"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	
	<br>
	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<th class="text-center">Demanda</th>
					<th>Cliente</th>
					<!-- <th>Programa</th> -->
					<th>Tipo de Programa</th>
					<th>Modulo</th>
					<th>Enganche</th>
					<th>Total Abonado</th>
					<th class="text-center">Modificar</th>
				</thead>
				<tbody>
					@if(count($demandas) < 1)
						<tr>
							<td colspan="8" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($demandas as $dem)
						<tr>
							<th class="text-center"><?php echo str_pad($dem->id_demanda, 4, "0", STR_PAD_LEFT); ?></th>
							<td>{{$dem->cliente->nombre . " " . $dem->cliente->ape_paterno . " " . $dem->cliente->ape_materno}}</td>
							<td>{{$dem->tipo_programa->nombre}}</td>
							<!-- <td>{{$dem->tipo_programa->programa->nombre}}</td> -->
							<td><?php echo substr($dem->modulo, 5);?></td>
							<td>${{number_format($dem->enganche,2)}}</td>
							<td>${{number_format($dem->ahorrador->total_abonado,2)}}</td>
							<td class="text-center"><button type="button" class="btn BtnOrange btn-xs" data-toggle="modal" data-target="#editar-{{$dem->id_demanda}}" ><span class="icon-cogs"></span> Enganche</button></td>
						</tr>
						<!-- Modal Editar -->
						<div class="modal fade" id="editar-{{$dem->id_demanda}}" tabindex="-1" role="dialog" aria-labelledby="head-editar">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						      	<h4 class="modal-title" id="head-editar">Editar Enganche</h4>
						      </div>
						      <div class="modal-body">
						        <form action="{{route('demanda_update')}}" method="POST">
						        	<input type="hidden" name="_method" value="PUT">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
						        	<div class="form-group">
							            <label>Enganche:</label>
							            <input type="number" class="form-control" name="enganche" value="{{$dem->enganche}}" min="{{$dem->ahorrador->total_abonado}}" step="0.01">
							            <input type="hidden" value="{{$dem->id_demanda}}" name="clave">
						          	</div>
								    <div class="modal-footer">
								    	<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
								        <input type="submit" class="btn BtnGreen" value="Guardar" onclick="return confirm('SEGURO QUE DESEA CONTINUAR?')">
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
		$('input').focus(function(){
			$(this).select();
		});

		//cerrar la alerta de error 
	    $('.close-alert').click(function(){
	        $('.msj-error').fadeOut();
	    });
	</script>

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
		});
	</script>
@stop