@extends('sistema_interno.template.cuerpo')

@section('titulo','Clave Ahorrador')

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
						<td><h1><b>{{$ahorrador->clave_ahorrador}}</b></h1></td>
					</tr>
					<tr>
						<td class="text-center">
							<a href="{{route('insuvi')}}" class="btn BtnRed" style="width:50%;font-weight:bold;">Finalizar</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
@stop