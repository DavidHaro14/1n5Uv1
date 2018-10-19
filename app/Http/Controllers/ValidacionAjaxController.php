<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use insuvi\Http\Requests;

class ValidacionAjaxController extends Controller
{
    public function GetValidacionAtencionSolicitud(Request $request){
    	if ($request->ajax()) {
    		switch ($request->tab) {
    			case 'GENERALES':
    				$this->validate($request, [
			            'curp'            => 'between:18,18|required|unique:cliente,curp|unique:conyuge,curp',
			            'nombre'          => 'required',
			            'correo'          => 'email|unique:cliente,correo',
			            'ape_paterno'     => 'required',
			            'ape_materno'     => 'required',
			            'estado_civil'    => 'required',
			            'fecha_nac'       => 'date|required',
			            'telefono'        => 'numeric',
			            'estado_nac'      => 'required',
			            'lugar_nac'       => 'required',
			            'no_dependientes' => 'required|numeric',
			            'ocupacion'       => 'required',
			            'escolaridad'     => 'required',
			        ],[
			            'curp.between'                  => 'El campo CURP debe contener 18 Caracteres',
			            'curp.required'                 => 'El campo CURP es obligatorio',
			            'curp.unique'                   => 'Ya se encuentra registrado esta CURP',
			            'nombre.required'               => 'El campo Nombre es obligatorio',
			            'correo.email'                  => 'Debe poner un tipo de correo valido',
			            'correo.unique'                 => 'El correo ya existe',
			            'ape_paterno.required'          => 'El campo Apellido Paterno es obligatorio',
			            'ape_materno.required'          => 'El campo Apellido Materno es obligatorio',
			            'estado_civil.required'         => 'El campo Estado Civil es obligatorio',
			            'fecha_nac.date'                => 'La Fecha de Nacimiento debe contener el siguiente formato (AÑO-MES-DIA)',
			            'fecha_nac.required'            => 'El campo Fecha de Nacimiento es obligatorio',
			            'telefono.numeric'              => 'El campo Teléfono debe contener solo números',
			            'estado_nac.required'           => 'El campo Estado Nacimiento es obligatorio',
			            'lugar_nac.required'            => 'El campo Lugar Nacimiento es obligatorio',
			            'no_dependientes.numeric'       => 'El campo No. Dependientes debe contener solo números',
			            'no_dependientes.required'      => 'El campo No. Dependientes es obligatorio',
			            'ocupacion.required'         	=> 'El campo Ocupación es obligatorio', 
			            'escolaridad.required'         	=> 'El campo Escolaridad es obligatorio', 
			        ]);
					return response()->json("OK");
    				break;
    			
    			case 'CONYUGE':
    				$this->validate($request, [
			            'curp'            => 'between:18,18|required|unique:cliente,curp|unique:conyuge,curp',
			            'nombre'          => 'required',
			            'ape_paterno'     => 'required',
			            'ape_materno'     => 'required',
			            'fecha_nac'       => 'date|required',
			            'lugar_nac'       => 'required',
			            'bienes'          => 'required',
			        ],[
			            'curp.between'                  => 'El campo CURP (Conyuge) debe contener 18 Caracteres',
			            'curp.required'                 => 'El campo CURP (Conyuge) es obligatorio',
			            'curp.unique'                   => 'Ya se encuentra registrado esta CURP',
			            'nombre.required'               => 'El campo Nombre (Conyuge) es obligatorio',
			            'ape_paterno.required'          => 'El campo Apellido Paterno (Conyuge) es obligatorio',
			            'ape_materno.required'          => 'El campo Apellido Materno (Conyuge) es obligatorio',
			            'fecha_nac.date'                => 'La Fecha de Nacimiento (Conyuge) debe contener el siguiente formato (AÑO-MES-DIA)',
			            'fecha_nac.required'            => 'El campo Fecha de Nacimiento (Conyuge) es obligatorio',
			            'lugar_nac.required'            => 'El campo Lugar Nacimiento (Conyuge) es obligatorio',
			            'bienes.required'               => 'El campo Bienes (Conyuge) es obligatorio',
			        ]);
					return response()->json("OK");
    				break;

    			case 'DOMICILIO':
    				$this->validate($request, [
			            'estado'        => 'required',
			            'municipio'     => 'required',
			            'localidad'     => 'required',
			            'colonia'     	=> 'required',
			            'calle'    		=> 'required',
			            'no_ext'       	=> 'required',
			            'no_int'        => 'required',
			            'codigo_postal' => 'numeric|required',
			            'referencia1'   => 'required',
			            'referencia2' 	=> 'required',
			            'referencia3'   => 'required',
			        ],[
			            'estado.required'       => 'El campo Estado es obligatorio',
			            'municipio.required'    => 'El campo Municipio es obligatorio',
			            'localidad.required'    => 'El campo Localidad es obligatorio',
			            'colonia.required'      => 'El campo Colonia es obligatorio',
			            'calle.required'        => 'El campo Calle es obligatorio',
			            'no_ext.required'       => 'El campo No. Exterior es obligatorio',
			            'no_int.required'       => 'El campo No. Interior es obligatorio',
			            'codigo_postal.required'=> 'El campo Codigo Postal es obligatorio',
			            'codigo_postal.numeric' => 'El campo Codigo Postal debe contener solo números',
			            'referencia1.required'  => 'El campo Referencia 1 es obligatorio',
			            'referencia2.required'  => 'El campo Referencia 2 es obligatorio',
			            'referencia3.required'  => 'El campo Referencia 3 es obligatorio',
			        ]);
					return response()->json("OK");
    				break;
    		}
    	}
    }

    public function GetValidacionEstudioSocioeconomico(Request $request){
    	if ($request->ajax()) {
    		switch ($request->tab) {
    			case 'SOLICITANTE':
    				$this->validate($request, [
			            'nombre_empresa'    => 'required',
			            'telefono_empresa'  => 'required',
			            'antiguedad'        => 'required',
			            'estado'        	=> 'required',
			            'municipio'     	=> 'required',
			            'localidad'     	=> 'required',
			            'colonia'     		=> 'required',
			            'calle'    			=> 'required',
			            'no_ext'       		=> 'required',
			            'no_int'        	=> 'required',
			            'servicio_salud'    => 'required',
			            'servicio_vivienda' => 'required',
			        ],[
			            'nombre_empresa.required'	 => 'El campo Nombre Empresa es obligatorio',
			            'telefono_empresa.required'  => 'El campo Telefono Empresa es obligatorio',
			            'antiguedad.required'   	 => 'El campo Antiguedad es obligatorio',
			        	'estado.required'       	 => 'El campo Estado es obligatorio',
			            'municipio.required'    	 => 'El campo Municipio es obligatorio',
			            'localidad.required'    	 => 'El campo Localidad es obligatorio',
			            'colonia.required'      	 => 'El campo Colonia es obligatorio',
			            'calle.required'        	 => 'El campo Calle es obligatorio',
			            'no_ext.required'       	 => 'El campo No. Exterior es obligatorio',
			            'no_int.required'       	 => 'El campo No. Interior es obligatorio',
			            'servicio_salud.required'    => 'El campo Servicio de salud es obligatorio',
			            'servicio_vivienda.required' => 'El campo Servicio de vivienda es obligatorio',
			        ]);
					return response()->json("OK");
    				break;

    				case 'CONYUGE':
    				$this->validate($request, [
			            'ocupacion'    		=> 'required',
			            'nombre_empresa'    => 'required',
			            'telefono_empresa'  => 'required',
			            'antiguedad'        => 'required',
			            'estado'        	=> 'required',
			            'municipio'     	=> 'required',
			            'localidad'     	=> 'required',
			            'colonia'     		=> 'required',
			            'calle'    			=> 'required',
			            'no_ext'       		=> 'required',
			            'no_int'        	=> 'required',
			            'servicio_salud'    => 'required',
			            'servicio_vivienda' => 'required',
			        ],[
			            'ocupacion.required'	 	 => 'El campo Nombre Ocupación (Conyuge) es obligatorio',
			            'nombre_empresa.required'	 => 'El campo Nombre Empresa (Conyuge) es obligatorio',
			            'telefono_empresa.required'  => 'El campo Telefono Empresa (Conyuge) es obligatorio',
			            'antiguedad.required'   	 => 'El campo Antiguedad (Conyuge) es obligatorio',
			        	'estado.required'       	 => 'El campo Estado (Conyuge) es obligatorio',
			            'municipio.required'    	 => 'El campo Municipio (Conyuge) es obligatorio',
			            'localidad.required'    	 => 'El campo Localidad (Conyuge) es obligatorio',
			            'colonia.required'      	 => 'El campo Colonia (Conyuge) es obligatorio',
			            'calle.required'        	 => 'El campo Calle (Conyuge) es obligatorio',
			            'no_ext.required'       	 => 'El campo No. Exterior (Conyuge) es obligatorio',
			            'no_int.required'       	 => 'El campo No. Interior (Conyuge) es obligatorio',
			            'servicio_salud.required'    => 'El campo Servicio de salud (Conyuge) es obligatorio',
			            'servicio_vivienda.required' => 'El campo Servicio de vivienda (Conyuge) es obligatorio',
			        ]);
					return response()->json("OK");
    				break;
    		}
    	}
    }
}
