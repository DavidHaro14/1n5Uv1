<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use insuvi\Http\Requests;
use insuvi\Cliente;
use insuvi\Estado;
use insuvi\Muro;
use insuvi\Piso;
use insuvi\Techo;
use insuvi\EstudioSocioeconomico;
use insuvi\Ocupacion;
use insuvi\SitConyuge;
use Carbon\Carbon;

class EstudioSocioeconomicoController extends Controller
{

    public function index(Request $request)
    {
        $solicitante = Cliente::search($request->buscar,$request->tipo)->where('estatus','=','1')->orderBy('ape_paterno')->paginate(10);


        return view('sistema_interno.EstudioSocioeconomico.index')->with('clientes',$solicitante);
    }

    public function form($id)
    {
        if (\Auth::user()->empleado->jefe_id == 0 && \Auth::user()->empleado->jefe == 0) {
            Flash::error("Debe tener un jefe de departamento asignado para poder realizar este proceso.");
            return redirect()->route('insuvi');
        }
    	$updated = 0;//Bandera para saber si existe el estudio socioecnomico -> si permanece 0 no existe
        $solicitante = Cliente::find($id);//Buscar Cliente

        //Si es Hasmany en el modelo usar foreach para desglosar la consulta con ORM eloquent de laravel
        foreach ($solicitante->estudios_socioeconomico as $lol) {
            $updated = $lol->id_estudio;//Si ya existe el estudio socioecnomico -> almacenar la id
        }

    	// $date = Carbon::parse($solicitante->fecha_nac);//obtener la fecha de nacimiento
    	// $edad = Carbon::createFromDate($date->year, $date->month, $date->day)->age;//calcular la edad

    	// $solicitante->fecha_nac = $edad;//colocar la edad en fecha_nac

        //Obtener la lista de los campo con tipo ENUM de la base de datos -> con la funcion del Helper (App\Helpers\Enums.php)
        $vivienda       = enums('estudio_socioeconomico','estado_vivienda');
        $servicioS      = enums('estudio_socioeconomico','servicio_salud');
        $servicioV      = enums('estudio_socioeconomico','servicio_vivienda');
        $servicioSC     = enums('sit_conyuge','servicio_salud');
        $servicioVC     = enums('sit_conyuge','servicio_vivienda');
        $generoF        = enums('familiar','genero');
        $parentescoF    = enums('familiar','parentesco');
        $generoR        = enums('referencia','genero');
        $parentescoR    = enums('referencia','parentesco');

        //Traer los catalogos para los selects
    	$state     = Estado::where('estatus', '=', '1')->orderBy('nombre')->get();
        $piso      = Piso::where('estatus', '=', '1')->orderBy('nombre')->get();
        $muro      = Muro::where('estatus', '=', '1')->orderBy('nombre')->get();
        $techo     = Techo::where('estatus', '=', '1')->orderBy('nombre')->get();
        $ocupacion = Ocupacion::where('estatus', '=', '1')->orderBy('nombre')->get();

    	return view('sistema_interno.EstudioSocioeconomico.nuevo')->with('solicitante',$solicitante)
    															  ->with('estados',$state)
                                                                  ->with('pisos',$piso)
                                                                  ->with('muros',$muro)
                                                                  ->with('techos',$techo)
                                                                  ->with('viviendas',$vivienda)
                                                                  ->with('ocupaciones',$ocupacion)
                                                                  ->with('serv_salud',$servicioS)
                                                                  ->with('serv_vivienda',$servicioV)
                                                                  ->with('serv_salud_conyuge',$servicioSC)
                                                                  ->with('serv_vivienda_conyuge',$servicioVC)
                                                                  ->with('generoF',$generoF)
                                                                  ->with('parentescoF',$parentescoF)
                                                                  ->with('generoR',$generoR)
                                                                  ->with('parentescoR',$parentescoR)
                                                                  ->with('existe',$updated);
    }

    public function agregar(Request $request)
    {
        //Validacion solicitante
        $this->validate($request, [
            'tel_empresa'       => 'numeric', 'antiguedad'        => 'numeric',
            'ingr_total'        => 'numeric', 'gst_total'         => 'numeric',
            'ingr_prin'         => 'numeric', 'ingr_sec'          => 'numeric',
            'gst_alimentacion'  => 'numeric', 'gst_luz'           => 'numeric',
            'gst_agua'          => 'numeric', 'gst_educacion'     => 'numeric',
            'gst_renta'         => 'numeric', 'gst_transporte'    => 'numeric',
            'gst_otros'         => 'numeric', 'cuartos'           => 'numeric',
            ],[
            'tel_empresa.numeric'       => 'El Campo Telefono Empresa Debe Ser Solo Numerico',
            'antiguedad.numeric'        => 'El Campo Antiguedad Debe Ser Solo Numerico',
            'ingr_total.numeric'        => 'El Campo Ingresos Total Debe Ser Solo Numerico',
            'gst_total.numeric'         => 'El Campo Gastos Total Debe Ser Solo Numerico',
            'ingr_prin.numeric'         => 'El Campo Ingreso Principal Debe Ser Solo Numerico',
            'ingr_sec.numeric'          => 'El Campo Ingreso Secundario Debe Ser Solo Numerico',
            'gst_alimentacion.numeric'  => 'El Campo Gastos Alimentacion Debe Ser Solo Numerico',
            'gst_luz.numeric'           => 'El Campo Gastos Luz Debe Ser Solo Numerico',
            'gst_agua.numeric'          => 'El Campo Gastos Agua Debe Ser Solo Numerico',
            'gst_educacion.numeric'     => 'El Campo Gastos Educacion Debe Ser Solo Numerico',
            'gst_renta.numeric'         => 'El Campo Gastos Renta Debe Ser Solo Numerico',
            'gst_transporte.numeric'    => 'El Campo Gastos Transporte Debe Ser Solo Numerico',
            'gst_otros.numeric'         => 'El Campo Otros Gastos Debe Ser Solo Numerico',
            'cuartos.numeric'           => 'El Campo Numeros de Cuartos Debe Ser Solo Numerico',
        ]);
        
        if ($request->estudio > 0) { //ACTUALIZAR UN ESTUDIO SOCIOECONOMICO EXISTENTE

            //Buscar Estudio Socioeconomico existente para actualizar
            $estudio = EstudioSocioeconomico::find($request->estudio);
            
            //Si es el mismo No. del seguro social (solicitante) que el anterior -> omitir la validacion UNIQUE
            /*if ($estudio->no_seg_social != $request->seg_social) {
                $this->validate($request, [
                    'seg_social'        => 'unique:estudio_socioeconomico,no_seg_social'
                    ],[
                    'seg_social.unique' => 'Ya existe el numero del seguro social'
                ]);
            }*/

            //Si tiene conyuge
            if($request->cony){ //Actualizar Estudio Socioeconomico con datos Conyuge

                //Si es el mismo No. del seguro social (conyuge) que el anterior -> omitir la validacion UNIQUE
                /*if ($estudio->sit_conyuge->no_seg_social != $request->seg_social_conyuge) {
                    $this->validate($request, [
                        'seg_social_conyuge'        => 'unique:sit_conyuge,no_seg_social'
                        ],[
                        'seg_social_conyuge.unique' => 'Ya existe el numero del seguro social (Conyuge)'
                    ]);
                }*/

                //Validacion del conyuge
                $this->validate($request, [
                    'tel_empresa_conyuge'       => 'numeric', 'antiguedad_conyuge'        => 'numeric',
                    'ingr_prin_conyuge'         => 'numeric', 'ingr_sec_conyuge'          => 'numeric',
                    ],[
                    'tel_empresa_conyuge.numeric'       => 'El Campo Telefono Empresa (Conyuge) Debe Ser Solo Numerico',
                    'antiguedad_conyuge.numeric'        => 'El Campo Antiguedad (Conyuge) Debe Ser Solo Numerico',
                    'ingr_prin_conyuge.numeric'         => 'El Campo Ingreso Principal (Conyuge) Debe Ser Solo Numerico',
                    'ingr_sec_conyuge.numeric'          => 'El Campo Ingreso Secundario (Conyuge) Debe Ser Solo Numerico',
                ]);

                $estudio->antiguedad         = $request->antiguedad;
                $estudio->nombre_trabajo     = $request->empresa;
                $estudio->telefono_trabajo   = $request->tel_empresa;
                $estudio->servicio_salud     = $request->servicio_salud;
                $estudio->servicio_vivienda  = $request->servicio_vivienda;
                $estudio->num_int            = $request->num_int;
                $estudio->num_ext            = $request->num_ext;
                $estudio->ingr_principal     = $request->ingr_prin;
                $estudio->ingr_secundario    = $request->ingr_sec;
                $estudio->asalariado         = $request->asalariado;
                $estudio->gst_alimentacion   = $request->gst_alimentacion;
                $estudio->gst_luz            = $request->gst_luz;
                $estudio->gst_transporte     = $request->gst_transporte;
                $estudio->gst_renta          = $request->gst_renta;
                $estudio->gst_educacion      = $request->gst_educacion;
                $estudio->gst_agua           = $request->gst_agua;
                $estudio->gst_otros          = $request->gst_otros;
                $estudio->gst_total          = $request->gst_total;
                $estudio->ingr_total         = $request->ingr_total;
                $estudio->ingr_familiar      = $request->ingr_familiar;
                $estudio->no_seg_social      = $request->seg_social;
                $estudio->num_cuartos        = $request->cuartos;
                $estudio->escrituracion      = $request->escrituracion;
                $estudio->entrevistador      = \Auth::user()->usuario;
                $estudio->responsable        = (\Auth::user()->empleado->jefe == 1) ? \Auth::user()->empleado->nombre . ' ' . \Auth::user()->empleado->apellido_p . ' ' . \Auth::user()->empleado->apellido_m : \Auth::user()->empleado->JefeAsignado->nombre . " " . \Auth::user()->empleado->JefeAsignado->apellido_p . " " . \Auth::user()->empleado->JefeAsignado->apellido_m;
                //$estudio->cliente_id         = $request->num;
                $estudio->calle_id           = $request->calle;
                $estudio->estado_vivienda    = $request->estado_vivienda;
                $estudio->techo_id           = $request->techo;
                $estudio->muro_id            = $request->muro;
                $estudio->piso_id            = $request->piso;

                $estudio->save();

                $estudio->sit_conyuge->antiguedad                = $request->antiguedad_conyuge;
                $estudio->sit_conyuge->nombre_trabajo            = $request->empresa_conyuge;
                $estudio->sit_conyuge->telefono_trabajo          = $request->tel_empresa_conyuge;
                $estudio->sit_conyuge->servicio_salud            = $request->servicio_salud_conyuge;
                $estudio->sit_conyuge->servicio_vivienda         = $request->servicio_vivienda_conyuge;
                $estudio->sit_conyuge->no_seg_social             = $request->seg_social_conyuge;
                $estudio->sit_conyuge->num_int                   = $request->num_int_conyuge;
                $estudio->sit_conyuge->num_ext                   = $request->num_ext_conyuge;
                $estudio->sit_conyuge->ingr_prin                 = $request->ingr_prin_conyuge;
                $estudio->sit_conyuge->ingr_sec                  = $request->ingr_sec_conyuge;
                $estudio->sit_conyuge->asalariado                = $request->asalariado_conyuge;
                $estudio->sit_conyuge->calle_id                  = $request->calle_conyuge;
                $estudio->sit_conyuge->ocupacion_id              = $request->ocupacion_conyuge;

                $estudio->sit_conyuge->save();

            } else { //Actualizar Estudio socioeconomico sin Conyuge

                $estudio->antiguedad         = $request->antiguedad;
                $estudio->nombre_trabajo     = $request->empresa;
                $estudio->telefono_trabajo   = $request->tel_empresa;
                $estudio->servicio_salud     = $request->servicio_salud;
                $estudio->servicio_vivienda  = $request->servicio_vivienda;
                $estudio->num_int            = $request->num_int;
                $estudio->num_ext            = $request->num_ext;
                $estudio->ingr_principal     = $request->ingr_prin;
                $estudio->ingr_secundario    = $request->ingr_sec;
                $estudio->asalariado         = $request->asalariado;
                $estudio->gst_alimentacion   = $request->gst_alimentacion;
                $estudio->gst_luz            = $request->gst_luz;
                $estudio->gst_transporte     = $request->gst_transporte;
                $estudio->gst_renta          = $request->gst_renta;
                $estudio->gst_educacion      = $request->gst_educacion;
                $estudio->gst_agua           = $request->gst_agua;
                $estudio->gst_otros          = $request->gst_otros;
                $estudio->gst_total          = $request->gst_total;
                $estudio->ingr_total         = $request->ingr_total;
                $estudio->ingr_familiar      = $request->ingr_familiar;
                $estudio->no_seg_social      = $request->seg_social;
                $estudio->num_cuartos        = $request->cuartos;
                $estudio->escrituracion      = $request->escrituracion;
                $estudio->entrevistador      = \Auth::user()->usuario;
                $estudio->responsable        = (\Auth::user()->empleado->jefe == 1) ? \Auth::user()->empleado->nombre . ' ' . \Auth::user()->empleado->apellido_p . ' ' . \Auth::user()->empleado->apellido_m : \Auth::user()->empleado->JefeAsignado->nombre . " " . \Auth::user()->empleado->JefeAsignado->apellido_p . " " . \Auth::user()->empleado->JefeAsignado->apellido_m;
                $estudio->cliente_id         = $request->num;
                $estudio->calle_id           = $request->calle;
                $estudio->estado_vivienda    = $request->estado_vivienda;
                $estudio->techo_id           = $request->techo;
                $estudio->muro_id            = $request->muro;
                $estudio->piso_id            = $request->piso;

                $estudio->save();

            }//END IF CONYUGE

            //dd("Actualizar Estudio -> " . $estudio->nombre_trabajo);

        } else { //INSERTAR UN NUEVO ESTUDIO SOCIOECONOMICO

            //Validacion solicitante
            /*$this->validate($request, [
                'seg_social'        => 'unique:estudio_socioeconomico,no_seg_social'
                ],[
                'seg_social.unique' => 'Ya existe el numero del seguro social'
            ]);*/

            $estudio = new EstudioSocioeconomico();

            //Si tiene Conyuge
            if($request->cony){ //Insertar nuevo estudiosocioeconomico con conyuge

                //Validacion Conyuge
                $this->validate($request, [
                    //'seg_social_conyuge'        => 'unique:sit_conyuge,no_seg_social',
                    'tel_empresa_conyuge'       => 'numeric', 'antiguedad_conyuge'        => 'numeric',
                    'ingr_prin_conyuge'         => 'numeric', 'ingr_sec_conyuge'          => 'numeric',
                    ],[
                    //'seg_social_conyuge.unique'         => 'Ya existe el numero del seguro social (Conyuge)',
                    'tel_empresa_conyuge.numeric'       => 'El Campo Telefono Empresa (Conyuge) Debe Ser Solo Numerico',
                    'antiguedad_conyuge.numeric'        => 'El Campo Antiguedad (Conyuge) Debe Ser Solo Numerico',
                    'ingr_prin_conyuge.numeric'         => 'El Campo Ingreso Principal (Conyuge) Debe Ser Solo Numerico',
                    'ingr_sec_conyuge.numeric'          => 'El Campo Ingreso Secundario (Conyuge) Debe Ser Solo Numerico',
                ]);

                $sit_conyuge = new SitConyuge();

                $estudio->antiguedad         = $request->antiguedad;
                $estudio->nombre_trabajo     = $request->empresa;
                $estudio->telefono_trabajo   = $request->tel_empresa;
                $estudio->servicio_salud     = $request->servicio_salud;
                $estudio->servicio_vivienda  = $request->servicio_vivienda;
                $estudio->num_int            = $request->num_int;
                $estudio->num_ext            = $request->num_ext;
                $estudio->ingr_principal     = $request->ingr_prin;
                $estudio->ingr_secundario    = $request->ingr_sec;
                $estudio->asalariado         = $request->asalariado;
                $estudio->gst_alimentacion   = $request->gst_alimentacion;
                $estudio->gst_luz            = $request->gst_luz;
                $estudio->gst_transporte     = $request->gst_transporte;
                $estudio->gst_renta          = $request->gst_renta;
                $estudio->gst_educacion      = $request->gst_educacion;
                $estudio->gst_agua           = $request->gst_agua;
                $estudio->gst_otros          = $request->gst_otros;
                $estudio->gst_total          = $request->gst_total;
                $estudio->ingr_total         = $request->ingr_total;
                $estudio->ingr_familiar      = $request->ingr_familiar;
                $estudio->no_seg_social      = $request->seg_social;
                $estudio->num_cuartos        = $request->cuartos;
                $estudio->escrituracion      = $request->escrituracion;
                $estudio->entrevistador      = \Auth::user()->usuario;
                $estudio->responsable        = (\Auth::user()->empleado->jefe == 1) ? \Auth::user()->empleado->nombre . ' ' . \Auth::user()->empleado->apellido_p . ' ' . \Auth::user()->empleado->apellido_m : \Auth::user()->empleado->JefeAsignado->nombre . " " . \Auth::user()->empleado->JefeAsignado->apellido_p . " " . \Auth::user()->empleado->JefeAsignado->apellido_m;
                $estudio->cliente_id         = $request->num;
                $estudio->calle_id           = $request->calle;
                $estudio->estado_vivienda    = $request->estado_vivienda;
                $estudio->techo_id           = $request->techo;
                $estudio->muro_id            = $request->muro;
                $estudio->piso_id            = $request->piso;

                $estudio->save();

                $sit_conyuge->antiguedad                = $request->antiguedad_conyuge;
                $sit_conyuge->nombre_trabajo            = $request->empresa_conyuge;
                $sit_conyuge->telefono_trabajo          = $request->tel_empresa_conyuge;
                $sit_conyuge->servicio_salud            = $request->servicio_salud_conyuge;
                $sit_conyuge->servicio_vivienda         = $request->servicio_vivienda_conyuge;
                $sit_conyuge->no_seg_social             = $request->seg_social_conyuge;
                $sit_conyuge->num_int                   = $request->num_int_conyuge;
                $sit_conyuge->num_ext                   = $request->num_ext_conyuge;
                $sit_conyuge->ingr_prin                 = $request->ingr_prin_conyuge;
                $sit_conyuge->ingr_sec                  = $request->ingr_sec_conyuge;
                $sit_conyuge->asalariado                = $request->asalariado_conyuge;
                $sit_conyuge->calle_id                  = $request->calle_conyuge;
                $sit_conyuge->ocupacion_id              = $request->ocupacion_conyuge;
                $sit_conyuge->estudio_socioeconomico_id = $estudio->id_estudio;

                $sit_conyuge->save();

            } else { //Insertar nuevo estudiosocioeconomico sin conyuge

                $estudio->antiguedad         = $request->antiguedad;
                $estudio->nombre_trabajo     = $request->empresa;
                $estudio->telefono_trabajo   = $request->tel_empresa;
                $estudio->servicio_salud     = $request->servicio_salud;
                $estudio->servicio_vivienda  = $request->servicio_vivienda;
                $estudio->num_int            = $request->num_int;
                $estudio->num_ext            = $request->num_ext;
                $estudio->ingr_principal     = $request->ingr_prin;
                $estudio->ingr_secundario    = $request->ingr_sec;
                $estudio->asalariado         = $request->asalariado;
                $estudio->gst_alimentacion   = $request->gst_alimentacion;
                $estudio->gst_luz            = $request->gst_luz;
                $estudio->gst_transporte     = $request->gst_transporte;
                $estudio->gst_renta          = $request->gst_renta;
                $estudio->gst_educacion      = $request->gst_educacion;
                $estudio->gst_agua           = $request->gst_agua;
                $estudio->gst_otros          = $request->gst_otros;
                $estudio->gst_total          = $request->gst_total;
                $estudio->ingr_total         = $request->ingr_total;
                $estudio->ingr_familiar      = $request->ingr_familiar;
                $estudio->no_seg_social      = $request->seg_social;
                $estudio->num_cuartos        = $request->cuartos;
                $estudio->escrituracion      = $request->escrituracion;
                $estudio->entrevistador      = \Auth::user()->usuario;
                $estudio->responsable        = (\Auth::user()->empleado->jefe == 1) ? \Auth::user()->empleado->nombre . ' ' . \Auth::user()->empleado->apellido_p . ' ' . \Auth::user()->empleado->apellido_m : \Auth::user()->empleado->JefeAsignado->nombre . " " . \Auth::user()->empleado->JefeAsignado->apellido_p . " " . \Auth::user()->empleado->JefeAsignado->apellido_m;
                $estudio->cliente_id         = $request->num;
                $estudio->calle_id           = $request->calle;
                $estudio->estado_vivienda    = $request->estado_vivienda;
                $estudio->techo_id           = $request->techo;
                $estudio->muro_id            = $request->muro;
                $estudio->piso_id            = $request->piso;

                $estudio->save();

            }//END IF CONYUGE

        }//END IF ACTUALIZAR/INSERTAR ESTUDIO SOCIOECONOMICO
        
        Flash::success("Se ha registrado correctamente.");
        return redirect()->route('insuvi');
    }

}
