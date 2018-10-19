<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use Carbon\Carbon;
use insuvi\Http\Requests;
use insuvi\Estado;
use insuvi\Subsidio;
use insuvi\Demanda;
use insuvi\Credito;
use insuvi\Colonia;
use insuvi\Lote;
use insuvi\Cliente;
use insuvi\Mensualidad;
use insuvi\Cancelacion;
use insuvi\Piso;
use insuvi\Muro;
use insuvi\Techo;
use insuvi\RegimenPropiedad;
use insuvi\Ocupacion;
use insuvi\Regularizacion;
use insuvi\Programa;
use insuvi\SolicitudEliminacion;

class CreditoController extends Controller
{
      public function index(Request $request)
      {
         $cliente = Cliente::join('demanda','cliente.id_cliente','=','demanda.cliente_id')->search($request->buscar,$request->tipo)->where('demanda.estatus','=','PREPARADO')->where('demanda.tipo_cliente','=','SOLICITANTE')->groupBy('cliente.id_cliente')->orderBy('ape_paterno')->paginate(25);
         return view('sistema_interno.Creditos.index_credito')->with('clientes',$cliente);
      }

      // Visualizar la plantilla correspondiente del credito
   	public function form($id,$credito = "no")
   	{
         $update = 0;
         if($credito != "no"){
            $update = 1;
            $credito = Credito::find($credito);
         }
         //dd($credito->subsidios);

         //VALIDAR PARA MODIFICACION EN LA VISTA HACER LA VALIDACION Y LUEGO EL POST
         $demanda = Demanda::find($id);

         /* Demanda */
         $t_cliente   = $demanda->tipo_cliente;
         $d_estatus   = $demanda->estatus;
         $t_plantilla = $demanda->tipo_programa->plantilla;

         /* Catalogos */
         $s_est  = Subsidio::where('tipo','=','ESTATAL')->where('estatus','=','1')->orderBy('clave')->get();
         $s_fed  = Subsidio::where('tipo','=','FEDERAL')->where('estatus','=','1')->orderBy('clave')->get();
         $s_mun  = Subsidio::where('tipo','=','MUNICIPAL')->where('estatus','=','1')->orderBy('clave')->get();
         $s_otr  = Subsidio::where('tipo','=','OTROS')->where('estatus','=','1')->orderBy('clave')->get();
         $est    = Estado::where('estatus','=','1')->orderBy('nombre')->get();
         if (($t_cliente == "SOLICITANTE" || $t_cliente == "ACREDITADO") && ($d_estatus == "PREPARADO" || $d_estatus == "EN PROCESO")) {

            switch ($t_plantilla) {
               case '1':
                  return view('sistema_interno.creditos.plantilla1')->with('estados',$est)
                                                 ->with('federal',$s_fed)
                                                 ->with('estatal',$s_est)
                                                 ->with('municipal',$s_mun)
                                                 ->with('otros',$s_otr)
                                                 ->with('actualizar',$update)
                                                 ->with('modificar',$credito)
                                                 ->with('dem',$demanda);
                  break;

               case '2':
                  return view('sistema_interno.creditos.plantilla2')->with('federal',$s_fed)
                                                 ->with('estatal',$s_est)
                                                 ->with('municipal',$s_mun)
                                                 ->with('otros',$s_otr)
                                                 ->with('actualizar',$update)
                                                 ->with('modificar',$credito)
                                                 ->with('dem',$demanda);
                  break;

               case '3':
                  $lotes = \DB::table('lote')
                           ->select('id_lote','no_manzana','no_lote')
                           ->join('credito','lote.id_lote','=','credito.lote_id')
                           ->join('demanda','demanda.id_demanda','=','credito.demanda_id')
                           ->where('cliente_id','=',$demanda->cliente_id)
                           ->where('lote.estatus','=','PAGADO')
                           ->where('credito.estatus','=','PAGADA')
                           ->get();
                  $vivienda       = enums('regularizacion','estado_vivienda');
                  $escrituracion  = enums('regularizacion','escrituracion');
                  $piso           = Piso::where('estatus', '=', '1')->orderBy('nombre')->get();
                  $muro           = Muro::where('estatus', '=', '1')->orderBy('nombre')->get();
                  $techo          = Techo::where('estatus', '=', '1')->orderBy('nombre')->get();
                  $regimen        = RegimenPropiedad::where('estatus', '=', '1')->orderBy('nombre')->get();
                  //dd(count($lotes));
                  return view('sistema_interno.creditos.plantilla3')->with('dem',$demanda)
                                                                    ->with('lotes',$lotes)
                                                                    ->with('estados',$est)
                                                                    ->with('pisos',$piso)
                                                                    ->with('muros',$muro)
                                                                    ->with('techos',$techo)
                                                                    ->with('regimen',$regimen)
                                                                    ->with('viviendas',$vivienda)
                                                                    ->with('actualizar',$update)
                                                                    ->with('modificar',$credito)
                                                                    ->with('escrituracion',$escrituracion);
                  break;
            }
         }

         return redirect()->route('insuvi')->withErrors(['El cliente es tipo AHORRADOR o la demanda no esta preparada para la acreditación']);
   	}

      /* Insertar Plantilla 1, 2 y 3 */
      public function add(Request $request)
      {  
         //dd("ENTRO A POST");
         /* Array Subsidio -> almacenar solo los campos seleccionados y excluir los vacios/nulos */
         $sub_array = [];

         for ($i=0; $i < sizeof($request->subsidios); $i++) { 
            if ($request->subsidios[$i] != "") {
               array_push($sub_array, $request->subsidios[$i]);
            }
         }

         $demanda = Demanda::find($request->demanda);
         // Datos para vereficar que este preparado la demanda para un credito y si no es ahorrador
         $d_estatus = $demanda->estatus;
         $d_cliente = $demanda->tipo_cliente;

         /* Identificar la plantilla */
         $t_plantilla = $demanda->tipo_programa->plantilla;

         if($d_estatus == "PREPARADO" && $d_cliente != "AHORRADOR")
         {

            $credito = new Credito();

            /* Estructura Clave Credito */
            $clv_modulo    = substr($demanda->modulo, 0,2);
            $prog          = str_pad($demanda->tipo_programa->programa->id_programa, 2, "0", STR_PAD_LEFT);
            $Tprog         = str_pad($demanda->tipo_programa->id_tipo_programa, 2, "0", STR_PAD_LEFT);
            $clv_credito   = str_pad($demanda->id_demanda, 5, "0", STR_PAD_LEFT);
               
            if ($t_plantilla == "1") {
               $fracc   = Colonia::find($request->fraccionamiento);
               $credito->clave_credito        = $clv_modulo . $prog . $Tprog . $clv_credito . $fracc->abreviacion;
               $credito->costo_metro          = $request->metro_cuadrado;
               $credito->costo_terreno        = $request->costo_terreno;
               $credito->costo_construccion   = $request->costo_construccion;
               $credito->valor_solucion       = $request->valor_solucion;
               $credito->lote_id              = $request->lote;
            } else if ($t_plantilla == "2"){
               $credito->clave_credito        = $clv_modulo . $prog . $Tprog . $clv_credito . "ME";
               $credito->valor_solucion       = $request->monto_credito;
               $credito->lote_id              = 0;
            } else if ($t_plantilla == "3"){
               if($request->lote < 1 || $request->lote === null){
                  $lote = Lote::find($request->otro_lote);
                  $lote->estatus = "REGULARIZADO";
               } else {
                  $lote = Lote::find($request->lote);
                  $lote->estatus = "PROCESO ESCRITURACION";
               }

               $lote->save();

               $credito->clave_credito        = $clv_modulo . $prog . $Tprog . $clv_credito . $lote->fraccionamiento->abreviacion;
               $credito->valor_solucion       = $request->monto_credito;
               $credito->tabla                = "5";
               $credito->lote_id              = $lote->id_lote;
            }
         
            if ($t_plantilla == "1" || $t_plantilla == "2") {
               $credito->no_subsidio_fed      = $request->subsidio_fed;
               $credito->no_subsidio_est      = $request->subsidio_est;
               $credito->no_subsidio_mun      = $request->subsidio_mun;
               $credito->no_subsidio_otr      = $request->subsidio_otr;
               $credito->taza_interes         = $request->tasa / 100;
               //$credito->plazo                = $request->plazo * 12;
               //$credito->plazo                = $request->plazo;
               $credito->costo_contado        = $request->costo_contado;
               $credito->costo_financiamiento = $request->costo_finan;
               $credito->tabla                = $request->tabla_cobros;
            } /*elseif ($t_plantilla == "3" && $request->lote != "0") {
               //Escrituracion -> Relacionar Lote pagado 
               $credito->lote_id              = $request->lote;//Escrituracion
            } elseif ($t_plantilla == "3" && $request->lote == "0") {
               //Regularizacion -> Crear y Relacionar Lote regularizacion  
               $lot_reg = new Regularizacion();
               $lot_reg->no_lote                = $request->no_lote;
               $lot_reg->no_manzana             = $request->no_manzana;
               $lot_reg->clave_catastral        = $request->clave_catastral;
               $lot_reg->superficie             = $request->superficie;
               $lot_reg->valor_metro            = $request->valor_metro;
               $lot_reg->catastral_lote         = $request->catastral_lote;
               $lot_reg->catastral_construccion = $request->catastral_construccion;
               $lot_reg->insuvi_lote            = $request->insuvi_lote;
               $lot_reg->insuvi_pie_casa        = $request->insuvi_pie_casa;
               $lot_reg->drenaje                = $request->drenaje;
               $lot_reg->agua                   = $request->agua;
               $lot_reg->electricidad           = $request->electricidad;
               $lot_reg->escrituracion          = $request->escrituracion;
               $lot_reg->estado_vivienda        = $request->estado_vivienda;
               $lot_reg->fraccionamiendo_id     = $request->fraccionamiento;
               $lot_reg->regimen_id             = $request->regimen;
               $lot_reg->piso_id                = $request->piso;
               $lot_reg->muro_id                = $request->muro;
               $lot_reg->techo_id               = $request->techo;

               $lot_reg->save();
               $credito->lote_id                = 0;
               // la id foranea estara en el lote_regularizacion -> credito
               //falta definir bien la plantilla 3
            }*/

            $credito->plazo                = $request->plazo;
            $credito->enganche             = $request->enganche;
            $credito->moratorio            = $request->moratorio / 100;
            $credito->total_pagar          = $request->pago_total;
            $credito->fecha_inicio         = $request->fecha_inicio;
            $credito->pago_mensual         = $request->pago_mensual;
            $credito->plantilla            = $t_plantilla;
            $credito->observaciones        = $request->observaciones;
            $credito->demanda_id           = $request->demanda;
            $credito->registrado           = \Auth::user()->usuario;

            $credito->save();

            //Relacionar Subsidios elegidos (Si el credito cuenta con subsidios)
            if ($sub_array) {
               $credito->subsidios()->attach($sub_array);
            } 

            /* Cambiar estatus del lote asignado */
            if ($t_plantilla == "1") {
               $lote = Lote::find($request->lote);
               $lote->estatus = "ASIGNADO";
               $lote->save();
            }

            /* Cambiar demanda EN PROCESO de pago */
            $demanda->estatus      = "EN PROCESO";
            $demanda->tipo_cliente = "ACREDITADO";
            $demanda->save();
               
            /* Almacenar Corrida en la Base de Datos */
            $v_vencimiento  = Carbon::parse($credito->fecha_inicio);
            $v_plazo        = $credito->plazo;
            $v_pago_mensual = $request->pago_mensual;
            $v_resto        = $request->pago_total;

            if ($t_plantilla == "1" || $t_plantilla == "2") {
               $v_tasa         = $credito->taza_interes / 12;
               $v_saldo        = $credito->costo_contado;
               $v_intereses    = $v_tasa * $v_saldo;
               $v_capital      = $v_pago_mensual - $v_intereses;
            }

            for ($mensualidad = 0; $mensualidad < $v_plazo ; $mensualidad ++) {
               $t_mensualidad  = new Mensualidad();

               $v_vencimiento = $v_vencimiento->addMonth();//Obtener el mes siguiente
               // Almacenar renglon
               $t_mensualidad->no_mensualidad = $mensualidad + 1;
               $t_mensualidad->clave_mensualidad = $credito->clave_credito . $t_mensualidad->no_mensualidad;
               $t_mensualidad->fecha_vencimiento = $v_vencimiento;

               if ($t_plantilla == "1" || $t_plantilla == "2") {
                  $t_mensualidad->interes = $v_intereses;
                  $t_mensualidad->capital = $v_capital;
                  $t_mensualidad->saldo = $v_saldo;
               }

               $t_mensualidad->credito_clave = $credito->clave_credito;
               $t_mensualidad->resto = $v_resto /*- $v_pago_mensual*/;
               // $t_mensualidad->resto = $v_resto;

               $t_mensualidad->save();
               //Calcular
               if ($t_plantilla == "1" || $t_plantilla == "2") {
                  $v_saldo -= $v_capital;
                  $v_intereses = $v_tasa * $v_saldo;
                  $v_capital = $v_pago_mensual - $v_intereses;
               }
               $v_resto -= $v_pago_mensual;
            }

            Flash::success("Se ha generado el crédito correctamente.");
            return view('sistema_interno.Creditos.clave_credito')->with('credito',$credito);
         } //if

         return redirect()->route('insuvi')->withErrors(['El cliente es tipo AHORRADOR o la demanda no esta preparada para la acreditación']);
      }

      // Actualizar Credito Virgen
      public function credito_put(Request $request){
         //dd($request);
         $credito = Credito::find($request->credito_modificar);
         $t_plantilla = $credito->plantilla;
         
         switch ($t_plantilla) {
            case '1':
               $credito->costo_metro          = $request->metro_cuadrado;
               $credito->costo_terreno        = $request->costo_terreno;
               $credito->costo_construccion   = $request->costo_construccion;
               $credito->valor_solucion       = $request->valor_solucion;
               if($credito->lote_id != $request->lote){
                  $credito->lote->estatus = "DISPONIBLE";
                  $credito->lote->save();
                  $nuevo = Lote::find($request->lote);
                  $nuevo->estatus   = "ASIGNADO";
                  $nuevo->save();
                  $credito->lote_id = $request->lote;
               }
               break;

            case '2':
                  $credito->valor_solucion = $request->monto_credito;
               break;

            case '3':
               if ($credito->lote_id != $request->lote) {
                  $credito->lote->estatus = "DISPONIBLE";
                  $credito->lote->save();
                  if(($request->lote < 1 || $request->lote === null)){
                     $new = Lote::find($request->otro_lote);
                  } else {
                     $new = Lote::find($request->lote);
                  }
                  $new->estatus = "ASIGNADO";
                  $new->save();
                  $credito->lote_id     = $lote->id_lote;
               }
               $credito->valor_solucion = $request->monto_credito;
               break;
            
            default:
               # code...
               break;
         }

         if ($t_plantilla == "1" || $t_plantilla == "2") {
            // Modificar Subsidios solo si afectaron cambios
            $sub_array_DB = [];
            $sub_array_IN = [];

            for ($i=0; $i < sizeof($request->subsidios); $i++) { 
               if ($request->subsidios[$i] != "") {
                  array_push($sub_array_IN, $request->subsidios[$i]);
               }
            }
            
            foreach ($credito->subsidios as $sub) {
               array_push($sub_array_DB, $sub->id_subsidio);
            }

            $rem = array_diff($sub_array_DB,$sub_array_IN);// IDs que Remover -> Pivot
            $add = array_diff($sub_array_IN,$sub_array_DB);// IDs que Agregar -> Pivot

            if($add){// Agregar
               $credito->subsidios()->attach($add);
            } 

            if($rem){// Remover
               $credito->subsidios()->detach($rem);
            }
            
            $credito->no_subsidio_fed      = ($request->subsidio_fed == "") ? NULL : $request->subsidio_fed;
            $credito->no_subsidio_est      = ($request->subsidio_est == "") ? NULL : $request->subsidio_est;
            $credito->no_subsidio_mun      = ($request->subsidio_mun == "") ? NULL : $request->subsidio_mun;
            $credito->no_subsidio_otr      = ($request->subsidio_otr == "") ? NULL : $request->subsidio_otr;
            $credito->taza_interes         = $request->tasa / 100;
            $credito->costo_contado        = $request->costo_contado;
            $credito->costo_financiamiento = $request->costo_finan;
            $credito->tabla                = $request->tabla_cobros;
         }

         $credito->plazo                = $request->plazo;
         $credito->enganche             = $request->enganche;
         $credito->moratorio            = $request->moratorio / 100;
         $credito->total_pagar          = $request->pago_total;
         $credito->fecha_inicio         = $request->fecha_inicio;
         $credito->pago_mensual         = $request->pago_mensual;
         $credito->observaciones        = $request->observaciones;

         //Eliminar las mensualidades anterior
         $credito->mensualidades->each(function($mensualidad,$pos){
            $mensualidad->destroy($mensualidad->clave_mensualidad);
         });

         $credito->actualizado           = \Auth::user()->usuario;

         $credito->save();

         // ELIMINAR CORRIDA ANTES DE CREAR LA NUEVA

         /* Almacenar Corrida en la Base de Datos */
         $v_vencimiento  = Carbon::parse($credito->fecha_inicio);
         $v_plazo        = $credito->plazo;
         $v_pago_mensual = $request->pago_mensual;
         $v_resto        = $request->pago_total;

         if ($t_plantilla == "1" || $t_plantilla == "2") {
            $v_tasa         = $credito->taza_interes / 12;
            $v_saldo        = $credito->costo_contado;
            $v_intereses    = $v_tasa * $v_saldo;
            $v_capital      = $v_pago_mensual - $v_intereses;
         }

         for ($mensualidad = 0; $mensualidad < $v_plazo ; $mensualidad ++) {
            $t_mensualidad  = new Mensualidad();

            $v_vencimiento = $v_vencimiento->addMonth();//Obtener el mes siguiente
            // Almacenar renglon
            $t_mensualidad->no_mensualidad = $mensualidad + 1;
            $t_mensualidad->clave_mensualidad = $credito->clave_credito . $t_mensualidad->no_mensualidad;
            $t_mensualidad->fecha_vencimiento = $v_vencimiento;

            if ($t_plantilla == "1" || $t_plantilla == "2") {
               $t_mensualidad->interes = $v_intereses;
               $t_mensualidad->capital = $v_capital;
               $t_mensualidad->saldo = $v_saldo;
            }

            $t_mensualidad->credito_clave = $credito->clave_credito;
            $t_mensualidad->resto = $v_resto - $v_pago_mensual;
            // $t_mensualidad->resto = $v_resto;

            $t_mensualidad->save();
            //Calcular
            if ($t_plantilla == "1" || $t_plantilla == "2") {
               $v_saldo -= $v_capital;
               $v_intereses = $v_tasa * $v_saldo;
               $v_capital = $v_pago_mensual - $v_intereses;
            }
            $v_resto -= $v_pago_mensual;
         }

         return view('sistema_interno.Creditos.clave_credito')->with('credito',$credito);
      }

      public function index_cancelacion(Request $request){
         $cre = Credito::buscar($request->buscar,$request->tipo)->where('credito.estatus','=','PAGANDOLA')->orWhere('credito.estatus','=','CONGELADA')/*->orWhere('credito.estatus','=','PAGADA')*/->paginate(10);
         return view('sistema_interno.Creditos.index_cancelacion')->with('creditos',$cre);
      }

      public function add_cancelacion(Request $request){
         //dd($request);
         $can = new Cancelacion();

         $can->folio         = $request->folio;
         $can->usuario_id    = 0;//Falta
         $can->descripcion   = $request->descripcion;
         $can->importe       = $request->importe;
         //$can->no_cheque     = $request->no_cheque;
         $can->credito_clave = $request->credito;
         $can->registrado    = \Auth::user()->usuario;
         $can->save();//guardar cancelacion

         $cre = Credito::find($request->credito);
         //cambiar estatus
         $cre->estatus          = "CANCELADA";
         $cre->demanda->estatus = "CANCELADA";
         //guardar
         $cre->demanda->save();//guardar modificacion del estatus y tipo_cliente de la demanda
         if ($cre->plantilla == 1 || $cre->plantilla == 3 || $cre->plantilla == 4) {
              $cre->lote->estatus = "DISPONIBLE";
              $cre->lote->save();//guardar modificacion del estatus del lote 
         }

         $cre->save();//guardar modificacion del estatus del credito

         Flash::success("Se ha cancelado el crédito correctamente.");
         return redirect()->route('cancelacion_index');
      }

      /* Reestructura */
      public function reestructura_index(Request $request){
         return view('sistema_interno.Reestructura.index');
      }

      public function reestructura_form($clave){

         // Buscar Credito
         $credito       = Credito::find($clave);
         // Buscar "-" Si lo encuentra significa que tiene mas de una reestructura, sino -> es la primera
         $pos           = strpos($credito->clave_credito, '-');
         $v_credito     = ($pos === false) ? $credito->clave_credito : substr($credito->clave_credito, 0, $pos);
         // Buscar el numero de reestructuras que a tenido la clave original, y tambien por si viene por una reestructura de sistema SAIV
         $no_rees       = \DB::table('credito')->whereRaw("credito.clave_credito LIKE '%$v_credito%'")->count();
         $no_saiv       = \DB::table('credito')->select('saiv')->whereRaw("credito.clave_credito LIKE '%$v_credito%'")->first();
         $no_saiv       = ($no_saiv->saiv > 0) ? $no_saiv->saiv + 1 : 0;
         // Generar nueva clave del credito a reestructurar en base a lo anterior
         $nueva_clave   = $v_credito."-RE".($no_rees + $no_saiv);
         // Identificar las mensualidades (No pagadas, Vencidas)
         $no_pagados    = \DB::table('mensualidad')->where('credito_clave','=',$clave)->where('estatus','=','NO PAGADO')->count();
         $vencidos      = \DB::table('mensualidad')->where('credito_clave','=',$clave)->where('estatus','=','VENCIDO')->count();
         // Traer mensualidades con el resto y saldo para calcular el capital (resto) vencido menos el financiamiento (saldo) -> si no existen Vencidos traer los No Pagados
         $mens_datos    = \DB::table('mensualidad')->select('resto','saldo')->where('credito_clave','=',$clave)->where('estatus','=','VENCIDO')->first();
         if(!$mens_datos){
            $mens_datos    = \DB::table('mensualidad')->select('resto','saldo')->where('credito_clave','=',$clave)->where('estatus','=','NO PAGADO')->first();
         }
         // Traer los intereses total de las mensualidades vencidas -> sino existen Vencidos enviar un cero
         $interes_venc  = \DB::table('mensualidad')->select('interes')->where('credito_clave','=',$clave)->where('estatus','=','VENCIDO')->sum('interes');
         $interes_venc  = ($interes_venc) ? $interes_venc : 0;
         // Traer todas las mensualidades del credito que se desea reestructurar
         $mensualidades = \DB::table('mensualidad')->select('*')->where('credito_clave','=',$clave)->orderBy('no_mensualidad')->get();
         //Formato fecha de vencimiento a las mensualidades
         foreach ($mensualidades as $mensual) {
            $mensual->fecha_vencimiento = Carbon::parse($mensual->fecha_vencimiento)->format('d-m-Y');
         }
         // Si hay vencidos traer el total del resto (capital c/financiamiento) vencido y calcular el moratorio -> si no existen mensualidades vencidas gener el capital y moratorios con cero
         if($vencidos > 0){
            $fecha_venc       = \DB::table('mensualidad')->select('fecha_vencimiento','resto')->where('credito_clave','=',$clave)->where('estatus','=','VENCIDO')->first();
            $dias             = \DB::table('mensualidad')->select(\DB::raw("datediff('".date('Y-m-d')."','$fecha_venc->fecha_vencimiento') as dias"))->first();
            $total_moratorios = ((floatval($credito->pago_mensual) * floatval($credito->moratorio)) / 30) * floatval($dias->dias);
            $capital_vencido  = $fecha_venc->resto;
         } else {
            $total_moratorios = 0;
            $capital_vencido  = 0;
         }
         
         return view('sistema_interno.Reestructura.nuevo')->with('credito',$credito)
                                                            ->with('nueva_clave',$nueva_clave)
                                                            ->with('mens_no_pagados',$no_pagados)
                                                            ->with('mens_vencidos',$vencidos)
                                                            ->with('mensualidades',$mensualidades)
                                                            ->with('total_moratorios',$total_moratorios)
                                                            ->with('capital_vencido',$capital_vencido)
                                                            ->with('interes_venc',$interes_venc)
                                                            ->with('mens_credito',$mens_datos);
      }

      public function reestructura_saiv(){
         $modulo     = enums('demanda','modulo');
         $programa   = Programa::where('estatus','=','1')->orderBy('nombre')->get();
         $clientes   = Cliente::where('estatus','=','1')->orderBy('nombre')->get();
         $est        = Estado::where('estatus','=','1')->orderBy('nombre')->get();
         $demandas   = Demanda::leftJoin('credito','credito.demanda_id','=','demanda.id_demanda')->join('cliente','cliente.id_cliente','=','demanda.cliente_id')->join('tipo_programa','tipo_programa.id_tipo_programa','=','demanda.tipo_programa_id')->select('demanda.id_demanda','tipo_programa.nombre as tipo','cliente.nombre','cliente.ape_paterno','cliente.ape_materno')->where('demanda.created_at','=',date('Y-m-d'))->where('demanda.tipo_cliente','=','ACREDITADO')->where('demanda.estatus','=','EN PROCESO')->whereRaw('credito.clave_credito is null')->get();
         #dd($demandas);
         // $array_list = ["2017-05-20","2017-07-20","2017-12-20","2018-04-20"];
         // $list = collect($array_list)->implode('|');
         // dd($list);
         return view('sistema_interno.Reestructura.saiv')->with('modulos',$modulo)
                                                         ->with('programas',$programa)
                                                         ->with('estados',$est)
                                                         ->with('clientes',$clientes)
                                                         ->with('demandas',$demandas);
      }

      public function reestructura_add(Request $request, $saiv = false){
         //dd($request);
         if(! $saiv){
            $anterior = Credito::find($request->vieja_clave);
            if ($anterior) {
               $anterior->estatus = "CANCELADA/REESTRUCTURA";
               $anterior->save();
            }
         }
         
         $credito  = new Credito();
         $credito->clave_credito        = $request->nueva_clave;
         $credito->valor_solucion       = $request->total_reestructurar;
         $credito->taza_interes         = $request->tasa / 100;
         $credito->plazo                = $request->plazo;
         $credito->moratorio            = $request->moratorio / 100;
         $credito->costo_contado        = $request->costo_contado;
         $credito->costo_financiamiento = $request->costo_finan;
         $credito->pago_mensual         = $request->pago_mensual;
         $credito->total_pagar          = $request->pago_total;
         $credito->fecha_inicio         = $request->fecha_inicio;
         $credito->tabla                = $request->tabla_cobros;
         $credito->observaciones        = $request->observaciones;
         $credito->reestructura_id      = $request->vieja_clave;
         $credito->demanda_id           = $request->id_demanda;
         $credito->registrado           = \Auth::user()->usuario;

         if(! $saiv){
            $credito->costo_metro          = $anterior->costo_metro;
            $credito->costo_terreno        = $anterior->costo_terreno;
            $credito->costo_construccion   = $anterior->costo_construccion;
            $credito->plantilla            = $anterior->plantilla;
            if($anterior->demanda->tipo_programa->plantilla == "1"){
               $credito->lote_id = $anterior->lote_id;
            }
         } else {

            $demanda = Demanda::find($request->id_demanda);

            $SaivLote = Lote::find($request->lote_saiv);
            $SaivLote->estatus = "ASIGNADO";
            $SaivLote->save();

            $credito->plantilla   = $demanda->plantilla;
            $credito->saiv        = $request->cantidad;
            $credito->fechas_saiv = collect($request->fechas)->implode('|');
            $credito->lote_id     = $request->lote_saiv;
         }

         $credito->save();

         /* Almacenar Corrida en la Base de Datos */
         $v_vencimiento  = Carbon::parse($credito->fecha_inicio);
         $v_plazo        = $credito->plazo;
         $v_pago_mensual = $request->pago_mensual;
         $v_resto        = $request->pago_total;

         $v_tasa         = $credito->taza_interes / 12;
         $v_saldo        = $credito->costo_contado;
         $v_intereses    = $v_tasa * $v_saldo;
         $v_capital      = $v_pago_mensual - $v_intereses;

         for ($mensualidad = 0; $mensualidad < $v_plazo ; $mensualidad ++) {
            $t_mensualidad = new Mensualidad();

            $v_vencimiento                    = $v_vencimiento->addMonth();//Obtener el mes siguiente

            // Almacenar renglon
            $t_mensualidad->no_mensualidad    = $mensualidad + 1;
            $t_mensualidad->clave_mensualidad = $credito->clave_credito . $t_mensualidad->no_mensualidad;
            $t_mensualidad->fecha_vencimiento = $v_vencimiento;

            $t_mensualidad->interes           = $v_intereses;
            $t_mensualidad->capital           = $v_capital;
            $t_mensualidad->saldo             = $v_saldo;

            $t_mensualidad->credito_clave     = $credito->clave_credito;
            $t_mensualidad->resto             = $v_resto - $v_pago_mensual;

            $t_mensualidad->save();

            //Calcular
            $v_saldo    -= $v_capital;
            $v_intereses = $v_tasa * $v_saldo;
            $v_capital   = $v_pago_mensual - $v_intereses;
            $v_resto    -= $v_pago_mensual;
         }

         Flash::success("Se a reestructurado correctamente");

         // REDIRECCIONAR A LA VISTA CLAVE_CREDITO
         return view('sistema_interno.Creditos.clave_credito')->with('credito',$credito);
         //return redirect()->route('insuvi');
      }

      public function index_solicitud(){
         //$creditos = Credito::where('estatus','=','PAGANDOLA')->where('created_at','=',date('Y-m-d'))->get();
         $creditos = \DB::select("SELECT cr.clave_credito
                              FROM credito cr
                              WHERE cr.reestructura_id != '0' 
                              and (SELECT count(*)
                                    FROM credito c
                                    join mensualidad m on m.credito_clave = c.clave_credito
                                    WHERE c.clave_credito = cr.clave_credito
                                    ) = (SELECT count(*)
                                          FROM credito c
                                          join mensualidad m on m.credito_clave = c.clave_credito
                                          where m.estatus = 'NO PAGADO'
                                          and c.clave_credito = cr.clave_credito)
                              and cr.estatus = 'PAGANDOLA'
                              group by cr.clave_credito
                              order by cr.created_at");
         return view('sistema_interno.Solicitud.nuevo')->with('creditos',$creditos);
      }

      public function add_solicitud(Request $request){

         $soli = new SolicitudEliminacion();
         $soli->descripcion = $request->concepto;
         $soli->usuario     = \Auth::user()->usuario;
         $soli->credito     = $request->credito;
         $soli->save();

         return redirect()->route('insuvi');
      }

      public function aprobacion_solicitud($aprobacion,$solicitud,$clave = 0){
         $soli = SolicitudEliminacion::find($solicitud);
         if ($aprobacion) {
            $cre   = Credito::find($clave);
            // Cambiar estatus del credito anterior
            $a_cre = Credito::find($cre->reestructura_id);
            $a_cre->estatus = "PAGANDOLA";
            $a_cre->save();
            // Borrar las mensualidades del credito
            $cre->mensualidades->each(function($mensualidad,$pos){
               $mensualidad->destroy($mensualidad->clave_mensualidad);
            });
            // Borrar credito de la solicitud
            $cre->delete();

            $soli->status = "APROBADO";
         } else{
            $soli->status = "NO APROBADO";
         }
         $soli->responsable = \Auth::user()->usuario;
         $soli->save();

         return redirect()->route('insuvi');
      }

      public function creditos_virgen(){
         $cre = \DB::select("SELECT cr.demanda_id, cr.clave_credito, p.nombre as programa, tp.nombre as tipo, cl.curp, cl.nombre, cl.ape_materno, cl.ape_paterno, cr.created_at
                              FROM credito cr
                              join mensualidad m on m.credito_clave = cr.clave_credito
                              join demanda d on d.id_demanda = cr.demanda_id
                              join cliente cl on cl.id_cliente = d.cliente_id
                              join tipo_programa tp on tp.id_tipo_programa = d.tipo_programa_id
                              join programa p on p.id_programa = tp.programa_id
                              WHERE cr.reestructura_id = '0' 
                              and (SELECT count(*)
                                    FROM credito c
                                    join mensualidad m on m.credito_clave = c.clave_credito
                                    WHERE c.clave_credito = cr.clave_credito
                                    ) = (SELECT count(*)
                                          FROM credito c
                                          join mensualidad m on m.credito_clave = c.clave_credito
                                          where m.estatus = 'NO PAGADO'
                                          and c.clave_credito = cr.clave_credito)
                              and cr.estatus = 'PAGANDOLA'
                              group by cr.clave_credito
                              order by cr.created_at");
         //dd($cre);
         return view('sistema_interno.Creditos.creditos_modificables')->with('creditos',$cre);
      }

}