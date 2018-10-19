@extends('sistema_interno.template.cuerpo')

@section('titulo','Seguimientos')

@section('contenido')
	
@include('errors.errores')
	
	<div class="row">
		<div class="col-md-6">
			<a href="{{route('seguimiento')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-6 text-right">
			<button class="btn BtnGreen" data-toggle="modal" data-target="#seguimiento"><span class="icon icon-plus"></span> Nuevo</button>
		</div>
	</div>
	<br>
	<div class="row">
		<table class="table table-hover">
			<thead class="BtnGray">
				<tr>
					<th>Situación</th>
					<th>Descripción</th>
					<th>Restricción</th>
					<th>Estatus</th>
					<th class="text-center">Opciones</th>
				</tr>
			</thead>
			<tbody class="tbody">
				@if(count($seguimientos) < 1)
					<tr>
						<td colspan="6"><b>No se encontró ningún seguimiento</b></td>
					</tr>
				@endif

				@foreach($seguimientos as $seg)
				<tr>
					<td>{{ $seg->situacion->situacion }}</td>
					<td>{{ $seg->descripcion_seguimiento }}</td>
					<td><?php echo $seg->restriccion . " "; if($seg->restriccion == "CONVENIO") echo "$".number_format($seg->monto_convenio,2); ?></td>
					@if($seg->estatus_seguimiento)
						<td><span class="label label-success BtnGreen">Activo</span></td>
					@else
						<td><span class="label label-danger BtnRed">Inactivo</span></td>
					@endif
					<td class="text-center">
						@if($seg->estatus_seguimiento)
							<form action="{{route('seguimiento_estatus')}}" method="POST" style="display:inline-block;">
					        	<input type="hidden" name="_method" value="PUT">
    							<input type="hidden" name="_token" value="{{ csrf_token() }}">
    							<input type="hidden" name="id" value="{{$seg->id_seguimiento}}">
    							<input type="hidden" name="restriccion" value="{{$seg->restriccion}}">
								<button class="btn BtnRed btn-xs" type="submit" <?php if($seg->restriccion == "CONVENIO" && ($seg->convenio_pagado == "0" && $seg->abonado_convenio > 0)) echo 'disabled'?>><span class="icon icon-bin"></span> Quitar</button>
							</form>
							@if($seg->restriccion == "CONVENIO" && $seg->convenio_pagado == "0")
								<button type="button" class="btn BtnOrange btn-xs" data-toggle="modal" data-target="#update_convenio-{{$seg->id_seguimiento}}"><span class="icon icon-pencil"></span> Monto</button>
							@endif

							<!-- Update Convenio -->
							<div class="modal fade" id="update_convenio-{{$seg->id_seguimiento}}" tabindex="-1" role="dialog" aria-labelledby="head-update">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							      	<h4 class="modal-title text-center" id="head-update"><b>Nuevo Monto Convenio</b></h4>
							      </div>
							      <div class="modal-body">
							        <form action="{{route('update_convenio',$seg->id_seguimiento)}}" method="POST">
							        	<input type="hidden" name="_token" value="{{csrf_token()}}">
							        	<input type="hidden" name="_method" value="PUT">
						        		<div class="form-group">
						        			<input type="number" class="form-control" value="{{$seg->monto_convenio - $seg->abonado_convenio}}" min="{{$seg->abonado_convenio}}" name="monto" required>
						        		</div>
						        		<div class="modal-footer">
						        			<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
						        			<button type="submit" class="btn BtnGreen">Guardar</button>
						        		</div>
							        </form>
							      </div>
							    </div>
							  </div>
							</div>
						@endif
					</td>
				</tr>
				
				@endforeach
			</tbody>
		</table>
		{{ $seguimientos->render() }}
	</div>

	<!-- Seguimiento -->
	<div class="modal fade" id="seguimiento" tabindex="-1" role="dialog" aria-labelledby="head-seguimiento">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title text-center" id="head-seguimiento"><b>Nuevo Seguimiento</b></h4>
	      </div>
	      <div class="modal-body">
	        <form action="{{route('seguimiento_add')}}" method="POST">
	        	{{ csrf_field() }}
	        	<input type="hidden" name="credito" value="{{$clave}}">
        		<div class="form-group">
        			<label>Situación</label>
        			<select name="situacion" class="form-control" required>
        				<option value="">Seleccionar</option>
        				@foreach($situaciones as $situacion)
        					<option value="{{$situacion->id_situacion}}">{{$situacion->situacion}}</option>
        				@endforeach
        			</select>
        		</div>
        		<div class="form-group">
        			<label>Monto Convenio</label>
        			<input type="text" class="form-control onlynumber" value="0" name="monto" readonly required>
        		</div>
        		<div class="form-group">
        			<label>Descripción</label>
        			<textarea name="descripcion" class="form-control text-uppercase" onKeyUp="this.value=this.value.toUpperCase();" rows="5" required></textarea>
        		</div>
        		<div class="row">
	        		<div class="col-md-4">
		        		<label>
		        			<input type="radio" value="CONVENIO" name="restriccion" required <?php if($convenio || $congelacion){ echo "disabled";}?>>Convenio
		        		</label>
	        		</div>
	        		<div class="col-md-4">
		        		<label>
		        			<input type="radio" value="NO DESCUENTO" name="restriccion" required <?php if($descuento){ echo "disabled";}?>>No Descuento
		        		</label>
	        		</div>
	        		<div class="col-md-4">
		        		<label>
		        			<input type="radio" value="CONGELACION" name="restriccion" required <?php if($congelacion || $convenio){ echo "disabled";}?>>Congelación
		        		</label>
	        		</div>
        		</div>
    			@if($convenio && $descuento && $congelacion && $cancelacion)
        			<p style="font-size:15px;color:red;text-align:center;margin-top:5px;"><b>No se puede tener mas de 4 seguimientos activos</b></p>
        		@endif
        		<br>
        		<div class="modal-footer">
        			<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
        			<button type="submit" class="btn BtnGreen" <?php if($convenio && $descuento && $congelacion && $cancelacion){ echo "disabled";}?>>Guardar</button>
        		</div>
	        </form>
	      </div>
	    </div>
	  </div>
	</div>

@stop

@section('js')
	<script>
		$('input,textarea').focus(function(){
	        this.select();
	    });

	    //Solo numerico y punto
	    $('.onlynumber').keydown(function (e) {
	        // Disponible: borrar, Enter
	        if ($.inArray(e.keyCode, [110, 190, 9, 8, 13, 109]) !== -1 ||
	             // Disponible: inicio, fin, izquierda, derecha
	            (e.keyCode >= 37 && e.keyCode <= 40)) {
	                 return;
	        }
	        // Solo usar teclas numericas
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();//retornar
	        }
	    });

		$('input[name="restriccion"]').change(function(){
			var restriccion = $(this).val();
			(restriccion != "CONVENIO") ? $('input[name="monto"]').prop('readonly',true).val(0) : $('input[name="monto"]').prop('readonly',false);
		});
	</script>
@stop
