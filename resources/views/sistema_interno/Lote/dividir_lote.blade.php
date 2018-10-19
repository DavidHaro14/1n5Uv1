@extends('sistema_interno.template.cuerpo')

@section('titulo','Lotes')

@section('contenido')

	@include('errors.errores')

	<div class="row">
		<div class="col-md-12 text-center"><h4><b>Lote</b></h4></div>
		<form action="{{route('add_division',$lote->id_lote)}}" method="POST">
			{{csrf_field()}}
			<div class="row">
				<div class="form-group col-md-3">
					<label>Domicilio</label>
					<input type="text" class="form-control" value="{{$lote->calle . ' #' . $lote->numero}}" readonly>
				</div>
				<div class="form-group col-md-1">
					<label>Letra</label>
					<input type="text" class="form-control" name="letra" value="B" maxlength="1" required>
				</div>
				<div class="form-group col-md-2">
					<label>Fraccionamiento</label>
					<input type="text" class="form-control" value="{{$lote->fraccionamiento->nombre}}" readonly>
				</div>
				<div class="form-group col-md-2">
					<label>Clave Catastral</label>
					<input type="text" class="form-control" name="clave_catastral" value="">
				</div>
				<div class="form-group col-md-2">
					<label>Uso Suelo</label>
					<input type="text" class="form-control" name="uso_suelo" value="{{$lote->uso_suelo}}" required>
				</div>
				<div class="form-group col-md-1">
					<label>Manzana</label>
					<input type="text" class="form-control" value="{{$lote->no_manzana}}" readonly>
				</div>
				<div class="form-group col-md-1">
					<label>Lote</label>
					<input type="text" class="form-control" value="{{$lote->no_lote}}" readonly>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-4">
					<label>Norte</label>
					<input type="text" class="form-control" name="norte" value="{{$lote->norte}}">
				</div>
				<div class="form-group col-md-4">
					<label>Sur</label>
					<input type="text" class="form-control" name="sur" value="{{$lote->sur}}">
				</div>
				<div class="form-group col-md-4">
					<label>Este</label>
					<input type="text" class="form-control" name="este" value="{{$lote->este}}">
				</div>
				<div class="form-group col-md-4">
					<label>Oeste</label>
					<input type="text" class="form-control" name="oeste" value="{{$lote->oeste}}">
				</div>
				<div class="form-group col-md-4">
					<label>Noreste</label>
					<input type="text" class="form-control" name="noreste" value="{{$lote->noreste}}">
				</div>
				<div class="form-group col-md-4">
					<label>Suroeste</label>
					<input type="text" class="form-control" name="suroeste" value="{{$lote->suroeste}}">
				</div>
				<div class="form-group col-md-4">
					<label>Noroeste</label>
					<input type="text" class="form-control" name="noroeste" value="{{$lote->noroeste}}">
				</div>
				<div class="form-group col-md-4">
					<label>Sureste</label>
					<input type="text" class="form-control" name="sureste" value="{{$lote->sureste}}">
				</div>
				<div class="form-group col-md-2">
					<label>Superficie Actual</label>
					<div class="input-group">
						<input type="text" class="form-control" name="superficie_actual" value="{{$lote->superficie}}" readonly>
						<div class="input-group-addon">
							<span>M2</span>
						</div>
					</div>
				</div>
				<div class="form-group col-md-2">
					<label>Nueva Superficie</label>
					<div class="input-group">
						<input type="number" class="form-control" name="nueva_superficie" value="96" min="96" max="<?php echo $lote->superficie - 96 ?>" required step="0.01">
						<div class="input-group-addon">
							<span>M2</span>
						</div>
					</div>
				</div>
				<div class="form-group col-md-2">
					<label>Ochavo</label>
					<input type="text" class="form-control" name="ochavo" value="{{$lote->ochavo}}">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<a href="{{route('lote_index')}}" class="btn BtnRed">Cancelar</a>
					<button type="submit" class="btn BtnGreen">Guardar</button>
				</div>
			</div>
		</form>
	</div>

@stop

<!-- PENDIENTE CALCULAR MINIMO O MAXIMO DE DIVISION DE LOTE -->