@extends('sistema_interno.template.cuerpo')

@section('titulo','Solicitud Eliminación')

@section('contenido')
	<div class="row">
		<h4 class="BtnGray text-center" style="padding:8px;"><b>Solicitud para Eliminación de una Reestructura</b></h4>
	</div>
	<div class="row table-width">
		<form action="{{route('add_solicitud')}}" method="POST">
			{{csrf_field()}}
			<div class="form-group col-md-5">
				<label>Crédito</label>
				<select name="credito" class="form-control select-credito" required>
					<option value="">Seleccionar</option>
					@foreach($creditos as $credito)
						<option value="{{$credito->clave_credito}}">{{$credito->clave_credito}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-md-4">
				<label>Usuario</label>
				<input type="text" class="form-control" value="VERO" name="usuario" readonly>
			</div>
			<div class="form-group col-md-3">
				<label>Fecha</label>
				<input type="text" class="form-control" value="{{date('d-m-Y')}}" readonly>
			</div>
			<div class="form-group col-md-12">
				<label>Concepto</label>
				<textarea type="text" class="form-control" name="concepto" rows="4" required></textarea>
			</div>
			<div class="col-md-12 text-center">
				<a class="btn BtnOrange btn-view"><span class="icon icon-eye"></span> Crédito Anterior</a>
				<a href="{{route('insuvi')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Cancelar</a>
				<button type="submit" class="btn BtnGreen"><span class="icon icon-upload3"></span> Enviar Solicitud</button>
			</div>
		</form>
	</div>

	<!-- Modal Solicitud -->
	<div class="modal fade" id="Solicitud" tabindex="-1" role="dialog" aria-labelledby="head-Solicitud">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title" id="head-Solicitud">Crédito Anterior</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="error text-center"></div>
	      </div>
	      <div class="modal-footer">
	      	<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>
@stop

@section('js')
	<script>
		$(document).ready(function(){
			$('.select-credito').change(function(){
				var valor = $(this).val();
				if(valor != ""){
					$.get('/GetCreditoAnterior',{id:valor}).always(function(){
						//alert("enviando");
					}).done(function(data){
						if(data == "NO"){
							$('.error').empty().append('<h4 style="color:red;"><b>No existe ningun credito anterior para activar <br> No se puede enviar una solicitud de eliminacion si no existe un credito anterior</b></h4>');
							$('.btn-view').removeAttr('href target');
							$('#Solicitud').modal('show');
							$('button[type="submit"]').prop('disabled',true);
						} else {
							$('.error').empty();
							$('.btn-view').attr({
								href:"/pdf_credito/"+data.clave_credito+"/"+data.plantilla+"/1",
								target:"_blank"
							});
							$('button[type="submit"]').prop('disabled',false);
						}
					});
				} else {
					$('.btn-view').removeAttr('href target');
				}
			});
		});	
	</script>
@stop