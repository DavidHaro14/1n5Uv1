@extends('sistema_interno.template.cuerpo')

@section('titulo','Reestructura')

@section('head')
	<link rel="stylesheet" href="{{ asset('datetimepicker/css/bootstrap-datepicker.min.css') }}">
@stop

@section('contenido')
	<div class="row">
		<ul class="nav nav-tabs nav-justified" role="tablist">
		  	<li role="presentation">
		  		<a href="#panel-info" aria-controls="panel-info" role="tab" data-toggle="tab">Información</a>
			</li>

		  	<li role="presentation" class="active">
		  		<a href="#panel-cesion" aria-controls="panel-cesion" role="tab" data-toggle="tab">Cesión Derecho</a>
		  	</li>
		</ul>
	</div>
	<div class="tab-content panel-body">
		<div role="tabpanel" class="tab-pane active" id="panel-cesion">
			<form action="{{route('add_cesion')}}" method="POST">
				{{ csrf_field() }}
				<div class="row" style="margin-left:20%;width:60%;">
					<div class="form-group col-md-4">
						<label>Crédito</label>
						<select name="credito" class="SimpleSelect select-credito" required>
							<option value="">Seleccionar</option>
							@foreach($creditos as $credito)
								<option value="{{ $credito->clave_credito}}">{{ $credito->clave_credito}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-8">
						<label>Cedido a</label>
						<select name="nuevo" class="SimpleSelect" required>
							<option value="" id="selected">Seleccionar</option>
							@foreach($clientes as $cliente)
								<option value="{{ $cliente->id_cliente}}" class="clear" id="cliente-{{$cliente->id_cliente}}">{{$cliente->curp . ' - ' . $cliente->nombre . ' ' . $cliente->ape_paterno . ' ' . $cliente->ape_materno }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-12">
						<label>Cliente Actual</label>
						<input type="text" class="form-control" value="Seleccionar Credito" id="cliente_actual" readonly>
					</div>
					<div class="form-group col-md-12">
						<label>Acuerdo</label>
						<textarea name="acuerdo" rows="5" class="form-control" required></textarea>
					</div>
				</div>
				<div class="row">
					<div class="form-group text-center">
						<a type="button" href="{{route('insuvi')}}" class="btn BtnRed"> <span class="icon icon-reply"></span> Cancelar</a>
						<button type="submit" value="Guardar" class="btn BtnGreen"> <span class="icon icon-upload"></span> Guardar</button>
					</div>
				</div>
			</form>
		</div><!-- /Div Panel Credito -->
		<div role="tabpanel" class="tab-pane" id="panel-info">
			<div class="row">
				<table class="table table-hover">
					<tbody class="info-credito"></tbody>
				</table>
			</div><!-- /Div Panel Cliente/Demanda -->
		</div><!-- /Div Panel Info -->
	</div><!-- /Div Panel Body-->

	<!-- Modal Tabla Credito Anterior -->
	<div class="modal fade" id="credit" tabindex="-1" role="dialog" aria-labelledby="head-credit">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title text-center" id="head-credit"><b>Tabla de Pagos</b></h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row col-md-12">
	      		<table class="table table-hover table-corrida"></table>
	      	</div><br>
		  	<div class="text-center"><button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button></div>
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
					$('.clear').prop('disabled',false);
					$.get('/CreditosGet',{clave:valor}).done(function(data){
						//console.log(data);
						$('#cliente-'+data.demanda.cliente_id).prop('disabled',true);
						$('#selected').prop('selected',true);
						$('.SimpleSelect').trigger('chosen:updated');
		      			/******************* CORRIDA *************************/
						var corrida = '';
						corrida += 
						'<thead>'+
		      				'<th class="text-center">Mensualidad</th>'+
		      				'<th>Fecha Vencimiento</th>'+
		      				'<th>Contado</th>'+
		      				'<th>Capital</th>'+
		      				'<th>Interés</th>'+
		      				'<th>Pago Mensual</th>'+
		      				'<th>Saldo</th>'+
		      				'<th>Estatus</th>'+
		      			'</thead>'+
		      			'<tbody>';
		      				$.each(data.mensualidades,function(index,mensualidad){
								corrida += '<tr>'+
									'<td class="text-center">'+mensualidad.no_mensualidad+'</td>'+
									'<td class="text-center">'+mensualidad.fecha_vencimiento+'</td>'+
									'<td>$'+formato_money(Math.round(mensualidad.saldo * 100) / 100)+'</td>'+
									'<td>$'+formato_money(Math.round(mensualidad.capital * 100) / 100)+'</td>'+
									'<td>$'+formato_money(Math.round(mensualidad.interes * 100) / 100)+'</td>'+
									'<td>$'+formato_money(Math.round(data.pago_mensual * 100) / 100)+'</td>'+
									'<td>$'+formato_money(Math.round(mensualidad.resto * 100) / 100)+'</td>'+
									'<td><b>'+mensualidad.estatus+'</b></td>'+
								'</tr>';
		      				});
		      			corrida += '</tbody>';
		      			/******************* INFORMACION *************************/
		      			var info = '';
		      			info += 
		      				'<tr>'+
								'<td colspan="6" class="text-center subtitle"><b>Cliente</b></td>'+
							'</tr>'+
							'<tr>'+
								'<td><strong>Clave Cliente:</strong></td><td>'+pad(data.demanda.cliente.id_cliente,4)+'</td>'+
								'<td><strong>Nombre:</strong></td><td>'+data.demanda.cliente.nombre+' '+data.demanda.cliente.ape_paterno+' '+data.demanda.cliente.ape_materno+'</td>'+
								'<td><strong>CURP:</strong></td><td>'+data.demanda.cliente.curp+'</td>'+
							'</tr>'+
							'<tr>'+
								'<td><strong>Estado de Nacimiento:</strong></td><td>'+data.demanda.cliente.estado_nac+'</td>'+
								'<td><strong>Lugar de Nacimiento:</strong></td><td>'+data.demanda.cliente.lugar_nac+'</td>'+
								'<td><strong>Fecha de Nacimiento:</strong></td><td>'+data.demanda.cliente.fecha_nac+'</td>'+
							'</tr>'+
							'<tr>'+
								'<td colspan="6" class="text-center subtitle"><b>Demanda</b></td>'+
							'</tr>'+
							'<tr>'+
								'<td><strong>Clave Demanda:</strong></td><td>'+pad(data.demanda.id_demanda,4)+'</td>'+
								'<td><strong>Programa:</strong></td><td>'+data.demanda.tipo_programa.programa.nombre+'</td>'+
								'<td><strong>Tipo Programa:</strong></td><td>'+data.demanda.tipo_programa.nombre+'</td>'+
							'</tr>'+
							'<tr>'+
								'<td><strong>Modulo: </strong></td><td>'+(data.demanda.modulo).substring(5)+'</td>'+
								'<td><strong>Tipo de Cliente:</strong></td><td>'+data.demanda.tipo_cliente+'</td>'+
								'<td><strong>Fecha Demanda: </strong></td><td>'+data.demanda.created_at+'</td>'+
							'</tr>'+
							'<tr>'+
								'<td><strong>Observaciones: </strong></td><td colspan="5">'+data.demanda.observaciones+'</td>'+
							'</tr>'+
							'<tr>'+
								'<td colspan="6" class="text-center subtitle"><b>Credito</b></td>'+
							'</tr>'+
							'<tr>'+
		                        '<td><b>Saldo a pagar:</b></td>'+
		                        '<td>$'+formato_money(Math.round(data.total_pagar * 100) / 100)+'</td>'+
		                        '<td><b>Pago mensual:</b></td>'+
		                        '<td>$'+formato_money(Math.round(data.pago_mensual * 100) / 100)+'</td>'+
		                        '<td><b>Total abonado:</b></td>'+
		                        '<td>$'+formato_money(Math.round(data.total_abonado * 100) / 100)+'</td>'+
		                    '</tr>'+
		                    '<tr>'+
		                        '<td><b>Plazo:</b></td>'+
		                        '<td>'+data.plazo+' Meses</td>'+
		                        '<td><b>Fecha inicio:</b></td>'+
		                        '<td>'+data.fecha_inicio+'</td>'+
		                        '<td><b>Enganche:</b></td>'+
		                        '<td>$'+formato_money(Math.round(data.enganche * 100) / 100)+'</td>'+
		                    '</tr>'+
		                    '<tr class="text-center">'+
		                        '<td colspan="6"><button type="button" class="btn btn-xs BtnOrange" data-toggle="modal" data-target="#credit"><span class="icon icon-eye"></span> Ver Mensualidades</button></td>'+
		                    '</tr>';
		                    if(data.lote){
			                    info += '<tr>'+
									'<td colspan="6" class="text-center subtitle"><b>Lote del crédito</b></td>'+
								'</tr>'+
		                        '<tr>'+
		                            '<td><b>Manzana:</b></td>'+
		                            '<td>'+data.lote.no_manzana+'</td>'+
		                            '<td><b>Lote:</b></td>'+
		                            '<td>'+data.lote.no_lote+'</td>'+
		                            '<td><b>Clave Catastral:</b></td>'+
		                            '<td>'+data.lote.clave_catastral+'</td>'+
		                        '</tr>'+
		                        '<tr>'+
		                            '<td><b>Domicilio:</b></td>'+
		                            '<td>'+data.lote.calle+' #'+data.lote.numero+'</td>'+
		                            '<td><b>Fraccionamiento:</b></td>'+
		                            '<td>'+data.lote.fraccionamiento.nombre+'</td>'+
		                            '<td><b>Superficie:</b></td>'+
		                            '<td>'+data.lote.superficie+' M2</td>'+
		                        '</tr>';
		                    }

		                    $('.table-corrida').empty().append(corrida);
		                    $('.info-credito').empty().append(info);
		                    $('#cliente_actual').val(data.demanda.cliente.nombre + " " + data.demanda.cliente.ape_paterno + " " + data.demanda.cliente.ape_materno);
		      			
					});
				} else {
					$('.table-corrida').empty();
                    $('.info-credito').empty().append("<div class='col-md-12 text-center'><b>Seleccionar Crédito</b></div>");
                    $('#cliente_actual').val("Seleccionar Crédito");
				} 
			});	
		});

		function pad(n, length) {

		    var  n = n.toString();

		    while(n.length < length)

		         n = "0" + n;

		    return n;
		    
		}

		function formato_money(money){
		    var v_money = money.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
		    return v_money;
		}

	</script>
@stop