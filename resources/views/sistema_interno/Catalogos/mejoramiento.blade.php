@extends('sistema_interno.template.cuerpo')

@section('titulo','Mejoramiento')

@section('contenido')
    
    <div class="row">
        <div class="col-md-6">
            <a href="{{route('catalogos')}}" class="btn BtnRed"><span class="icon icon-reply"></span> Salir</a>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn BtnGreen" data-toggle="modal" data-target="#mejoramientos"><span class="icon-plus"></span> Nuevo</button>
        </div>
        <br><br><br>
        <div class="panel panel-default table-width">
            <table class="table table-hover table-responsive">
                <thead class="BtnGray">
                    <tr>
                        <th>Estatus</th>
                        <th>Nombre</th>
                        <th class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mejoramientos as $mej)
                        <tr>
                            <td>
                                @if($mej->estatus)
                                    <span class="label BtnGreen">Activo</span>
                                @else
                                    <span class="label BtnRed">Inactivo</span>
                                @endif
                            </td>
                            <td>{{$mej->nombre}}</td>
                            <td class="text-center">
                                @if($mej->estatus)
                                    <a href="{{route('mejoramiento_estatus',$mej->id_mejoramiento)}}" class="btn BtnRed btn-xs"><span class="icon-arrow-down"></span> Suspender</a>
                                @else
                                    <a href="{{route('mejoramiento_estatus',$mej->id_mejoramiento)}}" class="btn BtnGreen btn-xs"><span class="icon-arrow-up"></span> Activar</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Insertar mejoramientos -->
        <div class="modal fade" id="mejoramientos" tabindex="-1" role="dialog" aria-labelledby="head-mejoramiento">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="head-mejoramiento">Nuevo Mejoramiento</h4>
              </div>
              <div class="modal-body">
                <form action="{{route('mejoramiento_agregar')}}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="nombre">Nombre Mejoramiento:</label>
                        <input type="text" class="form-control" name="nombre" required>
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
    </div>

@stop