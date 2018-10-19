<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use insuvi\Http\Requests;
use insuvi\Caja;
use insuvi\Ahorrador;
use insuvi\AbonoAhorrador;
use insuvi\AbonoCredito;
use insuvi\Credito;
use insuvi\Mensualidad;
use insuvi\DatosEmpresa;
use insuvi\Seguimiento;
use insuvi\AbonoConvenio;
use insuvi\Descuento;
use Carbon\Carbon;

class CobranzaController extends Controller
{
      // View -> Search
      public function index_creditos(Request $request, $descuentos = 0, $busqueda = 0)
   	{
         //dd(\Auth::user()->caja->folio_actual);
         if ($busqueda) {
            $cre = Credito::join('demanda','demanda.id_demanda','=','credito.demanda_id')
                           ->join('tipo_programa','tipo_programa.id_tipo_programa','=','demanda.tipo_programa_id')
                           ->join('cliente','demanda.cliente_id','=','cliente.id_cliente')
                           ->select('credito.clave_credito','credito.estatus','cliente.nombre','cliente.ape_paterno','cliente.ape_materno','cliente.curp','cliente.id_cliente','tipo_programa.nombre as programa')
                           ->Caja($request->buscar, $request->tipo)
                           ->where('credito.estatus','!=','CANCELADA/REESTRUCTURA')
                           ->where('credito.estatus','!=','CANCELADA')
                           ->where('credito.estatus','!=','PAGADA')
                           ->orderBy('credito.estatus')
                           ->get();

            $aho = Ahorrador::join('demanda','demanda.id_demanda','=','ahorrador.demanda_id')
                           ->join('tipo_programa','tipo_programa.id_tipo_programa','=','demanda.tipo_programa_id')
                           ->join('cliente','demanda.cliente_id','=','cliente.id_cliente')
                           ->select('ahorrador.clave_ahorrador','ahorrador.pagado','cliente.nombre','cliente.ape_paterno','cliente.ape_materno','cliente.curp','cliente.id_cliente','tipo_programa.nombre as programa')
                           ->Caja($request->buscar, $request->tipo)
                           ->where('ahorrador.pagado','=',0)
                           ->where('ahorrador.eliminado','=',0)
                           ->get();
           //dd($cre);
      	  return view('sistema_interno.cobranza.caja')->with('creditos',$cre)->with('ahorradores',$aho)->with('busqueda',$busqueda)->with('descuentos',$descuentos);
         }
         return view('sistema_interno.cobranza.caja')->with('busqueda',$busqueda)->with('descuentos',$descuentos);
   	}

      // View -> Pagos
      public function pagos_credito($credito,$tipo,$reestructurado = "")
      {
         if (!\Auth::user()->caja) {
            Flash::error("Debe tener asignado una caja para poder realizar este proceso.");
            return redirect()->route('insuvi');
         }
         
         $modulos   = \Auth::user()->modulos;
         $caja  = str_contains($modulos,'CAJA');
         $admon = str_contains($modulos,'ADMON');
         if ($caja || $admon) {
            $lim = DatosEmpresa::where('estatus',1)->first();
            //dd($lim);
            //$total_moratorios = 0;
            $v_descuentos = [];
            if ($tipo != "Credito") {
               $pago = Ahorrador::find($credito);
            } else {
               $pago = Credito::find($credito);

               // $vencidos = \DB::table('mensualidad')->where('credito_clave','=',$credito)->where('estatus','=','VENCIDO')->count();
               // if($vencidos){
               //    $fecha_venc       = \DB::table('mensualidad')->select('fecha_vencimiento')->where('credito_clave','=',$credito)->where('estatus','=','VENCIDO')->first();
               //    $dias             = \DB::table('mensualidad')->select(\DB::raw("datediff('".date('Y-m-d')."','$fecha_venc->fecha_vencimiento') as dias"))->first();
               //    //$suma_abonos      = Credito::find($credito)->mensualidades->abonos_credito->sum('abono');
               //    //dd($suma_abonos);
               //    $total_moratorios = ((floatval($pago->pago_mensual) * floatval($pago->moratorio)) / 30) * floatval($dias->dias);
               // }
               $v_mensualidad;
               $GLOBALS['mensualidades'] = [];
               $GLOBALS['estatus']       = [];
               $pago->descuentos->each(function($descuento,$pos){
                  if ($descuento->estatus === "DISPONIBLE") {
                     $GLOBALS['mensualidades'][$descuento->id_descuento] = explode("|", $descuento->mensualidades);
                     $GLOBALS['estatus'][$descuento->id_descuento] = explode("|", $descuento->estatus_descuentos);
                     //array_push($GLOBALS['mensualidades'], explode("|", $descuento->mensualidades));
                     //array_push($GLOBALS['estatus'], explode("|", $descuento->estatus_descuentos));
                     //array_push($GLOBALS['descuentos'], explode("|", $descuento->mensualidades));
                  }
               });
               //dd($GLOBALS['estatus'][2][0]);
               foreach ($GLOBALS['mensualidades'] as $id => $mensualidades) {
                  foreach ($mensualidades as $pos => $mensualidad) {
                     $v_descuentos[$mensualidad] = [$id,$GLOBALS['estatus'][$id][$pos]];
                  }
               }
               //$GLOBALS['descuentos'] = collect($GLOBALS['descuentos'])->collapse();
               //$GLOBALS['mensualidades'] = collect($GLOBALS['mensualidades'])->collapse();
               //$GLOBALS['estatus']       = collect($GLOBALS['estatus'])->collapse();

               /*foreach ($GLOBALS['mensualidades'] as $pos => $valor) {
                  $v_descuentos[$valor] = $GLOBALS['estatus'][$pos];
               }*/
               //dd($v_descuentos);
            }
            $tipo_limite = "monto";
            $limite      = $lim->limite_monto;
            if ($limite == 0) {
               $tipo_limite = "porcentaje";
               $limite      = $lim->limite_porcentaje;
            }
            //dd($tipo_limite);
            return view('sistema_interno.cobranza.pagos')->with('credito',$pago)
                                                         ->with('tipo_credito',$tipo)
                                                         ->with('tipo_limite',$tipo_limite)
                                                         //->with('moratorios',$total_moratorios)
                                                         ->with('mensaje',$reestructurado)
                                                         ->with('descuentos',$v_descuentos)
                                                         ->with('limite',$limite);
         } else {
            return redirect()->route('insuvi')->withErrors('Acceso Denegado');
         }
      }

      // View -> Descuentos
      public function descuentos_credito($credito)
      {
         $modulos   = \Auth::user()->modulos;
         $caja = str_contains($modulos,'DESCUENTO');
         $admon = str_contains($modulos,'ADMON');
         if ($caja || $admon) {
            $GLOBALS['descuentos'] = [];
            $m_credito = Credito::find($credito);
            $m_credito->descuentos->each(function($descuento,$pos){
               if ($descuento->estatus === "DISPONIBLE") {
                  array_push($GLOBALS['descuentos'], explode("|", $descuento->mensualidades));
               }
            });
            $GLOBALS['descuentos'] = collect($GLOBALS['descuentos'])->collapse();
            //dd($GLOBALS['descuentos']);
            return view('sistema_interno.Descuentos.descuento')->with('credito',$m_credito)->with('descuentos',$GLOBALS['descuentos']);
         } else {
            return redirect()->route('insuvi')->withErrors('Acceso Denegado');
         }
      }

      public function AplicarDescuento(Request $request, $credito){
         //dd($request);
         /* CONOCER CANTIDAD DE DESCUENTO QUE ALCANZA A CUBRIR LAS MENSUALIDADES SELECCIONADAS */
         $descuento_capital   = $this->DescuentosMensualidades($request->capital, $request->PlazoCapital, $request->pagos);
         $descuento_interes   = $this->DescuentosMensualidades($request->interes, $request->PlazoInteres, $request->pagos);
         $descuento_moratorio = $this->DescuentosMensualidades($request->moratorio, $request->moratorios, $request->pagos);

         /* DESCUENTO REAL */
         $MensualidadesCubiertas = [];
         $CapitalCubierto        = [];
         $InteresCubierto        = [];
         $MoratorioCubierto      = [];
         $EstatusMensualidades   = [];

         foreach ($request->pagos as $pos => $no_mensualidad) {
            if ($descuento_capital[$pos] != 0 || $descuento_interes[$pos] != 0 || $descuento_moratorio[$pos] != 0) {
               /* GUARDAR DESCUENTOS REALES */
               array_push($MensualidadesCubiertas, $no_mensualidad);
               array_push($CapitalCubierto, $descuento_capital[$pos]);
               array_push($InteresCubierto, $descuento_interes[$pos]);
               array_push($MoratorioCubierto, $descuento_moratorio[$pos]);
               array_push($EstatusMensualidades, "DISPONIBLE");

               /* ALAMCENAR DESCUENTOS EN LAS MENSUALIDADES REALES*/
               $m_mensualidad = Mensualidad::find($credito.$no_mensualidad);
               $m_mensualidad->descuento_capital   = $descuento_capital[$pos];
               $m_mensualidad->descuento_interes   = $descuento_interes[$pos];
               $m_mensualidad->descuento_moratorio = $descuento_moratorio[$pos];
               $m_mensualidad->save();
            }
         }

         $m_descuento = new Descuento();
         $m_descuento->observaciones  = $request->observaciones;
         $m_descuento->vigencia       = $request->vigencia;
         $m_descuento->credito_clave  = $credito;
         $m_descuento->desc_capital   = collect($CapitalCubierto)->implode('|');
         $m_descuento->desc_interes   = collect($InteresCubierto)->implode('|');
         $m_descuento->desc_moratorio = collect($MoratorioCubierto)->implode('|');
         $m_descuento->mensualidades  = collect($MensualidadesCubiertas)->implode('|');
         $m_descuento->estatus_descuentos = collect($EstatusMensualidades)->implode('|');
         $m_descuento->registrado         = \Auth::user()->usuario;
         $m_descuento->save();

         return redirect()->route('caja',1);
      }

      public function PagoConvenio(Request $request,$seguimiento){

            $m_seguimiento = Seguimiento::find($seguimiento);
            //dd($m_seguimiento);
            $m_seguimiento->abonado_convenio += $request->pago;
            if ($m_seguimiento->monto_convenio == $m_seguimiento->abonado_convenio) {
               $m_seguimiento->convenio_pagado = 1;
            }
            $m_seguimiento->save();

            $pago = new AbonoConvenio();
            $pago->folio           = \Auth::user()->caja->folio_actual;
            $pago->no_pago         = $m_seguimiento->abonos_convenio->count() + 1;
            $pago->fecha           = Carbon::now();
            $pago->abono           = $request->pago;
            $pago->seguimiento_id  = $seguimiento;
            $pago->registrado      = \Auth::user()->usuario;
            $pago->save();
            
            \Auth::user()->caja->folio_actual += 1;
            \Auth::user()->caja->save();

            return redirect()->route('caja');
      }

      public function PagoAhorrador(Request $request,$credito){

            $ahorrador = Ahorrador::find($credito);
            //dd($ahorrador);
            $ahorrador->total_abonado += $request->abono;
            if ($ahorrador->monto_total == $ahorrador->total_abonado) {
               $ahorrador->pagado = 1;
               $ahorrador->demanda->estatus = "PREPARADO";
               $ahorrador->demanda->tipo_cliente = "SOLICITANTE";
               $ahorrador->demanda->save();
            }
            $ahorrador->save();

            $pago = new AbonoAhorrador();
            $pago->folio           = \Auth::user()->caja->folio_actual;
            $pago->no_pago         = $ahorrador->abonos_ahorrador->count() + 1;
            $pago->fecha           = Carbon::now();
            $pago->abono           = $request->abono;
            $pago->ahorrador_clave = $credito;
            $pago->registrado      = \Auth::user()->usuario;
            $pago->save();

            \Auth::user()->caja->folio_actual += 1;
            \Auth::user()->caja->save();

            return redirect()->route('caja');
      }

      public function PagoCredito(Request $request,$credito){
         //dd($request);
         if ($request->reestructura == "NINGUNO") {
            switch ($request->forma_pago) {
               case 'contado':
                  $this->PagoCompletoCredito($request->pagos, $credito, $request->moratorios);
                  break;
               case 'abono':
                  $this->AbonoMensualidadCredito($credito.$request->pagos[0], $request->moratorios[0], $request->abono_credito);
                  break;
               case 'pago_mensual':
                  $this->PagoMensualidadesCompletas($credito, $request->pagos, $request->moratorios);
                  break;
            }
            return redirect()->route('pagos_credito',['credito'=>$credito,'tipo'=>'Credito']);
         }     

         // Entra a reestructura
         if ($request->tabla_cobro === "4") {
            $clave_nueva = $this->ReestructurarCredito($request->pagos, $credito, $request->contado, $request->reestructura, $request->PlazoCapital);

            return redirect()->route('pagos_credito',['credito'=>$credito,'tipo'=>'Credito','reestructurado'=>$clave_nueva]);
         }
      }

      function PagoCompletoCredito($mensualidades, $credito, $moratorios){
         $GLOBALS['moratorios'] = $moratorios;
         $GLOBALS['total']      = 0;
         
         foreach ($mensualidades as $pos => $no_mensualidad) {
            $mensualidad = Mensualidad::find($credito.$no_mensualidad);
            $mensualidad->estatus = "PAGADO";
            $mensualidad->save();
            $GLOBALS['total'] += (float)$mensualidad->capital;

            // Abono Completo -> CAPITAL
            $pago = new AbonoCredito();
            $pago->no_pago           = $mensualidad->no_mensualidad . " - " . ($mensualidad->abonos_credito->count() + 1);
            $pago->folio             = \Auth::user()->caja->folio_actual;
            $pago->registrado        = \Auth::user()->usuario;
            $pago->fecha             = Carbon::now();
            $pago->abono             = $mensualidad->capital + $GLOBALS['moratorios'][$pos];
            $pago->mensualidad_clave = $mensualidad->clave_mensualidad;
            $pago->interes           = "0";
            $pago->capital           = $mensualidad->capital /*+ $mensualidad->interes*/;
            $pago->moratorio         = $GLOBALS['moratorios'][$pos];
            $pago->save();
         }

         \Auth::user()->caja->folio_actual += 1;
         \Auth::user()->caja->save();

         $cre = Credito::find($credito);
         // Cambiar Estatus Credito
         $cre->estatus       = "PAGADA";
         $cre->total_abonado += $GLOBALS['total'];
         if ($cre->lote_id != 0 && $cre->plantilla == "1") {
            $cre->lote->estatus = "PAGADO";
            $cre->lote->save();
         }
         if ($cre->lote_id != 0 && $cre->plantilla == "3") {
            $cre->lote->estatus = "ESCRITURADO";
            $cre->lote->save();
         }
         $cre->save();
         
      }

      function AbonoMensualidadCredito($mensualidad, $moratorio, $abono){
         //dd("LOL");
         $mens = Mensualidad::find($mensualidad);

         $abonos_moratorios = $mens->abonos_credito->sum('moratorio');
         $abonos_interes    = $mens->abonos_credito->sum('interes');
         $abonos_capital    = $mens->abonos_credito->sum('capital');
         $abonos_total      = $mens->abonos_credito->sum('abono');
         $c_mensualidad     = ($mens->credito->pago_mensual - ($abonos_total - $abonos_moratorios));
         $abono_real        = $abono - $moratorio;

         $v_interes         = ($mens->interes - $abonos_interes);
         $v_capital         = ($mens->capital - $abonos_capital);

         $abonomon          = ($abono_real / $c_mensualidad) * $moratorio;
         //$abonomon          = ($abono / $c_mensualidad) * $moratorio;
         $porcen_interes    = $v_interes / $c_mensualidad;
         $porcen_capital    = $v_capital / $c_mensualidad;
         //dd($abonomon);
            $pago = new AbonoCredito();
            $pago->no_pago           = $mens->no_mensualidad . " - " . ($mens->abonos_credito->count() + 1);
            $pago->folio             = \Auth::user()->caja->folio_actual;
            $pago->registrado        = \Auth::user()->usuario;
            $pago->fecha             = Carbon::now();
            $pago->abono             = $abono;
            $pago->mensualidad_clave = $mens->clave_mensualidad;
            $pago->interes           = ($abono - $abonomon) * $porcen_interes;
            $pago->capital           = ($abono - $abonomon) * $porcen_capital;
            $pago->moratorio         = $abonomon;
            $pago->save();

            \Auth::user()->caja->folio_actual += 1;
            \Auth::user()->caja->save();

         if ($mens->estatus != "ABONADO" && $mens->estatus != "VENCIDO") {
            $mens->estatus = "ABONADO";
         }

         //dd($pago);
         $mens->save();
         $mens = Mensualidad::find($mensualidad);
         $t_abonados = $mens->abonos_credito->sum('abono') - $mens->abonos_credito->sum('moratorio');
         //dd("-> Total Abonado: ".ceil($t_abonados)." -> Pago Mensual: ".$mens->credito->pago_mensual);
         //$t_abonados = $mens->abonos_credito->sum('interes') + $mens->abonos_credito->sum('capital') + $mens->abonos_credito->sum('moratorio');
         if (ceil($t_abonados) >= $mens->credito->pago_mensual) {
            //dd("YA PAGO TODO!");
            $mens->estatus = "PAGADO";
               $mens->credito->total_abonado += $mens->credito->pago_mensual;
               $mens->credito->save();
            $mens->save();
         }
      }

      function PagoMensualidadesCompletas($credito, $mensualidades, $moratorios){
         $total = 0;
         $m_credito = Credito::find($credito);
         foreach ($mensualidades as $pos => $mens) {
            $m_mensualidad = Mensualidad::find($credito.$mens);
            $m_mensualidad->estatus = "PAGADO";
            $m_mensualidad->save();

            $pago = new AbonoCredito();
            $pago->no_pago           = $m_mensualidad->no_mensualidad . " - " . ($m_mensualidad->abonos_credito->count() + 1);
            $pago->folio             = \Auth::user()->caja->folio_actual;
            $pago->registrado        = \Auth::user()->usuario;
            $pago->fecha             = Carbon::now();
            $pago->abono             = $m_mensualidad->credito->pago_mensual + $moratorios[$pos];
            $pago->mensualidad_clave = $m_mensualidad->clave_mensualidad;
            $pago->interes           = $m_mensualidad->interes;
            $pago->capital           = $m_mensualidad->capital;
            $pago->moratorio         = $moratorios[$pos];
            $pago->save();
            $total += $m_mensualidad->credito->pago_mensual;
         }

         \Auth::user()->caja->folio_actual += 1;
         \Auth::user()->caja->save();

         $m_credito->total_abonado += $total;
         $m_credito->save();
      }

      // SOLO PUEDEN HACER REESTRUCTURA TABLA 4 Y SIN TENER VENCIDO NINGUNA MENSUALIDAD
      function ReestructurarCredito($pagos, $credito, $contado, $tipo, $capital){ 
         /* CAMBIAR ESTATUS DEL CREDITO */
         $m_credito = Credito::find($credito);
         $m_credito->estatus = "CANCELADA/REESTRUCTURA";
         $m_credito->save();

         /* PAGO REFLEJADO EN -> TOTAL PAGAR */
         $total_pagar_caja = 0;
         for ($i = 0; $i < sizeof($pagos); $i++) { 
            $total_pagar_caja += $m_credito->pago_mensual;
         }

         /* CONOCER PLAZOS Y EL PAGO REAL CUBIERTO */
         $plazos_cubiertos = 0;
         $total_cubierto   = 0;
         foreach ($capital as $pos => $cantidad) {
            //echo $cantidad . "<br>";
            $total_cubierto += $cantidad;
            if ($total_cubierto > $total_pagar_caja) {
               $plazos_cubiertos = $pos - 1;
               $total_cubierto   -= $cantidad;
               break;
            }
         }

         /* CONOCER EL NUMERO INICIAL DE LA MENSUALIDAD */
         $mensualidad_inicio  = reset($pagos);

         /* REGISTRAR PAGOS DE PURO CAPITAL DE LAS MENSUALIDADES QUE ALCANZA A CUBRIR */
         for ($i = 0; $i <= $plazos_cubiertos; $i++) { 
            //echo $credito . ($mensualidad_inicio+$i) . "<br>";
            $clave_mensualidad = $credito . ($mensualidad_inicio + $i);
            $m_mensualidad     = Mensualidad::find($clave_mensualidad);
            $m_mensualidad->estatus = "PAGADO";
            $m_mensualidad->save();

            $m_abonos = new AbonoCredito();
            $m_abonos->no_pago           = $m_mensualidad->no_mensualidad . " - " . ($m_mensualidad->abonos_credito->count() + 1);
            $m_abonos->folio             = \Auth::user()->caja->folio_actual;
            $m_abonos->registrado        = \Auth::user()->usuario;
            $m_abonos->fecha             = Carbon::now();
            $m_abonos->abono             = $m_mensualidad->capital;
            $m_abonos->mensualidad_clave = $clave_mensualidad;
            $m_abonos->capital           = $m_mensualidad->capital;
            $m_abonos->save();
            $m_mensualidad->credito->total_abonado += $m_abonos->abono;
         }

         \Auth::user()->caja->folio_actual += 1;
         \Auth::user()->caja->save();

         /* GENERAR NUEVA CLAVE CREDITO */
         // Buscar "-" Si lo encuentra significa que tiene mas de una reestructura, sino -> es la primera
         $pos           = strpos($m_credito->clave_credito, '-');
         $v_credito     = ($pos === false) ? $m_credito->clave_credito : substr($m_credito->clave_credito, 0, $pos);
         // Buscar el numero de reestructuras que a tenido la clave original, y tambien por si viene por una reestructura de sistema SAIV
         $no_rees       = \DB::table('credito')->whereRaw("credito.clave_credito LIKE '%$v_credito%'")->count();
         $no_saiv       = \DB::table('credito')->select('saiv')->whereRaw("credito.clave_credito LIKE '%$v_credito%'")->first();
         $no_saiv       = ($no_saiv->saiv > 0) ? $no_saiv->saiv + 1 : 0;
         // Generar nueva clave del credito a reestructurar en base a lo anterior
         $nueva_clave   = $v_credito."-RE".($no_rees + $no_saiv);

         /* DIFERENCIA ENTRE EL PAGO A PAGAR CON EL PAGO REAL CUBIERTO -> SI SOBRA REGISTRAR ABONO EN LA PRIMERA MENSUALIDAD DEL NUEVO CREDITO */
         $abono_nuevo_credito  = $total_pagar_caja - $total_cubierto;
         /* DATOS PARA EL NUEVO */
         $nuevo_costo_contado  = $contado - $total_cubierto;
         $plazo_real           = ($tipo != "PLAZO") ? $m_credito->plazo : ($m_credito->plazo - ($plazos_cubiertos + $mensualidad_inicio));
         //$plazo_real           = ($tipo != "PLAZO") ? $m_credito->plazo : ($m_credito->plazo - ($plazos_cubiertos + 1));
         $v_interes            = $m_credito->taza_interes / 12;
         $nueva_mensualidad    = ($v_interes * $nuevo_costo_contado) / (1 - pow(1 + $v_interes, -$plazo_real));
         //$nueva_mensualidad    = ($v_interes * $nuevo_costo_contado) / (1 - pow(1 + $v_interes, -$m_credito->plazo));
         //$nuevo_total_pagar    = $m_credito->plazo * $nueva_mensualidad;
         $nuevo_total_pagar    = $plazo_real * $nueva_mensualidad;
         $nuevo_financiamiento = $nuevo_total_pagar - $nuevo_costo_contado;

         $mensaje = "REESTRUCTURA (AUTOMÁTICA) POR " . $tipo . ", QUE FUE CUBIERTO CON EL MONTO DE $" . number_format($total_pagar_caja,2);
         
         if ($abono_nuevo_credito > 0) {
            $mensaje .= " Y CON UN RESTO DE $" . number_format($abono_nuevo_credito,2) . " QUE SE ABONÓ EN LA SIGUIENTE MENSUALIDAD DEL CREDITO NUEVO - " . $nueva_clave;
         }

         $nuevo = new Credito();
         $nuevo->clave_credito         = $nueva_clave;
         $nuevo->costo_metro           = $m_credito->costo_metro;
         $nuevo->costo_terreno         = $m_credito->costo_terreno;
         $nuevo->costo_construccion    = $m_credito->costo_construccion;
         $nuevo->valor_solucion        = $m_credito->valor_solucion;
         //$nuevo->enganche            = $m_credito->enganche;
         $nuevo->costo_contado         = $nuevo_costo_contado;
         $nuevo->plazo                 = $plazo_real;
         $nuevo->taza_interes          = $m_credito->taza_interes;
         $nuevo->moratorio             = $m_credito->moratorio;
         $nuevo->plantilla             = $m_credito->plantilla;
         $nuevo->demanda_id            = $m_credito->demanda_id;
         $nuevo->lote_id               = $m_credito->lote_id;
         $nuevo->tabla                 = $m_credito->tabla;
         $nuevo->reestructura_id       = $credito;
         $nuevo->total_pagar           = $nuevo_total_pagar;
         $nuevo->pago_mensual          = $nueva_mensualidad;
         $nuevo->costo_financiamiento  = $nuevo_financiamiento;
         $nuevo->fecha_inicio          = Carbon::now();
         $nuevo->observaciones         = $mensaje;
         $nuevo->save();

         $v_vencimiento  = Carbon::parse($nuevo->fecha_inicio);
         $v_plazo        = $nuevo->plazo;
         $v_pago_mensual = $nuevo->pago_mensual;
         $v_resto        = $nuevo->total_pagar;

         $v_tasa         = $nuevo->taza_interes / 12;
         $v_saldo        = $nuevo->costo_contado;
         $v_intereses    = $v_tasa * $v_saldo;
         $v_capital      = $v_pago_mensual - $v_intereses;

         for ($mensualidad = 0; $mensualidad < $v_plazo ; $mensualidad ++) {
            $t_mensualidad  = new Mensualidad();

            $v_vencimiento = $v_vencimiento->addMonth();//Obtener el mes siguiente
            // Almacenar renglon
            $t_mensualidad->no_mensualidad    = $mensualidad + 1;
            $t_mensualidad->clave_mensualidad = $nuevo->clave_credito . $t_mensualidad->no_mensualidad;
            $t_mensualidad->fecha_vencimiento = $v_vencimiento;

            $t_mensualidad->interes = $v_intereses;
            $t_mensualidad->capital = $v_capital;
            $t_mensualidad->saldo   = $v_saldo;

            $t_mensualidad->credito_clave = $nuevo->clave_credito;
            $t_mensualidad->resto         = $v_resto /*- $v_pago_mensual*/;
            // $t_mensualidad->resto = $v_resto;

            $t_mensualidad->save();
            //Calcular
            $v_saldo -= $v_capital;
            $v_intereses = $v_tasa * $v_saldo;
            $v_capital = $v_pago_mensual - $v_intereses;
            $v_resto -= $v_pago_mensual;
         }
         
         if ($abono_nuevo_credito > 0) {
            $clv_mensualidad = $nuevo->mensualidades->first()->clave_mensualidad;
            $this->AbonoMensualidadCredito($clv_mensualidad, 0, $abono_nuevo_credito);
         }

         return $nuevo->clave_credito;
      }

      function DescuentosMensualidades($descuento, $plazos, $pagos){
         $descuento_real  = [];
         if ($descuento > 0) {
            $Cubierto        = false;
            foreach ($plazos as $pos => $cantidad) {
               if ($cantidad >= $descuento) {
                  if ($descuento > 0) {
                     array_push($descuento_real, floatval($descuento));
                     $descuento -= $cantidad;
                  } else{
                     array_push($descuento_real, 0);
                  }
                  $Cubierto = true;
               } elseif (!$Cubierto) {
                  $descuento -= $cantidad;
                  array_push($descuento_real, floatval($cantidad));
               } else {
                  array_push($descuento_real, 0);
               }

               if (($pos + 1) === sizeof($pagos)) {
                  break;
               }
            }
         } else {
            for ($i=0; $i < sizeof($pagos); $i++) { 
               array_push($descuento_real, 0);
            }
         }
         return $descuento_real;
      }
}
