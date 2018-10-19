@extends('sistema_interno.template.cuerpo')

@section('titulo','Reestructura')

@section('contenido')

@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row client">
		<div class="input-group">
			<div class="input-group-btn">
		   		<select name="tipo" class="btn btn-default size-search" id="tipo">
		   			<option value="curp">CURP</option>
		   			<option value="nombre">Nombre</option>
		   		</select>
		   	</div>
			<b><input type="text" class="form-control" name="buscar" maxlength="18" id="dato"></b>
			<div class="input-group-btn">
				<button type="button" class="btn BtnOrange" id="btn-search"><span class="icon icon-search"></span> Buscar</button>
			</div>
		</div>
	</div>
	<br>

	<div class="row client">
		<div class="panel panel-default">
			<table class="table table-hover">
				<thead class="BtnGray">
					<tr>
						<th class="text-center">Clave Cliente</th>
						<th>Nombre</th>
						<th>CURP</th>
						<th>Estado Civil</th>
						<th class="text-center">Ver Créditos</th>
					</tr>
				</thead>
				<tbody class="t-body">
					<tr>
						<td colspan="5"><b>BUSCAR UN CLIENTE...</b></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	{{-- CREDITOS --}}
	<div id="creditos"></div>

@stop

@section('js')
	<script src="{{ asset('js/reestructura_index.js') }}"></script>
@stop