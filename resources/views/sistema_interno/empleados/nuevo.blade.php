@extends('sistema_interno.template.cuerpo')

@section('titulo','Personal Insuvi')

@section('contenido')
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
		</div>
		<div class="col-md-6 col-sm-6 text-right">
			<button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#empleados"><span class="icon-plus"></span> Nuevo</button>
		</div>
		<br><br><br>
		<div class="panel panel-default">
			<table class="table table-hover table-responsive">
				<thead class="BtnGray">
					<tr>
						<th>Estatus</th>
						<th>Nombre</th>
						<th>Teléfono</th>
						<th>Correo</th>
						<th>Modulo</th>
						<!-- <th>Usuario</th>
						<th>Perfil</th>
						<th>Operaciones</th>
						<th class="text-center">Permisos</th> -->
						<th class="text-center">Usuario</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@if(count($empleados) < 1)
					<tr>
						<th colspan="7">No se encontró ningún registro...</th>
					</tr>
					@endif
					@foreach($empleados as $emp)
						<?php 
							$no_asignados = \DB::table('empleado')->where('jefe_id','=',$emp->id_empleado)->count();
							$asignados    = \DB::table('empleado')->join('usuario','usuario.id_usuario','=','empleado.usuario_id')->where('empleado.jefe_id','=',$emp->id_empleado)->get();
							//var_dump($asignados);
						?>
						<tr>
							<td>
								@if($emp->usuario->estatus_us)
									<span class="label label-success BtnGreen">Activo</span>
								@else
									<span class="label label-danger BtnRed">Inactivo</span>
								@endif
							</td>
							<td>{{$emp->nombre}} {{$emp->apellido_p}} {{$emp->apellido_m}}</td>
							<td>{{$emp->telefono}}</td>
							<td>{{$emp->correo}}</td>
							<td>{{$emp->modulo}}</td>
							<!-- <td>{{$emp->usuario->usuario}}</td>
							<td>{{$emp->usuario->perfil}}</td>
							<th class="text-center">{{$emp->usuario->permisos}}%</th> -->
							<td class="text-center"><button type="button" class="btn btn-xs BtnOrange" data-target="#usuarios-{{$emp->id_empleado}}" data-toggle="modal"><span class="icon icon-user"></span> Detalle</button></td>
							@if($no_asignados > 0)
								<td class="text-center">
									<button type="button" class="btn BtnRed btn-xs" data-target="#sustitucion-{{$emp->id_empleado}}" data-toggle="modal"><span class="icon-arrow-down"></span> Suspender</button>
								</td>
							@else
								<td class="text-center">
									@if($emp->usuario->estatus_us)
										<a href="{{route('empleado_estatus',$emp->id_empleado)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
									@else
										<a href="{{route('empleado_estatus',$emp->id_empleado)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
									@endif
								</td>
							@endif
						</tr>

						<!-- Ver Datos Usuarios -->
						<div class="modal fade" id="usuarios-{{$emp->id_empleado}}" tabindex="-1" role="dialog" aria-labelledby="head-usuario">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						      	<h4 class="modal-title text-center" id="head-usuario"><b>Información</b></h4>
						      </div>
						      <div class="modal-body">
						      	<div class="row">
									<div class="col-md-12 col-sm-12 text-center">
										<h4 class="BtnGray" style="padding:8px;">Datos Usuario Sistema</h4>
									</div>
									<div class="col-md-6 col-sm-6">
										<label>Nombre Usuario: </label>
										<p>{{$emp->usuario->usuario}}</p>
									</div>
									<div class="col-md-6 col-sm-6">
										<label>Modulo Asignado: </label>
										<p>{{$emp->modulo}}</p>
									</div>
									<div class="col-md-6 col-sm-6">
										<label>Perfil Usuario: </label>
										<p>{{$emp->usuario->perfil}}</p>
									</div>
									<div class="col-md-6 col-sm-6">
										<label>Nivel de Permisos: </label><br>
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{$emp->usuario->permisos}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$emp->usuario->permisos}}%;">
											{{$emp->usuario->permisos}}%
										</div>
										<!-- <p>{{$emp->usuario->permisos}}%</p> -->
									</div>
									<div class="col-md-12 col-sm-12">
										<label>Jefe Asignado:</label>
										@if($emp->jefe_id != 0)
											<p>
												<?php 
													$jefe = \insuvi\Empleado::find($emp->jefe_id);
													echo $jefe->nombre . " " . $jefe->apellido_p . " " . $jefe->apellido_m;
												?>
											</p>
										@elseif($emp->jefe != 0)
											<p>Es Jefe</p>
										@else
											<p>Ninguno</p>
										@endif
									</div>
									<div class="col-md-12 col-sm-12 text-center">
										<h4 class="BtnGray" style="padding:8px;">Rol(es) Asignado(s):</h4>
									</div>
									<div class="col-md-12 col-sm-12">
										<ul>
											@foreach($emp->usuario->modulos as $modulo)
												<li>{{$modulo}}</li>
											@endforeach
										</ul>
									</div>
									<div class="col-md-12 col-sm-12 text-center">
										<h4 class="BtnGray" style="padding:8px;">Nivel de Permisos:</h4>
									</div>
									<div class="col-md-12 col-sm-12">
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;">
											25%
										</div>
										<br>
										<b>CONSULTAR</b>
									</div>

									<div class="col-md-12 col-sm-12" style="margin-top:1%;">
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
											50%
										</div>
										<br>
										<b>CONSULTAR, REGISTRAR</b>
									</div>

									<div class="col-md-12 col-sm-12" style="margin-top:1%;">
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;">
											75%
										</div>
										<br>
										<b>CONSULTAR, REGISTRAR, MODIFICAR</b>
									</div>

									<div class="col-md-12 col-sm-12" style="margin-top:1%;">
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
											100%
										</div>
										<br>
										<b>CONSULTAR, REGISTRAR, MODIFICAR, ELIMINAR</b>
									</div>
								</div>
						      </div>
						    </div>
						  </div>
						</div>

						@if($no_asignados > 0)
							<!-- Susticiones -->
							<div class="modal fade" id="sustitucion-{{$emp->id_empleado}}" tabindex="-1" role="dialog" aria-labelledby="head-sustitucion">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							      	<h4 class="modal-title text-center" id="head-sustitucion"><b>Sustitución de Jefe</b></h4>
							      </div>
							      <div class="modal-body">
									<form action="{{route('jefe_estatus',$emp->id_empleado)}}" method="POST">
							      		<div class="row">
											<input type="hidden" name="_method" value="PUT">
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<div class="col-md-12 col-sm-12 text-center">
												<h4 class="BtnGray" style="padding:8px;">Empleados Relacionados</h4>
											</div>
											<div class="col-md-12 col-sm-12">
												<ul>
													@foreach($asignados as $empleado)
														<li>
															{{$empleado->nombre . " " . $empleado->apellido_p . " " . $empleado->apellido_m }} - <b>{{$empleado->perfil}}</b>
														</li>
														<input type="hidden" name="empleados[]" value="{{$empleado->id_empleado}}">
													@endforeach
												</ul>
											</div>
											<div class="col-md-12 col-sm-12 text-center">
												<h4 class="BtnGray" style="padding:8px;">Sustituto</h4>
											</div>
											<div class="col-md-12 col-sm-12">
												<label>Asignar Nuevo Jefe</label>
												<select name="jefe" class="form-control" required>
													<option value="">Seleccionar</option>
													<!-- <option value="0" selected>Ningúno</option> -->
													@foreach($jefes as $jefe)
														@if($jefe->id_empleado != $emp->id_empleado)
															<option value="{{$jefe->id_empleado}}">{{$jefe->nombre . " " . $jefe->apellido_m . " " . $jefe->apellido_p}}</option>
														@endif
													@endforeach
												</select>
											</div>
										</div>
										<div class="row" style="margin-top:2%;">
											<div class="modal-footer">
												<button type="button" class="btn BtnRed" data-dismiss="modal">Cerrar</button>
												<button type="submit" class="btn BtnGreen">Sustituir</button>
											</div>
										</div>
									</form>
							      </div>
							    </div>
							  </div>
							</div>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<!-- Insertar Empleado -->
	<div class="modal fade" id="empleados" tabindex="-1" role="dialog" aria-labelledby="head-empleado">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      	<h4 class="modal-title text-center" id="head-empleado"><b>Nuevo Personal</b></h4>
	      </div>
	      <div class="modal-body">
	        <form action="{{route('empleado_agregar')}}" method="POST">
	        	{{ csrf_field() }}
	        	<div class="row">
		        	<div class="form-group col-md-6">
						<label for="nombre">Nombre</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-user"></span></div>
							<input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="paterno">Apellido Paterno</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-user"></span></div>
							<input type="text" class="form-control" name="apellido_p" placeholder="Apellido Paterno" required>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="materno">Apellido Materno</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-user"></span></div>
							<input type="text" class="form-control" name="apellido_m" placeholder="Apellido Materno" required>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="telefono">Teléfono (opcional)</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-phone"></span></div>
							<input type="text" class="form-control OnlyNumber" name="telefono" placeholder="Teléfono" id="telefono" maxlength="13">
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="correo">Correo (opcional)</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-mail"></span></div>
							<input type="email" class="form-control" name="correo" placeholder="Correo" maxlength="60">
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="usuario">Usuario</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-user"></span></div>
							<input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="pass">Contraseña</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-key"></span></div>
							<input type="password" class="form-control" name="password" placeholder="Contraseña" maxlength="15" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" title="Debe contener entre 8 y 15 caracteres, incluyendo al menos una letra mayuscula, un numero y el resto en minusculas" id="password" required>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="confirmar">Confirmar Contraseña</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-key"></span></div>
							<input type="password" class="form-control" name="password_confirmation" placeholder="Contraseña" maxlength="15" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" title="Debe contener entre 8 y 15 caracteres, incluyendo al menos una letra mayuscula, un numero y el resto en minusculas" id="confirm_password" required>
						</div>
					</div>

		        	<div class="form-group col-md-6">
			            <label for="nombre">Perfil</label>
			            <div class="input-group">
							<div class="input-group-addon"><span class="icon icon-user-tie"></span></div>
			            	<input type="text" class="form-control" name="perfil" placeholder="Nombre Perfil" required>
						</div>
		          	</div>
					
					<div class="form-group col-md-6">
						<label for="Modulo">Modulo</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-office"></span></div>
							<select name="modulo" class="form-control" required>
								<option value="">Seleccionar</option>
								@foreach($modulos as $modulo)
									<option value="{{$modulo}}">{{$modulo}}</option>
								@endforeach
							</select>
						</div>
					</div>

		        	<div class="form-group col-md-12">
						<label for="operaciones">Rol(es)</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-display"></span></div>
							<select name="operaciones[]" class="MultiSelect" multiple id="operacion" required>
								<option value="ADMON">Súper Administrador</option>
								<option value="SOLICITUD">Atención Solicitud</option>
								<option value="ESTUDIO">Estudio Socioeconomico</option>
								<option value="DEMANDA">Atención Demanda</option>
								<option value="CONTRATACION">Contratación</option>
								<option value="ENGANCHE">Modificación Enganche</option>
								<option value="DOMICILIO">Cambio Domicilio</option>
								<option value="CAJA">Caja</option>
								<option value="CANCELACION">Cancelación Crédito</option>
								<option value="SEGUIMIENTO">Seguimientos</option>
								<option value="REESTRUCTURA">Reestructura</option>
								<option value="SAIV">Reestructura SAIV</option>
								<option value="CESION">Cesión Derecho</option>
								<option value="DESCUENTO">Descuentos</option>
							</select>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="Permisos">Nivel Permisos</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-unlocked"></span></div>
							<select name="permisos" class="form-control" id="permisos" required>
								<option value="">Seleccionar</option>
								<option value="25">Consultar</option>
								<option value="50">Consultar y Registrar</option>
								<option value="75">Consultar, Registrar y Modificar</option>
								<option value="100">Consultar, Registrar, Modificar y Eliminar</option>
							</select>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="Permisos">Asignación Jefe</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="icon icon-user-tie"></span></div>
							<select name="jefe" class="form-control" id="jefes" required>
								<option value="jefe">Es Jefe</option>
								<option value="0" selected>Ninguno</option>
								@foreach($jefes as $jefe)
									<option value="{{$jefe->id_empleado}}">{{$jefe->nombre . " " . $jefe->apellido_m . " " . $jefe->apellido_p}}</option>
								@endforeach
							</select>
						</div>
					</div>
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

@stop

@section('js')
	<script src="{{asset('js/empleado.js')}}"></script>
@stop