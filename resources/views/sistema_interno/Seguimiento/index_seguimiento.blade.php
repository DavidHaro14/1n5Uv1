@extends('sistema_interno.template.cuerpo')

@section('titulo','Seguimientos')

@section('contenido')
	
@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('seguimiento')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="credito">Clave Crédito</option>
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
			   		</select>
			   	</div>
				<b><input type="text" class="form-control" name="buscar" id="dato"></b>
				<div class="input-group-btn"><button type="submit" class="btn BtnOrange"><span class="icon icon-search"></span> Buscar</button></div>
			</div>
		</form>
	</div>
	<br>
	<!-- <div class="row btn-seg"></div> -->
	<div class="row">
		<div class="panel panel-default">
			<table class="table table-hover">
				<thead class="BtnGray">
					<tr class="th-tr">
						<th class="text-center">Clave Cliente</th>
						<th>Nombre</th>
						<th>CURP</th>
						<th>Clave Crédito</th>
						@if(!$busqueda)
							<th>Mens. Vencidos</th>
						@endif
						<th class="text-center">Seguimientos</th>
					</tr>
				</thead>
				<tbody class="tbody">
					@if(count($creditos) < 1)
						<tr>
							<td colspan="6"><b>
							@if($busqueda)
								No se encontró ningún resultado...
							@else
								Buscar...
							@endif
							</b></td>
						</tr>
					@endif
					@foreach($creditos as $cre)
						@if($cre->vencidos > 2)
							<tr>
								<th class="text-center"><?php echo str_pad($cre->demanda->cliente->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
								<td>{{ $cre->demanda->cliente->nombre ." ". $cre->demanda->cliente->ape_paterno ." ". $cre->demanda->cliente->ape_materno }}</td>
								<td>{{ $cre->demanda->cliente->curp }}</td>
								<td>{{ $cre->clave_credito }}</td>
								@if(!$busqueda)
									<td class="text-center"><span class="badge">{{ $cre->vencidos }}</span>	</td>
								@endif
								<td class="text-center">
									<a class="btn BtnOrange btn-xs" href="{{route('seguimiento_credito',$cre->clave_credito)}}"><span class="icon icon-eye"></span> Ver</a>
								</td>
							</tr>
						@endif
					@endforeach
				</tbody> 
			</table>
		</div>
		{{ $creditos->render() }}
	</div>

@stop
