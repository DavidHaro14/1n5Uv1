@extends('sistema_interno.template.cuerpo')

@section('titulo','Cancelación de Créditos')

@section('contenido')
	
@include('errors.errores')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('cancelacion_index')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="credito">Clave Crédito</option>
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
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
			<table class="table table-hover">
				<thead class="BtnGray">
					<tr>
						<th class="text-center">Clave Cliente</th>
						<th>Nombre</th>
						<th>CURP</th>
						<th>Tipo Programa</th>
						<th>Clave Crédito</th>
						<th class="text-center">Cancelar</th>
					</tr>
				</thead>
				<tbody>
					@if(count($creditos) < 1)
						<tr>
							<td colspan="6" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($creditos as $cre)
					<tr>
						<th class="text-center"><?php echo str_pad($cre->demanda->cliente->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
						<td>{{ $cre->demanda->cliente->nombre ." ". $cre->demanda->cliente->ape_paterno ." ". $cre->demanda->cliente->ape_materno }}</td>
						<td>{{ $cre->demanda->cliente->curp }}</td>
						<td>{{ $cre->demanda->tipo_programa->nombre }}</td>
						<td>{{ $cre->clave_credito }}</td>
						<td class="text-center">
							<button type="button" class="btn btn-xs BtnOrange" data-toggle="modal" data-target="#creditos-{{$cre->clave_credito}}"><span class="icon icon-arrow-down"></span></button>
						</td>
					</tr>
					<!-- Cancelación -->
					<div class="modal fade" id="creditos-{{$cre->clave_credito}}" tabindex="-1" role="dialog" aria-labelledby="head-credito">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					      	<h4 class="modal-title" id="head-credito">Cancelación</h4>
					      </div>
					      <div class="modal-body">
					        <form action="{{route('cancelacionC')}}" method="POST">
					        	{{ csrf_field() }}
					        	<div class="row">
									<div class="form-group col-md-6">
										<label>Crédito</label>
										<input type="text" name="credito" class="form-control readonly" value="{{$cre->clave_credito}}" required>
									</div>
									<!-- <div class="form-group col-md-6">
										<label>Fecha</label>
										<input type="text" class="form-control readonly" value="<?php echo date('d-m-Y')?>">
									</div> -->
									<!-- <div class="form-group">
										<label>Usuario</label>
										<input type="text" name="usuario" class="form-control" value="VERO" readonly>
									</div> -->
						        	<div class="form-group col-md-6">
										<label>Folio <span class="icon icon-pencil ColorRequired"></span></label>
										<input type="number" name="folio" class="form-control" required>
									</div>
									<div class="form-group col-md-6">
										<label>Importe</label>
										<input type="hidden" name="importe" value="{{$cre->total_abonado}}">
										<input type="text" class="form-control readonly" value="{{number_format($cre->total_abonado,2)}}" required>
									</div>
									<div class="form-group col-md-6">
										<label>No. Cheque <span class="icon icon-pencil ColorRequired"></span></label>
										<input type="text" class="form-control" name="no_cheque">
									</div>
									<div class="form-group col-md-12">
										<label>Descripción <span class="icon icon-pencil ColorRequired"></label>
										<textarea name="descripcion" rows="3" class="form-control" required></textarea>
									</div>
								</div>
							    <div class="modal-footer">
							    	<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
							        <button type="submit" class="btn BtnGreen" value="Guardar" onclick="confirm('Seguro que desea continuar?')">Guardar</button>
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
		{{ $creditos->render() }}
	</div>

@stop

@section('js')
	<script>
		$('input').focus(function(){
	        this.select();
	    });
	</script>
@stop
