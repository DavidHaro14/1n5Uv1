@extends('principal.template.cuerpo')

@section('titulo','Programas')

@section('contenido')
	<div class="row">
		@foreach($programas as $prog)
		<div class="panel panel-default">
	  		<div class="panel-body">
				<div class="media">
				  <div class="media-left">
				    <a href="#">
				      <img src="{{ asset('img/programas.png')}}" alt="...">
				    </a>
				  </div>
				  <div class="media-body">
				    <h4 class="media-heading">{{$prog->nombre}}</h4>
				    @foreach($prog->tipos_programas as $tipo)
				    	<li><a href="" data-toggle="modal" data-target="#programa-{{$tipo->id_tipo_programa}}">{{$tipo->nombre}}</a></li>
				    	<!-- Modal -->
						<div class="modal fade" id="programa-{{$tipo->id_tipo_programa}}" tabindex="-1" role="dialog" aria-labelledby="head-area">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="head-area">{{$tipo->nombre}} - DOCUMENTOS REQUERIDOS</h4>
									</div>
									<div class="modal-body">
										@foreach($tipo->documentos as $doc)
											<h4><li>{{$doc->nombre}}</li></h4>
										@endforeach
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
									</div>
								</div>
							</div>
						</div>
				    @endforeach
				  </div>
				</div>	
			</div>
		</div>
		@endforeach
	</div>
@stop