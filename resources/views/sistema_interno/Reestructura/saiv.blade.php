@extends('sistema_interno.template.cuerpo')

@section('titulo','Reestructura')

@section('head')
	<link rel="stylesheet" href="{{ asset('datetimepicker/css/bootstrap-datepicker.min.css') }}">
	<style>
		h3{
			margin-top: -10px;
		}
		.proceso, .procesoLote{
			display: none;
		}
	</style>
@stop

@section('contenido')
	<div role="tabpanel" class="tab-pane" id="panel-credito">
		<div class="row">
			<h4 class="BtnGray" style="padding:8px;" data-toggle="collapse" data-target="#CollapseDemanda" aria-expanded="false" aria-controls="CollapseDemanda"><span class="icon icon-circle-down"></span> <b style="margin-left:45%;">Demanda</b></h4>
		</div>
		<div id="CollapseDemanda" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
			<div class="row">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
				<div class="form-group col-md-3">
					<label>Solicitante</label>
					<select class="SimpleSelect camp_dem" id="solicitante" required>
						<option value="">Seleccionar</option>
						@foreach($clientes as $cl)
							<option value="{{$cl->id_cliente}}">{{$cl->curp . " - " .$cl->nombre . " " . $cl->ape_paterno . " " . $cl->ape_materno}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Programa</label>
					<select class="SimpleSelect camp_dem" id="programa" required>
						<option value="">Seleccionar</option>
						@foreach($programas as $pr)
							<option value="{{$pr->id_programa}}">{{$pr->nombre}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Tipo de Programa</label>
					<select class="SimpleSelect camp_dem" id="TiposProgramas" required>
						<option value="">Seleccionar</option></select>
				</div>
				<div class="form-group col-md-3">
					<label>Modulo</label>
					<select class="SimpleSelect camp_dem" id="modulo" required>
						<option value="">Seleccionar</option>
						@foreach($modulos as $md)
							<option value="{{$md}}">{{$md}}</option>
						@endforeach
					</select>
				</div>
				<!-- <div class="form-group col-md-3">
					<label>Enganche</label>
					<input type="text" class="form-control camp_dem" required value="0" id="enganche">
				</div> -->
				@if(count($demandas) > 0)
					<div class="form-group col-md-6">
						<label>Demandas Pendientes a Reestructurar</label>
						<select id="select-demanda" class="SimpleSelect">
							<option value="">Seleccionar</option>
							@foreach($demandas as $demanda)
								<option value="{{$demanda->id_demanda}}">{{$demanda->nombre . " " . $demanda->ape_paterno . " " . $demanda->ape_materno . " - " . $demanda->tipo }}</option>
							@endforeach
						</select>
					</div>
				@endif
				<div class="form-group col-md-12 text-center">
					<button type="button" class="btn BtnOrange btn-continuar" onclick="continuar_proceso()" style="margin-top:25px;"><span class="icon icon-arrow-right"></span> Crear Demanda</button>
				</div>
			</div>
		</div>
		<form action="{{route('reestructura_add',true)}}" method="POST">
			<input type="hidden" value="0" id="h_ca_venc">
			<input type="hidden" value="0" id="h_fi_venc">
			<input type="hidden" value="0" id="h_mo_venc">
			<input type="hidden" value="0" id="h_total_pagar">
			<input type="hidden" value="ME" id="siglas">
			<input type="hidden" name="id_demanda" value="0" id="id_demanda">
			<!-- <input type="hidden" name="plantilla_demanda" value="0" id="plantilla_demanda"> -->
			{{ csrf_field() }}
			<div class="row procesoLote">
				<h4 class="BtnGray" style="padding:8px;" data-toggle="collapse" data-target="#CollapseLote" aria-expanded="false" aria-controls="CollapseLote"><span class="icon icon-circle-down"></span> <b style="margin-left:45%;">Datos Lote</b></h4>
			</div>
			<div id="CollapseLote" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="row procesoLote">
					<div class="form-group col-md-3">
						<label>Estado</label>
						<select class="SimpleSelect" id="state" required>
							<option value="">Seleccionar</option>
							@foreach($estados as $est)
								<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Municipio</label>
						<select class="SimpleSelect" id="town" required>
							<option value="">Seleccionar</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Localidad</label>
						<select class="SimpleSelect" id="location" required>
							<option value="">Seleccionar</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Fraccionamiento</label>
						<select name="fraccionamiento" class="SimpleSelect" id="colonie" required>
							<option value="">Seleccionar</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Manzana</label>
						<select class="SimpleSelect" id="manzana" required>
							<option value="">Seleccionar</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Lote</label>
						<select name="lote_saiv" id="lote" class="SimpleSelect" required>
							<option value="0">Seleccionar</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Superficie M2</label>
						<input type="text" class="form-control readonly" value="0" id="superficie">
					</div>
					<div class="form-group col-md-3">
						<label>Costo por Metro Cuadrado <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="1" step="0.01" class="form-control" name="metro_cuadrado" value="0" id="costo_cuadrado">
					</div>
					<div class="form-group col-md-3">
						<label>Costo Terreno <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="1" step="0.01" class="form-control" name="costo_terreno" value="0" id="costo_terreno" >
					</div>
					<div class="form-group col-md-3">
						<label>Costo Construcción <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="1" step="0.01" class="form-control" name="costo_construccion" value="0" id="costo_construccion">
					</div>
					<div class="col-md-3 text-center" style="margin-top:25px;">
						<button type="button" class="btn BtnOrange btn-viewlote" data-target="#viewlote" data-toggle="modal" disabled><span class="icon icon-eye"></span> Ver Lote</button>
					</div>
				</div>
			</div>
			<div class="row proceso">
				<h4 class="BtnGray" style="padding:8px;" data-toggle="collapse" data-target="#CollapseSaiv" aria-expanded="false" aria-controls="CollapseSaiv"><span class="icon icon-circle-down"></span> <b style="margin-left:45%;">Crédito SAIV</b></h4>
			</div>
			<div id="CollapseSaiv" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="row proceso">
					<div class="form-group col-md-3">
						<label>No. Total Reestructuras <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="0" class="form-control" value="0" name="cantidad" id="cantidad" required>
					</div>
					<div class="form-group col-md-3">
						<label>Capital Vencido <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="1" step="0.01" class="form-control" required value="0" id="ca_venc">
					</div>
					<div class="form-group col-md-3">
						<label>Financiamiento Vencido <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="1" step="0.01" class="form-control" required value="0" id="fi_venc">
					</div>
					<div class="form-group col-md-3">
						<label>Moratorios Vencido <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="0" step="0.01" class="form-control" value="0" id="mo_venc">
					</div>
					<!-- <div class="form-group col-md-3">
						<label>Intereses Vencido <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="number" min="0" class="form-control" required value="0" id="int_venc">
					</div> -->
					<div class="form-group col-md-3">
						<label>Clave Crédito <span class="icon icon-pencil ColorRequired"></span></label>
						<input type="text" class="form-control" name="vieja_clave" id="vieja_clave" required>
					</div>
					<div class="form-group col-md-3">
						<label>Descuento Capital <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" min="0" step="0.01" class="form-control" value="0" id="ca_desc">
							<span class="input-group-addon">$</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Descuento Financiamiento <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" min="0" step="0.01" class="form-control" value="0" id="fi_desc">
							<span class="input-group-addon">$</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Descuento Moratorios <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" min="0" step="0.01" class="form-control" value="0" id="mo_desc">
							<span class="input-group-addon">$</span>
						</div>
					</div>
				</div>
				<div class="row proceso">
					<div class="col-md-12">
						<p><b>Fecha Reestructuras SAIV:</b></p>
					</div>
					<div class="FechasSaiv">
						<div class="col-md-12">
							<p>No hay reestructuras</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row proceso">
				<h4 class="BtnGray" style="padding:8px;" data-toggle="collapse" data-target="#CollapseCredito" aria-expanded="false" aria-controls="CollapseCredito"><span class="icon icon-circle-down"></span> <b style="margin-left:45%;">Nuevo Crédito</b></h4>
			</div>
			<div id="CollapseCredito" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="row proceso">
					<div class="form-group col-md-3">
						<label>Monto a Reestructurar</label>
						<input type="text" class="form-control readonly" value="0" name="total_reestructurar" id="to_rees">
					</div>
					<div class="form-group col-md-3">
						<label>Tasa de Interés Anual <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" min="0" class="form-control" required name="tasa" value="0" id="tasa">
							<span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Plazo <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" min="1" class="form-control" required name="plazo" value="0" id="plazo">
							<span class="input-group-addon">Meses</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Moratorio <span class="icon icon-pencil ColorRequired"></span></label>
						<div class="input-group">
							<input type="number" min="0" class="form-control" required name="moratorio" value="0">
							<span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Costo de Contado</label>
						<input type="text" class="form-control readonly" required name="costo_contado" value="0" id="costo_contado">
					</div>
					<div class="form-group col-md-3">
						<label>Costo Financiamiento</label>
						<input type="text" class="form-control readonly" required name="costo_finan" value="0" id="financiamiento">
					</div>
					<div class="form-group col-md-3">
						<label>Pago Mensual</label>
						<input type="text" class="form-control readonly" required name="pago_mensual" value="0" id="mensualidad">
					</div>
					<div class="form-group col-md-3">
						<label>Total a Pagar</label>
						<input type="text" class="form-control readonly" required name="pago_total" value="0" id="pago_total">
					</div>
					<div class="form-group col-md-3">
						<label>Fecha Inicio <span class="icon icon-pencil ColorRequired"></span></label>
						<div class='input-group date d_inicio' id="picker-container">
							<input type="text" class="form-control f_inicio readonly" required name="fecha_inicio">
		                    <span class="input-group-addon">
		                        <span class="icon icon-calendar"></span>
		                    </span>
		                </div>
					</div>
					<div class="form-group col-md-3">
						<label>Clave Crédito</label>
						<input type="text" class="form-control readonly" name="nueva_clave" id="nueva_clave" required>
					</div>
					<div class="form-group col-md-3">
						<label>Tabla de cobros</label>
						<select name="tabla_cobros" id="t_cobros" class="form-control">
							<option value="4">TABLA 4</option>
							<option value="5">TABLA 5</option>
						</select>
					</div>
					<div class="col-md-3" style="margin-top:25px;">
						<center><button class="btn BtnOrange btn-corrida" type="button" data-toggle="modal" data-target="#corrida"><span class="icon icon-table"></span> Tabla Corrida</button></center>
					</div>
				</div>
				<div class="row proceso">
					<div class="form-group col-md-12">
						<label>Observaciones (Opcional)</label>
						<textarea name="observaciones" class="form-control" rows="4">NO HAY OBSERVACIONES</textarea>
					</div>
				</div>
				<div class="row proceso">
					<div class="form-group text-center">
						<a type="button" href="{{route('insuvi')}}" class="btn BtnOrange"><span class="icon icon-clock"></span> Dejar Pendiente</a>
						<button type="button" onclick="eliminar_demanda()" class="btn BtnRed"><span class="icon icon-reply"></span> Cancelar</button>
						<button type="submit" value="Guardar" class="btn BtnGreen"><span class="icon icon-upload"></span> Guardar</button>
					</div>
				</div>
			</div>
		</form>
	</div><!-- /Div Panel Credito -->

	<!-- Modal Corrida -->
	<div class="modal fade" id="corrida" tabindex="-1" role="dialog" aria-labelledby="head-corrida">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title" id="head-corrida">Tabla de Pagos</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row col-md-12" id="mostrar_corrida"></div><br>
		  	<div class="text-center"><button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button></div>
		  </div>
		</div>
 	  </div>
	</div>

	<!-- Modal Lote -->
	<div class="modal fade" id="viewlote" tabindex="-1" role="dialog" aria-labelledby="head-lote">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title" id="head-lote">Información Lote</h4>
	      </div>
	      <div class="modal-body">
	      	<table class="table table-hover">
	      		<tbody id="tbody_lote"></tbody>
	      	</table><br>
		  	<div class="text-center"><button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button></div>
		  </div>
		</div>
 	  </div>
	</div>
@stop

@section('js')
	<script src="{{ asset('datetimepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('datetimepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
	<script src="{{ asset('js/reestructura_saiv.js') }}"></script>
	
	<script>
		$('#lote').change(function(){
			var lote = $(this).val();
			if(lote != 0){
				$.ajax({
			        url: "/GetSiglasFraccionamiento",
			        type: 'GET',
			        async: false,
			        dataType: 'json',
			        data: {
			        	id:lote
			        },
			        success:function(sigla){
						$('#siglas').val(sigla);
			        }
			    });
			} else {
				$('#siglas').val("ME");
			}
			new_clave();
		});

		$('#select-demanda').change(function(){
			var demanda = $(this).val();

			$.ajax({
		        url: "/GetPlantillaDemanda",
		        type: 'GET',
		        async: false,
		        dataType: 'json',
		        data: {
		        	id:demanda
		        },
		        success:function(demanda){
		            if(demanda.plantilla === "1"){
    				$('.procesoLote').show();
	    			}
					$('#modulo').val(demanda.modulo);
					$('#TiposProgramas').append('<option value="'+demanda.tipo_programa_id+'" selected hidden>'+demanda.tipo_programa.nombre+'</option>');
					$('#solicitante').val(demanda.cliente_id);
					$('#programa').val(demanda.tipo_programa.programa_id);
		        }
		    });
			$('#id_demanda').val(demanda);
			$('.camp_dem,#select-demanda').prop('disabled',true).trigger('chosen:updated');
            //$('.SimpleSelect').prop('disabled',true).trigger('chosen:updated');
        	$('.proceso').fadeIn();
        	$('.btn-continuar').hide();
        	new_clave();		
        });

		$('.camp_dem,#cantidad,#vieja_clave').change(function(){
	        new_clave();
	    });

		var date   = new Date();
		
      	$('.d_inicio').datepicker({
		    format: 'yyyy-mm-dd',
		    autoclose:true,
		    language:'es',
		    startDate: date,
		    clearBtn: true,
		    container: "#picker-container",
		    title:"Inicio"
		});

      	/*
	        Funciones Onclick
	    */

      	function continuar_proceso(){
	        var mensaje   = "Es importante verificar los datos seleccionados:\rSi continuas no existirá modificaciones mas adelante.";
	        var respuesta = confirm(mensaje);
	        var camp_empty = 0;
	        $('.camp_dem').each(function(){
	        	if($(this).val() == ""){camp_empty++;}
	        });
	        if(respuesta === true && camp_empty < 1){
	        	$('.camp_dem').prop('disabled',true).trigger('chosen:updated');
            	//$('.SimpleSelect').prop('disabled',true).trigger('chosen:updated');
	        	$('.proceso').fadeIn();
	        	$('.btn-continuar').hide();
	        	demanda_create();
	        } else if(respuesta === false){
	        	return false;
	        } else if(camp_empty > 0){
	        	alert('Quedo un campo sin seleccionar');
	        }
	    }

	    /*
        Funciones Ajaxs
	    */

	    function demanda_create(){
	    	var solicitante = $('#solicitante').val();
	    	var tipo 	 	= $('#TiposProgramas').val();
	    	var modulo   	= $('#modulo').val();
	    	//var enganche 	= $('#enganche').val();
	    	var token 		= $('#token').val();

	        $.ajax({
	        	url:"/SetDemandaSaiv",
	        	headers: {'X-CSRF-TOKEN': token},
	        	type:"POST",
	        	dataType:"json",
	        	data:{
	        		solicitante:solicitante,
	        		tipo:tipo,
	        		modulo:modulo
	        	},
	        	success:function(data){
	        		if(data != ""){
	        			console.log(data);
	        			$('#id_demanda').val(data.id_demanda);
	        			//$('#plantilla_demanda').val(data.plantilla);
	        			if(data.plantilla === "1"){
	        				$('.procesoLote').show();
	        			}
	        			new_clave();
	        		} else {
	        			alert('Error al registrar la demanda... vuelva a cargar la pagina...')
	        		}
	        	}
	        });
	    }

	    function eliminar_demanda(){
	    	var id = $('#id_demanda').val();
	    	
	    	$.get('/SetDeleteDemanda/'+id);

	    	window.location.href = "<?php echo route('insuvi') ?>";
	    }
	    /*
	        FUNCION PARA GENERAR CLAVE NUEVA
	    */

	    function new_clave(){
	        var modulo   = $('#modulo').val();
	        var programa = $('#programa').val();
	        var tipo     = $('#TiposProgramas').val();
	        var demanda  = $('#id_demanda').val();
	        var cantidad = $('#cantidad').val();
	        var siglas	 = $('#siglas').val();
	        //var siglas	 = $('#vieja_clave').val();

	        //var nueva = modulo.substr(0,2) + pad(programa,2) + pad(tipo,2) + pad(demanda,5) + siglas.substr(-2) + "-RE" + (parseFloat(cantidad)+parseFloat(1));
	        var nueva = modulo.substr(0,2) + pad(programa,2) + pad(tipo,2) + pad(demanda,5) + siglas + "-RE" + (parseFloat(cantidad)+parseFloat(1));
	        //alert(nueva);
	        $('#nueva_clave').val(nueva);
	    }

	    /*
	        function -> para agregar ceros a la izquierda (Javascript)
	    */
	    function pad(n, length) {

	        var  n = n.toString();

	        while(n.length < length)

	             n = "0" + n;

	        return n;
	        
	    }
	</script>
@stop
