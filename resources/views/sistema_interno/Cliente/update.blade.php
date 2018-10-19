@extends('sistema_interno.template.cuerpo')

@section('titulo','Cambio Domicilio')

@section('contenido')

	<!-- BUSCADORES/FILTROS -->
	<div class="row">
		<form action="{{route('cliente_view_update')}}" method="GET">
			<div class="input-group">
				<div class="input-group-btn">
			   		<select name="tipo" class="btn btn-default size-search" id="tipo">
			   			<option value="curp">CURP</option>
			   			<option value="nombre">Nombre</option>
			   			<option value="domicilio">Domicilio</option>
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
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<th class="text-center">Clave</th>
					<th>Nombre</th>
					<th>CURP</th>
					<th>Domicilio</th>
					<th>Modificar</th>
				</thead>
				<tbody>
					@if(count($clientes) < 1)
						<tr>
							<td colspan="5" class="text-center">NO SE ENCONTRO NINGUN REGISTRO</td>
						</tr>
					@endif
					@foreach($clientes as $cl)
						<tr>
							<th class="text-center"><?php echo str_pad($cl->id_cliente, 4, "0", STR_PAD_LEFT); ?></th>
							<td>{{$cl->nombre . " " . $cl->ape_paterno . " " . $cl->ape_materno}}</td>
							<td>{{$cl->curp}}</td>
							@if($domicilio > 0)
								<td>{{$cl->nom_calle . " #" . $cl->num_ext . ", " . $cl->nom_mun }}</td>
							@else
								<td>{{$cl->calle->nombre . " #" . $cl->num_ext . ", " . $cl->calle->colonia->localidad->municipio->nombre }}</td>
							@endif
							<td class="text-center"><button type="button" class="btn BtnOrange btn-sm" data-toggle="modal" data-target="#editar-{{$cl->id_cliente}}" ><span class="icon-cog"></span></button></td>
						</tr>
						<!-- Modal Editar -->
						<div class="modal fade" id="editar-{{$cl->id_cliente}}" tabindex="-1" role="dialog" aria-labelledby="head-editar">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						      	<h4 class="modal-title" id="head-editar">Cambiar Domicilio</h4>
						      </div>
						      <div class="modal-body">
						        <form action="{{route('cliente_update')}}" method="POST">
						        	<input type="hidden" name="_method" value="PUT">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" value="{{$cl->id_cliente}}" name="clave">
									<div class="form-group">
										<label>Estado</label>
										<select class="SimpleSelect state">
											<option value="" selected hidden>Seleccionar</option>
											@foreach($estados as $est)
												<option value="{{$est->id_estado}}">{{$est->nombre}}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group">
										<label>Municipio</label>
										<select class="SimpleSelect town">
											<option value="" selected hidden>Seleccionar</option>
										</select>
									</div>
									<div class="form-group">
										<label>Localidad</label>
										<select class="SimpleSelect location">
											<option value="" selected hidden>Seleccionar</option>
										</select>
									</div>
									<div class="form-group">
										<label>Colonia/Fraccionamiento</label>
										<select class="SimpleSelect colonie">
											<option value="" selected hidden>Seleccionar</option>
										</select>
									</div>
									<div class="form-group">
										<label>Calle</label>
										<select name="calle" class="SimpleSelect street">
											<option value="" selected hidden>Seleccionar</option>
										</select>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
								            <label>No. Exterior:</label>
								            <input type="text" class="form-control OnlyNumberDomicilio" name="num_ext" value="{{$cl->num_ext}}">
							          	</div>
							          	<div class="form-group col-md-6">
							          		<label>No. Interior:</label>
								            <input type="text" class="form-control OnlyNumberDomicilio" name="num_int" value="{{$cl->num_int}}">
							          	</div>
									</div>
								    <div class="modal-footer">
								    	<button type="button" class="btn BtnRed btn-close-modal" data-dismiss="modal">Cerrar</button>
								        <input type="submit" class="btn BtnGreen" value="Guardar" onclick="return confirm('SEGURO QUE DESEA CONTINUAR?')">
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
		{{ $clientes->render()}}
	</div>
@stop

@section('js')
	<script>
		$(document).ready(function(){
			$('#tipo').change(function(){
				var type = $(this).val();
				if(type == "curp"){
					$('#dato').attr('maxlength','18').val("");
				} else {
					$('#dato').attr('maxlength', false).val("");
				}	
			});
		});
	</script>
	<script>
		$('input').focus(function(){
			$(this).select();
		});

		$('.btn-close-modal').click(function(){
			$('.state, .town, .location, .colonie, .street').val("");
		});

		/*
	    *   Select Estado    -> #state
	    *   Select Municipio -> #town
	    *   Desplegar Municipios Segun el Estado Seleccionado
	    */

	    $('.state').change(function(){
	        var id = $(this).val();
	        $.ajax({
	            url:"MunicipiosGet",
	            type:"GET",
	            dataType:"json",
	            data:{'id':id},
	            // beforeSend:function(){
	            //     $('.town').append('<option selected value=""> Cargando... </option>');
	            //     $('.town').prop('disabled',true);
	            // },
	            success:function(data){
	                //Si data no esta vacio
	                if (data != "") {
	                    //$('.town').prop('disabled',false);
	                    $('.town').empty();
	                    $('.town').append('<option selected value=""> Seleccionar </option>');
	                    $.each(data, function(index, dato){
	                        $('.town').append('<option value="'+dato.id_municipio+'"> '+dato.nombre+' </option>');
	                    });
	                } else {
	                    $('.town').empty();
	                    $('.town').append('<option selected value=""> No se encontro... </option>');
	                }
	                $('.SimpleSelect').trigger('chosen:updated');
	            }
	        });
	    });

	    /*
	    *   Select Municipio -> #town
	    *   Select Localidad -> #location
	    *   Desplegar Localidades Segun el Municipio Seleccionado
	    */

	    $('.town').change(function(){
	        var id = $(this).val();
	        $.ajax({
	            url:"LocalidadesGet",
	            type:"GET",
	            dataType:"json",
	            data:{'id':id},
	            // beforeSend:function(){
	            //     $('.location').append('<option selected value=""> Cargando... </option>');
	            //     $('.location').prop('disabled',true);
	            // },
	            success:function(data){
	                //Si data no esta vacio
	                if (data != "") {
	                    // $('.location').prop('disabled',false);
	                    $('.location').empty();
	                    $('.location').append('<option selected value=""> Seleccionar </option>');
	                    $.each(data, function(index, dato){
	                        $('.location').append('<option value="'+dato.id_localidad+'"> '+dato.nombre+' </option>');
	                    });
	                } else {
	                    $('.location').empty();
	                    $('.location').append('<option selected value=""> No se encontro... </option>');
	                }
	                $('.SimpleSelect').trigger('chosen:updated');
	            }
	        });
	    });

	    /*
	    *   Select Localidad -> #location
	    *   Select Colonia   -> #colonie
	    *   Desplegar Colonias Segun la Localidad Seleccionada
	    */
	    
	    $('.location').change(function(){
	        var id = $(this).val();
	        $.ajax({
	            url:"ColoniasGet",
	            type:"GET",
	            dataType:"json",
	            data:{'id':id},
	            // beforeSend:function(){
	            //     $('.colonie').append('<option selected value=""> Cargando... </option>');
	            //     $('.colonie').prop('disabled',true);
	            // },
	            success:function(data){
	                //Si data no esta vacio
	                if (data != "") {
	                    // $('.colonie').prop('disabled',false);
	                    $('.colonie').empty();
	                    $('.colonie').append('<option selected value=""> Seleccionar </option>');
	                    $.each(data, function(index, dato){
	                        $('.colonie').append('<option value="'+dato.id_colonia+'"> '+dato.nombre+' </option>');
	                    });
	                } else {
	                    $('.colonie').empty();
	                    $('.colonie').append('<option selected value=""> No se encontro... </option>');
	                }
	                $('.SimpleSelect').trigger('chosen:updated');
	            }
	        });
	    });

	    /*
	    *   Select Colonia   -> #colonie
	    *   Select Calle     -> #street
	    *   Desplegar Calles Segun la Colonia Seleccionada
	    */
	    
	    $('.colonie').change(function(){
	        var id = $(this).val();
	        $.ajax({
	            url:"CallesGet",
	            type:"GET",
	            dataType:"json",
	            data:{'id':id},
	            // beforeSend:function(){
	            //     $('.street').append('<option selected value=""> Cargando... </option>');
	            //     $('.street').prop('disabled',true);
	            // },
	            success:function(data){
	                //Si data no esta vacio
	                if (data != "") {
	                    // $('.street').prop('disabled',false);
	                    $('.street').empty();
	                    $('.street').append('<option selected value=""> Seleccionar </option>');
	                    $.each(data, function(index, dato){
	                        $('.street').append('<option value="'+dato.id_calle+'"> '+dato.nombre+' </option>');
	                    });
	                } else {
	                    $('.street').empty();
	                    $('.street').append('<option selected value=""> No se encontro... </option>');
	                }
	                $('.SimpleSelect').trigger('chosen:updated');
	            }
	        });
	    });
	</script>
@stop