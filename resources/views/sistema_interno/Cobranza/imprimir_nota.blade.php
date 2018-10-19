@extends('sistema_interno.template.cuerpo')

@section('titulo','Imprimir Nota')

@section('contenido')
	<div class="row">
		<div class="text-center" style="margin-top:10%;margin-bottom:10%;">
			<table class="table table-width table-bordered">
				<thead style="background-color:#9fd1ff;">
					<tr>
						<th><h2><b><center>IMPRIMIR NOTA DE PAGO(S)</center></b></h2></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center">
							<a href="{{route('insuvi')}}" class="btn btn-danger" style="width:100%;font-weight:bold;font-size:25px;">Finalizar</a>
							<a href="{{route('insuvi')}}" class="btn btn-success" style="width:100%;font-weight:bold;font-size:25px;">Generar Nota</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
@stop
