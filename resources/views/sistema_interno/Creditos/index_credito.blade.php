@extends('sistema_interno.template.cuerpo')

@section('titulo','Contratación')

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
						<th class="text-center">Clave Cliente</th>
						<th>Nombre</th>
						<th>CURP</th>
						<th>Domicilio</th>
						<th class="text-center">Demanda(s)</th>
					</tr>
				</thead>
				<tbody>
					@if(count($clientes) < 1)
						<tr>
							<td colspan="5" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($clientes as $cl)
					<tr>
						<th class="text-center"><?php echo str_pad($cl->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
						<td>{{ $cl->nombre }} {{ $cl->ape_paterno }} {{ $cl->ape_materno }}</td>
						<td>{{ $cl->curp }}</td>
						<td>
							{{
								$domicilio = $cl->calle->nombre . ", " . $cl->calle->colonia->nombre. " #" .$cl->num_ext . $cl->num_int	
							}}					
						</td>
						<td class="text-center">
							<button class="btn BtnOrange btn-xs" type="button" data-toggle="collapse" data-target="#Collapse-{{$cl->id_cliente}}" aria-expanded="false" aria-controls="Collapse-{{$cl->id_cliente}}">
							  <span class="icon icon-arrow-down"></span> Ver
							</button>
						</td>
					</tr>
					<tr class="collapse" id="Collapse-{{$cl->id_cliente}}">
						<td><b>Demanda(s):</b></td>
					  	<td colspan="4">
							@foreach($cl->demandas as $dem)
								@if($dem->estatus == "PREPARADO" && $dem->tipo_cliente == "SOLICITANTE")
									<a href="{{route('credito',$dem->id_demanda)}}" class="btn btn-xs BtnGreen">{{$dem->tipo_programa->nombre}}</a>
								@endif
							@endforeach
					  	</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		{{ $clientes->render() }}
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
		});
	</script>
@stop