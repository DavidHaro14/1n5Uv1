@extends('sistema_interno.template.cuerpo')

@section('titulo','Información Cliente')

@section('contenido')

@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('view_cliente')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
			   			<option value="domicilio">Domicilio</option>
			   		</select>
			   	</div>
				<b><input type="text" class="form-control" name="buscar" maxlength="18" id="dato"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	<br>
	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<th>Estatus</th>
					<th class="text-center">Clave</th>
					<th>Nombre</th>
					<th>CURP</th>
					<th>Domicilio</th>
					<th></th>
					<th></th>
				</thead>
				<tbody>
					@if(count($clientes) < 1)
						<tr>
							<td colspan="5" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($clientes as $cl)
						<tr>
							<td>
								@if($cl->estatus)
									<span class="label BtnGreen">Activo</span>
								@else
									<span class="label BtnRed">Inactivo</span>
								@endif
							</td>
							<th class="text-center"><?php echo str_pad($cl->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
							<td>{{$cl->nombre . " " . $cl->ape_paterno . " " . $cl->ape_materno}}</td>
							<td>{{$cl->curp}}</td>
							@if($domicilio > 0)
								<td>{{$cl->nom_calle . " #" . $cl->num_ext . ", " . $cl->nom_mun }}</td>
							@else
								<td>{{$cl->calle->nombre . " #" . $cl->num_ext . ", " . $cl->calle->colonia->localidad->municipio->nombre }}</td>
							@endif
							<td>
								<a href="{{route('view_cliente',$cl->id_cliente)}}" class="btn BtnOrange btn-xs"><span class="icon-info"></span> Detalle
								</a>
							</td>
							<td>
								@if($cl->estatus)
									<a href="{{route('estatus_cliente',$cl->id_cliente)}}" class="btn BtnRed btn-xs"><span class="icon icon-arrow-down"></span> Suspender</a>
								@else
									<a href="{{route('estatus_cliente',$cl->id_cliente)}}" class="btn BtnGreen btn-xs"><span class="icon icon-arrow-up"></span> Activar</a>
								@endif
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