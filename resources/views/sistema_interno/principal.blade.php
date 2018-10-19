@extends('sistema_interno.template.cuerpo')

@section('titulo','Insuvi')

@section('head')
	<style>
		.StylePanel{
			border-radius:0px;
			-webkit-box-shadow: 10px 9px 14px -7px rgba(0,0,0,0.67);
			-moz-box-shadow: 10px 9px 14px -7px rgba(0,0,0,0.67);
			box-shadow: 10px 9px 14px -7px rgba(0,0,0,0.67);
		}
	</style>
@stop

@section('contenido')

	@include('errors.errores')
	
	<?php 
		$v_modulo = Auth::user()->modulos;
		$admon 	  = str_contains($v_modulo,'ADMON');
	?>

	<div class="row">
        <div class="col-md-12">
	        <div style="background-image:url('img/logo2.png');background-repeat:no-repeat;background-position:center;">
				@if($admon)
	        		<div class="panel panel-default col-md-5 StylePanel">
	        			<div class="panel-body">
	        				<div class="row">
	        					<div class="col-md-12 text-center"><h4><b>Limite Reestructurar</b></h4></div>
	        					<div class="col-md-2"><b>Monto:</b></div><div class="col-md-4"><p>${{number_format($datos->limite_monto,2)}}</p></div>
	        					<div class="col-md-2"><b>Fecha: </b></div><div class="col-md-4"><p>{{$datos->fecha_limite}}</p></div>
	        					<div class="col-md-3"><b>Porcentaje: </b></div> <div class="col-md-3"> <p>{{$datos->limite_porcentaje}}%</p></div>
	        					<div class="col-md-12 text-center" data-toggle="modal" data-target="#CambiarLimite"><button class="btn BtnOrange"><span class="icon icon-pencil"></span> Cambiar</button></div>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="panel panel-default col-md-offset-2 col-md-5 StylePanel">
	        			<div class="panel-body">
	        				<div class="row">
	        					<div class="col-md-12 text-center"><h4><b>Director General</b></h4></div>
	        					<div class="col-md-3"><b>Nombre: </b></div><div class="col-md-9">{{$datos->director}} </p></div>
	        					<div class="col-md-3"><b>Fecha: </b></div><div class="col-md-9">{{$datos->fecha_director}} </p></div>
	        					<div class="col-md-12 text-center" data-toggle="modal" data-target="#CambiarDirector"><button class="btn BtnOrange"><span class="icon icon-pencil"></span> Cambiar</button></div>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="panel panel-default col-md-5 StylePanel">
	        			<div class="panel-body">
	        				<div class="row">
	        					<div class="col-md-12 text-center"><h4><b>Solicitud para Eliminación de Créditos Reestructurados</b></h4></div>
	        				</div>
	        				<div class="row">
	        					<div class="col-md-12 text-center"><b><span class="badge" style="background-color:rgb(250,105,0);">{{$solicitudes->count()}}</span> Créditos</b></div>
	        					<br><br>
	        					<div class="col-md-12">
	        						<div class="col-md-5"><b>Crédito</b></div>
	        						<div class="col-md-5 text-center"><b>Usuario</b></div>
	        						<div class="col-md-2 text-center"><b>Solicitud</b></div>
	        					</div>
	        					@if(count($solicitudes) < 1)
		        					<div class="col-md-12 text-center" style="border-top: 1px solid rgba(0,0,0,0.5);padding:8px;">
		        						<b>No hay ninguna solicitud</b>
		        					</div>
	        					@endif
	        					@foreach($solicitudes as $solicitud)
		        					<div class="col-md-12" style="border-top: 1px solid rgba(0,0,0,0.5);padding:8px;">
		        						<div class="col-md-5">{{$solicitud->credito}}</div>
		        						<div class="col-md-5 text-center">{{$solicitud->usuario}}</div>
		        						<div class="col-md-2 text-center"><button class="btn BtnOrange btn-xs" data-toggle="modal" data-target="#Solicitud-{{$solicitud->id_solicitud}}"><span class="icon icon-upload"></span> </button></div>
		        					</div>

		        					<!-- Modal Solicitud -->
									<div class="modal fade" id="Solicitud-{{$solicitud->id_solicitud}}" tabindex="-1" role="dialog" aria-labelledby="head-Solicitud">
									  <div class="modal-dialog" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									      	<h4 class="modal-title" id="head-Solicitud">Solicitud</h4>
									      </div>
									      <div class="modal-body">
								        	<table class="table" >
								        		<tbody>
								        			<tr>
									        			<th style="border:none;">Clave Crédito</th>
									        			<td style="border:none;">{{$solicitud->credito}}</td>
								        			</tr>
								        			<tr>
									        			<th style="border:none;">Usuario</th>
									        			<td style="border:none;">{{$solicitud->usuario}}</td>
								        			</tr>
								        			<tr>
									        			<th colspan="2" style="border:none;">Descripción</th>
								        			</tr>
													<tr>
									        			<td colspan="2" style="border:none;">{{$solicitud->descripcion}}</td>
								        			</tr>
								        		</tbody>
								        	</table>
								        	<br>
										    <div class="modal-footer">
										    	<button type="button" class="btn BtnOrange" data-dismiss="modal">Cerrar</button>
										        <a href="{{route('aprobacion_solicitud',['aprobacion' => 0,'solicitud' => $solicitud->id_solicitud])}}" class="btn BtnRed" onClick="return confirm('No aprobara la solicitud, desea continuar?')">No Aprobar</a>
										        <a href="{{route('aprobacion_solicitud',['aprobacion' => 1,'solicitud' => $solicitud->id_solicitud,'clave' => $solicitud->credito])}}" class="btn BtnGreen" onClick="return confirm('Una vez aprobado, no se podra recuperar la información, desea continuar?')">Aprobar Eliminación</a>
										    </div>
									      </div>
									    </div>
									  </div>
									</div>
	        					@endforeach
	        				</div>
	        			</div>
	        		</div>
	        		<br><br><br><br><br><br><br>
	        		<br><br><br><br><br><br><br>
	        		<br><br><br><br><br><br><br>
	        		<br><br><br><br><br>

	        		<!-- Modal Limite -->
					<div class="modal fade" id="CambiarLimite" tabindex="-1" role="dialog" aria-labelledby="head-CambiarLimite">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					      	<h4 class="modal-title" id="head-CambiarLimite">Cambiar Limite</h4>
					      </div>
					      <div class="modal-body">
					        <form action="{{route('add_datos')}}" method="POST">
					        	{{ csrf_field() }}
					        	<input type="hidden" value="limite" name="registro">
					        	<input type="hidden" value="{{$datos->id_empresa}}" name="id">
					        	<div class="row">
					        		<div class="form-group col-md-6">
					        			<label>Limite</label>
					        			<div class="input-group">
					        				<input type="number" class="form-control" step="0.01" value="0.00" name="limite" required min="1">
					        				<div class="input-group-addon addon-limite">%</div>
					        			</div>
					        		</div>
					        		<div class="form-group col-md-6">
					        			<label>Tipo</label>
					        			<select name="tipo" class="form-control select-type" required>
					        				<option value="Porcentaje">Porcentaje</option>
					        				<option value="Monto">Monto</option>
					        			</select>
					        		</div>
					        	</div>
							    <div class="modal-footer">
							    	<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
							        <input type="submit" class="btn BtnGreen" value="Guardar">
							    </div>
					        </form>
					      </div>
					    </div>
					  </div>
					</div>

					<!-- Modal Director -->
					<div class="modal fade" id="CambiarDirector" tabindex="-1" role="dialog" aria-labelledby="head-CambiarDirector">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					      	<h4 class="modal-title" id="head-CambiarDirector">Cambiar Director</h4>
					      </div>
					      <div class="modal-body">
					        <form action="{{route('add_datos')}}" method="POST">
					        	{{ csrf_field() }}
					        	<input type="hidden" value="director" name="registro">
					        	<input type="hidden" value="{{$datos->id_empresa}}" name="id">
					        	<div class="row">
					        		<div class="form-group col-md-12">
					        			<label>Nombre</label>
					        			<div class="input-group">
					        				<div class="input-group-addon addon-limite">Director</div>
					        				<input type="text" class="form-control" name="nombre" required>
					        			</div>
					        		</div>
					        	</div>
							    <div class="modal-footer">
							    	<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
							        <input type="submit" class="btn BtnGreen" value="Guardar">
							    </div>
					        </form>
					      </div>
					    </div>
					  </div>
					</div>
					
				@else
					<br><br><br><br><br><br><br>
	        		<br><br><br><br><br><br><br>
	        		<br><br><br><br><br><br><br>
	        		<br><br><br><br><br>
				@endif
			</div>
        </div>	
    </div>
@stop

@section('js')
	<script>
		$(document).ready(function(){
			//$('.alert').fadeOut(5600);
			$('.select-type').change(function(){
				var val = $(this).val();
				if(val === "Monto"){
					$('.addon-limite').text("$");
				} else {
					$('.addon-limite').text("%");
				}
			});
			/*$('.disabled').click(function(e){
				e.preventDefault();
			});*/
		});	
	</script>
@stop