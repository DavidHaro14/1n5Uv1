@extends('sistema_interno.template.cuerpo')

@section('titulo','Clave Credito')

@section('contenido')
	<div class="row">
		<div class="text-center" style="margin-top:7%;margin-bottom:10%;">
			<table class="table table-width">
				<thead class="BtnGray">
					<tr>
						<th><h2><b><center>CLAVE DEL CREDITO</center></b></h2></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><h1><b>{{$credito->clave_credito}}</b></h1></td>
					</tr>
					<tr>
						<td class="text-center">
							<a href="{{route('insuvi')}}" class="btn BtnRed" style="width:40%;">Finalizar</a>
							<a href="<?php echo route('pdf_credito',['credito'=>$credito->clave_credito,'plantilla'=>$credito->plantilla])?>" class="btn BtnGreen" style="width:40%;"><span class="icon icon-file-pdf"></span> Documento</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

@stop
