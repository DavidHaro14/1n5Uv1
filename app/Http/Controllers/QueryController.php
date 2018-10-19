<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use insuvi\Http\Requests;
use insuvi\Municipio;
use insuvi\Localidad;
use insuvi\Colonia;
use insuvi\Calle;
use insuvi\PreRegistro;
use insuvi\Cliente;
use insuvi\Familiar;
use insuvi\Referencia;
use insuvi\TipoPrograma;
use insuvi\Lote;
use insuvi\Regularizacion;
use insuvi\Subsidio;
use insuvi\Demanda;
use insuvi\Credito;
use insuvi\Ahorrador;
use insuvi\AbonoAhorrador;
use insuvi\AbonoCredito;
use insuvi\Seguimiento;

class QueryController extends Controller
{

    /*
     * VALIDAR SI EXISTE EL NUMERO DE SUBSIDO
     */

    public function consultar($campo,$comparacion,$id_sub){
        $validar = Credito::leftJoin('credito_subsidio','credito_subsidio.credito_id','=','credito.clave_credito')
                                 ->leftJoin('subsidio','subsidio.id_subsidio','=','credito_subsidio.subsidio_id')
                                 ->where('subsidio.id_subsidio','=',$id_sub)
                                 ->where('credito.'.$campo,'=',$comparacion)
                                 ->count();
        return $validar;
    }

    public function SubsidiosValidacion(Request $request)
    {
        if($request->ajax()){
            switch ($request->tipo) {
                case 'OTROS':
                   $response = $this->consultar('no_subsidio_otr',$request->numero,$request->id_sub);
                   break;
                case 'ESTATAL':
                   $response = $this->consultar('no_subsidio_est',$request->numero,$request->id_sub);
                   break;
                case 'MUNICIPAL':
                   $response = $this->consultar('no_subsidio_mun',$request->numero,$request->id_sub);
                   break;
                case 'FEDERAL':
                   $response = $this->consultar('no_subsidio_fed',$request->numero,$request->id_sub);
                   break;
            }
            return response()->json($response);
        }
    }

    /*
     * OBTENER LOS MUNICIPIOS DEL ESTADO SELECCIONADO
     */

    public function getCreditoAnterior(Request $request)
    {
        if($request->ajax()){
            $cre = Credito::find($request->id);
            $cre = Credito::find($cre->reestructura_id);
            $cre = ($cre) ? $cre : "NO";
            return response()->json($cre);
        }
    }

    /*
     * OBTENER LOS MUNICIPIOS DEL ESTADO SELECCIONADO
     */

    public function getMunicipios(Request $request)
    {
        if($request->ajax()){
            $mun = Municipio::municipios($request->id);
            return response()->json($mun);
        }
    }

    /*
     * OBTENER LAS LOCALIDADES DEL MUNICIPIO SELECCIONADO
     */

    public function getLocalidades(Request $request)
    {
        if($request->ajax()){
            $loc = Localidad::localidades($request->id);
            return response()->json($loc);
        }
    }

    /*
     * OBTENER LAS COLONIAS DE LA LOCALIDAD SELECCIONADA
     */

    public function getColonias(Request $request)
    {
        if($request->ajax()){
            $col = Colonia::colonias($request->id);
            return response()->json($col);
        }
    }

    /*
     * OBTENER LAS CALLES DE LA COLONIA SELECCIONADA
     */
    
    public function getCalles(Request $request)
    {
        if($request->ajax()){
            $calle = Calle::calles($request->id);
            return response()->json($calle);
        }
    }

    /*
     * OBTENER LOS LOTES DE LA COLONIA SELECCIONADA
     */
    
    public function getLotes(Request $request)
    {
        if($request->ajax()){
            $lote = Lote::lotes($request->id);
            return response()->json($lote);
        }
    }

    /*
     * OBTENER LOS LOTES DE LA MANZANA SELECCIONADA
     */
    
    public function getLotes2(Request $request)
    {
        if($request->ajax()){
            $lote = Lote::lotes2($request->id,$request->manzana);
            return response()->json($lote);
        }
    }

    /*
     * OBTENER LA INFORMACION DEL LOTE, DEL LOTE SELECCIONADO
     */
    
    public function getInfoLote(Request $request, $id)
    {
        if($request->ajax()){
            $info = Lote::find($id);
            return response()->json($info);
        }
    }

    /*
     * OBTENER LOS TIPOS DE PROGRAMAS DEL PROGRAMA SELECCIONADO
     */
    
    public function getTiposProgramas(Request $request)
    {
        if($request->ajax()){
            $pro = TipoPrograma::tipos($request->id);
            return response()->json($pro);
        }
    }

    /*
     * OBTENER LOS DOCUMENTOS DEL TIPO DE PROGRAMA SELECCIONADO
     */
    
    public function getDocumentos(Request $request, $id)
    {
        if($request->ajax()){
            $doc = TipoPrograma::find($id);
            $doc->documentos;
            return response()->json($doc);
        }
    }

    /*
     * OBTENER LA SUPERFICIE DEL FRACCIONAMIENTO SELECCIONADO
     */
    
    public function getSuperficie(Request $request, $id)
    {
        if($request->ajax()){
            $lot = Lote::find($id);
            return response()->json($lot);
        }
    }

    /*
     * OBTENER EL VALOR DEL SUBSIDIO FEDERAL SELECCIONADO
     */
    
    public function getValorSubsidio(Request $request, $id)
    {
        if($request->ajax()){
            $sub = Subsidio::find($id);
            return response()->json($sub);
        }
    }

    /*
     * OBTENER EL ENGANCHE DE LA DEMANDA SELECCIONADA
     */
    
    public function getCreditoEnganche(Request $request, $id)
    {
        if($request->ajax()){
            $dem = Demanda::find($id);
            return response()->json($dem);
        }
    }

    /*
     * OBTENER LAS SIGLAS DEL FRACCIONAMIENTO EN EL LOTE SELECCIONADO
     */
    
    public function GetSiglasFraccionamiento(Request $request)
    {
        if($request->ajax()){
            $sigla = Lote::find($request->id);
            return response()->json($sigla->fraccionamiento->abreviacion);
        }
    }

    /*
     * OBTENER LA PLANTILLA DE LA DEMANDA SELECCIONADA (REESTRUCTURA SAIV)
     */
    
    public function GetPlantillaDemanda(Request $request)
    {
        if($request->ajax()){
            $demanda = Demanda::find($request->id);
            $demanda->tipo_programa;
            return response()->json($demanda);
        }
    }

    /*
     * ELIMINAR DEMANDA CREADA EN REESTRUCTURA SAIV
     */
    
    public function SetDeleteDemanda(Request $request, $id)
    {
        if($request->ajax()){
            \DB::table('demanda')->where('id_demanda', $id)->delete();
            return response()->json($id);
        }
    }

    /*
     * CREAR NUEVA DEMANDA DE LA REESTRUCTURA SAIV
     */
    
    public function setDemandaSaiv(Request $request)
    {
        if($request->ajax()){
            $plantilla = TipoPrograma::find($request->tipo);
            $dem = new Demanda();
            $dem->modulo            = $request->modulo;
            $dem->tipo_cliente      = "SOLICITANTE";
            //$dem->tipo_cliente      = "ACREDITADO";
            //$dem->enganche          = $request->enganche;
            $dem->estatus           = "PREPARADO";
            //$dem->estatus           = "EN PROCESO";
            $dem->tipo_programa_id  = $request->tipo;
            $dem->cliente_id        = $request->solicitante;
            $dem->plantilla         = $plantilla->plantilla;
            $dem->observaciones     = "SE CREO ESTA DEMANDA DESDE LA REESTRUCTURA DEL SAIV";
            $dem->save();
            //$dem->id_demanda = 23;
            return response()->json($dem);
        }
    }

    /*
     * CONTADOR DE SEGUIMIENTOS PENDIENTES -> CON MENSUALIDADES VENCIDAS
     */

    public function getSeguimientos(Request $request)
    {
        if($request->ajax()){
          $cont = 0;
          //$cre = Credito::join('mensualidad','credito.clave_credito','=','mensualidad.credito_clave')->where('mensualidad.estatus','=','VENCIDO')->where('credito.estatus','=','PAGANDOLA')->groupBy('credito.clave_credito')->get();
          $cre = Credito::select('credito.*',\DB::raw('count(*) as vencidos'))->join('mensualidad','credito.clave_credito','=','mensualidad.credito_clave')->where('mensualidad.estatus','=','VENCIDO')->where('credito.estatus','=','PAGANDOLA')->groupBy('credito.clave_credito')->get();
          //$cre = count($cre);
          foreach ($cre as $credito) {
            if($credito->vencidos > 2){
              $cont++;
            }
          }
          return response()->json($cont);
        }
    }

    /*
     * OBTENER EL CREDITO SELECCIONADO -> PLANTILLA 4
     */
    
    public function getCreditoReestructura(Request $request)
    {
        if($request->ajax()){
            $cre = Credito::find($request->clave);
            $cre->mensualidades;
            $cre->demanda;
            if($cre->plantilla == "1"){
                $cre->lote;
                $cre->lote->fraccionamiento;
            }
            $cre->demanda->cliente;
            $cre->demanda->tipo_programa;
            $cre->demanda->tipo_programa->programa;
            return response()->json($cre);
        }
    }

    /*
     * REGISTRAR LOTE DE REGULARIZACION
     */

    public function SetRegularizacionLote(Request $request)
    {
        if($request->ajax()){

          $this->validate($request, [
            'no_manzana'      => 'required|numeric',            'catastral_lote'         => 'required|numeric',
            'no_lote'         => 'required|numeric',            'catastral_construccion' => 'required|numeric',
            'superficie'      => 'required|numeric',            'insuvi_lote'            => 'required|numeric',
            'clave_catastral' => 'unique:lote,clave_catastral', 'insuvi_pie_casa'        => 'required|numeric',
            'calle'           => 'required',                    'escrituracion'          => 'required',
            'numero'          => 'required',                    'estado_vivienda'        => 'required',
            'valor_metro'     => 'required|numeric',            'regimen'                => 'required',
            'piso'            => 'required',                    'muro'                   => 'required',
            'techo'           => 'required'
            ],[
            'no_manzana.numeric'        => 'El Campo No. Manzana Debe Ser Solo Numerico',
            'no_manzana.required'       => 'El Campo No. Manzana Es Obligatorio',
            'no_lote.numeric'           => 'El Campo No. Lote Debe Ser Solo Numerico',
            'no_lote.required'          => 'El Campo No. Lote Es Obligatorio',
            'superficie.numeric'        => 'El Campo Superficie Debe Ser Solo Numerico',
            'superficie.required'       => 'El Campo Superficie Es Obligatorio',
            'clave_catastral.unique'    => 'La Clave Catastral Ya Existe',
            'valor_metro.numeric'       => 'El Campo Valor Metro Debe Ser Solo Numerico',
            'valor_metro.required'      => 'El Campo Valor Metro Es Obligatorio',
            'piso.required'             => 'El Campo Piso Es Obligatorio',
            'techo.required'            => 'El Campo Techo Es Obligatorio',
            'muro.required'             => 'El Campo Muro Es Obligatorio',
            'regimen.required'          => 'El Campo Regimen Es Obligatorio',
            'escrituracion.required'    => 'El Campo Escrituracion Es Obligatorio',
            'estado_vivienda.required'  => 'El Campo Estado Vivienda Es Obligatorio',
            'catastral_construccion.numeric'  => 'El Campo Valor Construccion Catasral Debe Ser Solo Numerico',
            'catastral_construccion.required' => 'El Campo Valor Construccion Catasral Es Obligatorio',
            'catastral_lote.numeric'          => 'El Campo Valor Lote Catastral Debe Ser Solo Numerico',
            'catastral_lote.required'         => 'El Campo Valor Lote Catastral Es Obligatorio',
            'insuvi_lote.numeric'             => 'El Campo Valor Lote Insuvi Debe Ser Solo Numerico',
            'insuvi_lote.required'            => 'El Campo Valor Lote Insuvi Es Obligatorio',
            'insuvi_pie_casa.numeric'         => 'El Campo Valor Lote Pie Ca Debe Ser Solo Numerico',
            'insuvi_pie_casa.required'        => 'El Campo Valor Lote Pie Ca Es Obligatorio',
            'calle.required'                  => 'El Campo Calle Es Obligatorio',
            'numero.required'                 => 'El Campo Numero Es Obligatorio',
          ]);

          $lote = new Lote();
          $lote->colonia_id      = $request->fraccionamiento;
          $lote->no_manzana      = $request->no_manzana;
          $lote->no_lote         = "L-" . $request->no_lote;
          $lote->superficie      = $request->superficie;
          $lote->norte           = $request->norte;
          $lote->sur             = $request->sur;
          $lote->este            = $request->este;
          $lote->oeste           = $request->oeste;
          $lote->ochavo          = $request->ochavo;
          $lote->uso_suelo       = $request->uso_suelo;
          $lote->clave_catastral = $request->clave_catastral;
          $lote->calle           = $request->calle;
          $lote->numero          = $request->numero;
          $lote->regularizacion  = 1;

          $lote->save();

          $regu = new Regularizacion();
          $regu->valor_metro            = $request->valor_metro;
          $regu->catastral_lote         = $request->catastral_lote;
          $regu->catastral_construccion = $request->catastral_construccion;
          $regu->insuvi_lote            = $request->insuvi_lote;
          $regu->insuvi_pie_casa        = $request->insuvi_pie_casa;
          $regu->drenaje                = $request->drenaje;
          $regu->agua                   = $request->agua;
          $regu->electricidad           = $request->electricidad;
          $regu->escrituracion          = $request->escrituracion;
          $regu->estado_vivienda        = $request->estado_vivienda;
          $regu->regimen_id             = $request->regimen;
          $regu->piso_id                = $request->piso;
          $regu->lote_id                = $lote->id_lote;
          $regu->muro_id                = $request->muro;
          $regu->techo_id               = $request->techo;
          $regu->save();

          return response()->json($lote->id_lote);
        }
    }

    /*
     * OBTENER LAS MENSUALIDADES VENCIDAS Y NO VENCIDAS
     */
    
    public function getMensualidadesVenc(Request $request)
    {
        if($request->ajax()){
            $array_mens     = [];
            $vencidos       = \DB::table('mensualidad')->where('credito_clave','=',$request->clave)->where('estatus','=','VENCIDO')->count();
            $no_pagados     = \DB::table('mensualidad')->where('credito_clave','=',$request->clave)->where('estatus','=','NO PAGADO')->count();
            //$pagados        = \DB::table('mensualidad')->where('credito_clave','=',$request->clave)->where('estatus','=','PAGADO')->count();
            //$no_vencidos    = \DB::table('mensualidad')->where('credito_clave','=',$request->clave)->whereRaw("estatus = 'NO PAGADO' OR estatus = 'PAGADO'")->count();
            if($vencidos > 0){
                $fecha_venc     = \DB::table('mensualidad')->select('fecha_vencimiento')->where('credito_clave','=',$request->clave)->where('estatus','=','VENCIDO')->first();
                $dias           = \DB::table('mensualidad')->select(\DB::raw("datediff('".date('Y-m-d')."','$fecha_venc->fecha_vencimiento') as dias"))->first();
                array_push($array_mens, ['vencidos' => $vencidos,'no_pagados' => $no_pagados, 'dias_venc'=>$dias->dias]);
                //array_push($array_mens, ['vencidos' => $vencidos,'pagados' => $pagados, 'dias_venc'=>$dias->dias]);
            } else {
                array_push($array_mens, ['vencidos' => $vencidos,'no_pagados' => $no_pagados, 'dias_venc'=>0]);
                //array_push($array_mens, ['vencidos' => $vencidos,'pagados' => $pagados, 'dias_venc'=>0]);
            }
            //array_push($array_mens, ['vencidos' => $vencidos, 'no_vencidos' => $no_vencidos, 'pagados' => $pagados, 'no_pagados' => $no_pagados]);
            //array_push($array_mens, $no_vencidos);
            return response()->json($array_mens);
        }
    }

    /*
     * OBTENER LOS DATOS GENERALES DEL CLIENTE POR EL REST DE RENAPO
     */
    
    public function getDatosCliente(Request $request)
    {
        if($request->ajax()){
            $curp = $request->curp;
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://wsrenapo.col.gob.mx/curp/apiV1/obtener/info?curp=".$curp,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Basic aW5zdXZpOjAxOWZjNDU2Mzg3YTAwOTYzMzUxNDQwYTMxZGVmZmM0",
                "cache-control: no-cache"
                //"postman-token: d64b258c-0b21-f854-f1fe-10c788c3c457"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if($err){
                return $err;
            } else {
                return $response;
            }
        }
    }

    /*
     * OBTENER LA CURP DEL CLIENTE POR EL REST DE RENAPO
     */
    
    public function getCurpCliente(Request $request)
    {
        if($request->ajax()){
            $nombre = urlencode($request->nombre);
            $ape_pa = $request->ape_pa;
            $ape_ma = $request->ape_ma;
            $f_nac  = urlencode($request->f_nac);
            $genero = $request->genero;

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://wsrenapo.col.gob.mx/curp/apiV1/obtener/curp?nombre=".$nombre."&apellido1=".$ape_pa."&apellido2=".$ape_ma."&fnac=".$f_nac."&sexo=".$genero,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Basic aW5zdXZpOjAxOWZjNDU2Mzg3YTAwOTYzMzUxNDQwYTMxZGVmZmM0",
                "cache-control: no-cache"
                //"postman-token: b3b337a1-bb6d-1a9a-6942-24b1ba5e6816"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl); 

            if($err){
                return $err;
            } else {
                return $response;
            }
        }
    }

    /*
     * SISTEMA DE BUSQUEDA (SCOPE) DEL PREREGISTRO YA CAPTURADO (CURP/CLAVE REGISTRO) Y RESPONDER CON EL OBJETO ENCONTRADO
     */

    public function preregistro(Request $request)
    {
        if($request->ajax()){

            $this->validate($request, [
                'buscar' => 'required',
            ],[
                'buscar.required'   => 'Debe Escribir la CURP o Clave Que Desea Buscar',
            ]);

            $pre = PreRegistro::search($request->buscar,$request->tipo)->get();

             return response()->json($pre);
        }
    }

    /*
     * Buscar Cliente en Reestructura Index
     */

    public function getAcreditado(Request $request)
    {
        // if($request->ajax()){

        //     //Validacion
        //     $this->validate($request, [
        //         'buscar' => 'required',
        //     ],[
        //         'buscar.required'   => 'Debe Escribir la CURP, Clave o el Nombre Que Desea Buscar',
        //     ]);

        //     if ($request->tipo == "credito") { //View -> Caja

        //         $aho = Ahorrador::join('demanda','demanda.id_demanda','=','ahorrador.demanda_id')->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->search($request->buscar)->get();
        //         $cre = Credito::join('demanda','demanda.id_demanda','=','credito.demanda_id')->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->leftJoin('mensualidad','credito.clave_credito','=','mensualidad.credito_clave')->search($request->buscar)->get();

        //         if(count($cre) == 0) {
        //             return response()->json($aho);
        //         } else {
        //             return response()->json($cre);
        //         }

        //         return response()->json("Ninguno");

        //     } else if($request->tipo == "cliente") { //View -> Caja

        //         $cre = Demanda::join('cliente','demanda.cliente_id','=','cliente.id_cliente')->leftJoin('ahorrador','demanda.id_demanda','=','ahorrador.demanda_id')->leftJoin('credito','demanda.id_demanda','=','credito.demanda_id')->clave($request->buscar)->get();

        //         return response()->json($cre);

        //     } else if($request->tipo == "curp") { //View -> Caja

        //         $cre = Cliente::join('demanda','demanda.cliente_id','=','cliente.id_cliente')->leftJoin('ahorrador','demanda.id_demanda','=','ahorrador.demanda_id')->leftJoin('credito','demanda.id_demanda','=','credito.demanda_id')->search($request->buscar,$request->tipo)->get();

        //         return response()->json($cre);

        //     } else if($request->tipo == "nombre") { //Views -> Reestructura/Caja

        //         $cre = Cliente::join('demanda','demanda.cliente_id','=','cliente.id_cliente')->leftJoin('ahorrador','demanda.id_demanda','=','ahorrador.demanda_id')->leftJoin('credito','demanda.id_demanda','=','credito.demanda_id')->search($request->buscar,$request->tipo)->groupBy('cliente.id_cliente')->get();

        //         return response()->json($cre);

        //     } else if($request->tipo == "curp2") { //View -> Reestructura

        //         $cre = Cliente::join('demanda','demanda.cliente_id','=','cliente.id_cliente')->leftJoin('ahorrador','demanda.id_demanda','=','ahorrador.demanda_id')->leftJoin('credito','demanda.id_demanda','=','credito.demanda_id')->search($request->buscar,"curp")->groupBy('cliente.id_cliente')->get();

        //         return response()->json($cre);

        //     }
        // }

      if($request->ajax()){
        if ($request->Filtro === "true") {
          $cliente = Cliente::search($request->buscar,$request->tipo)->join('demanda','demanda.cliente_id','=','cliente.id_cliente')->join('credito','credito.demanda_id','=','demanda.id_demanda')->where('cliente.estatus','=','1')->where('credito.plantilla','!=','3')->where('credito.estatus','!=','CANCELADA')->where('credito.estatus','!=','CANCELADA/REESTRUCTURA')->where('credito.estatus','!=','PAGADA')->groupBy('cliente.id_cliente')->get();
        } else {
          $cliente = Cliente::search($request->buscar,$request->tipo)->join('demanda','demanda.cliente_id','=','cliente.id_cliente')->join('credito','credito.demanda_id','=','demanda.id_demanda')->where('cliente.estatus','=','1')->where('credito.plantilla','!=','3')->where('credito.estatus','!=','CANCELADA')->where('credito.estatus','!=','CANCELADA/REESTRUCTURA')->where('credito.estatus','!=','PAGADA')->get();
          $cliente->each(function($cli){
            $cli->fecha_inicio = Carbon::parse($cli->fecha_inicio)->format('d-m-Y');
          });
        }
        return response()->json($cliente);
      }
    }

    /*
     * BUSCAR EL CREDITO SELECCIONADO EN EL SELECT -> CAJA
     */

    public function getCredito(Request $request)
    {
        if($request->ajax()){

            if ($request->tipo != "credito") {

                $cre = Ahorrador::search($request->clave)->get();
                return response()->json($cre);

            }

            $cre = Credito::join('demanda','demanda.id_demanda','=','credito.demanda_id')->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->leftJoin('mensualidad','credito.clave_credito','=','mensualidad.credito_clave')->search($request->clave)->get();
            
            return response()->json($cre);
        }
    }

    /*
     * BUSCAR TODOS LOS PAGOS DEL CREDITO SELECCIONADO -> CAJA
     */

    public function getPagos(Request $request)
    {
        if($request->ajax()){

            if ($request->tipo != "credito") {

                $abonos = AbonoAhorrador::search($request->clave)->get();
                return response()->json($abonos);

            } else {
                $abonos = AbonoCredito::search($request->clave)->get();
                return response()->json($abonos);
            }

            //$cre = Credito::join('demanda','demanda.id_demanda','=','credito.demanda_id')->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->search($request->clave)->get();
            return response()->json();
        }
    }

    /*
     * VALIDACION DE LA CURP ANTES DE ELABORAR EL PREREGISTRO
     */

    public function validar(Request $request)
    {
        if($request->ajax()){

            //* Descomposicion de la CURP del Solicitante *//
            $inical     = substr($request->curp, 0, 4);
            $num        = substr($request->curp, 4, 6);       
            $genero     = substr($request->curp, 10, 1);
            $estado     = substr($request->curp, 11, 2);
            $letra      = substr($request->curp, 13, 3);
            $homoclave  = substr($request->curp, 16, 2);

            if(!(ctype_alpha($inical) && ctype_alpha($letra) && ctype_digit($num) && ctype_digit($homoclave) && $this->is_mx_state($estado) && $this->is_sexo_curp($genero)))
            {
                $msg =  [
                        'curp' => 'El formato de la CURP es incorrecto, favor de verificar su CURP'
                    ];
                $status = 422;
                return response()->json($msg,$status);
            }

            $this->validate($request, [
                'curp' => 'unique:cliente,curp|required|unique:conyuge,curp|unique:pre_registro,solicitante_curp|unique:pre_registro,conyuge_curp|min:18',
            ],[
                'curp.unique'   => 'Esta CURP ya existe, si ya estas registrado favor de iniciar sesion, Caso al contrario favor de ponerse en contacto con el personal del INSUVI.',
                'curp.min'      => 'La CURP Debe Contener 18 Caracteres',
                'curp.required' => 'El Campo CURP Es Obligatorio',
            ]);

            return response()->json();
        }
    }

    /*
     * CREAR UN FAMILIAR DEL CLIENTE
     */

    public function familiar(Request $request)
    {
        if($request->ajax())
        {
            $this->validate($request, [
                'nombre'     => 'required',
                'paterno'    => 'required',
                'materno'    => 'required',
                'parentesco' => 'required',
                'genero'     => 'required',
                'edad'       => 'required|numeric',
                'ingreso'    => 'required|numeric',
            ],[
                'nombre.required'     => 'El Campo Nombre es Obligatorio',
                'paterno.required'    => 'El Campo Apellido Paterno es Obligatorio',
                'materno.required'    => 'El Campo Apellido Materno es Obligatorio',
                'parentesco.required' => 'El Campo Parentesco es Obligatorio',
                'genero.required'     => 'El Campo Genero es Obligatorio',
                'edad.required'       => 'El Campo Edad es Obligatorio',
                'ingreso.required'    => 'El Campo Ingreso es Obligatorio/Si no tiene Ingresos Marcar con "0"',
                'edad.numeric'        => 'El Campo Edad debe ser Numerico',
                'ingreso.numeric'     => 'El Campo Ingreso debe ser Numerico',
            ]);

            $fam = new Familiar();

            $fam->nombre      = $request->nombre;
            $fam->ape_paterno = $request->paterno;
            $fam->ape_materno = $request->materno;
            $fam->parentesco  = $request->parentesco;
            $fam->genero      = $request->genero;
            $fam->edad        = $request->edad;
            $fam->ocupacion   = $request->ocupacion;
            $fam->ingresos    = $request->ingreso;
            $fam->cliente_id  = $request->id;

            $fam->save();

            $solicitante = Cliente::find($request->id);

            $solicitante->familiares;

            return response()->json($solicitante);

        }
    }

    /*
     * ELIMINAR UN FAMILIAR DEL CLIENTE
     */

    public function deletefamiliar($id, Request $request)
    {   
        if($request->ajax())
        {
            $fam = Familiar::find($id);

            $fam->delete();

            $sol = Cliente::find($request->cliente);

            $sol->familiares;

            return response()->json($sol);
        }
    }

    /*
     * CREAR NUEVA REFERENCIA DEL CLIENTE
     */
    
    public function referencia(Request $request)
    {
        if($request->ajax())
        {
            $this->validate($request, [
                'nombre'     => 'required',
                'paterno'    => 'required',
                'materno'    => 'required',
                'parentesco' => 'required',
                'genero'     => 'required',
                'domicilio'  => 'required',
                'telefono'   => 'numeric|required',
            ],[
                'nombre.required'     => 'El Campo Nombre es Obligatorio',
                'paterno.required'    => 'El Campo Apellido Paterno es Obligatorio',
                'materno.required'    => 'El Campo Apellido Materno es Obligatorio',
                'parentesco.required' => 'El Campo Parentesco es Obligatorio',
                'genero.required'     => 'El Campo Genero es Obligatorio',
                'domicilio.required'  => 'El Campo Domicilio es Obligatorio',
                'telefono.required'   => 'El Campo Telefono debe ser solo Numerico',
            ]);

            $ref = new Referencia();

            $ref->nombre      = $request->nombre;
            $ref->ape_paterno = $request->paterno;
            $ref->ape_materno = $request->materno;
            $ref->parentesco  = $request->parentesco;
            $ref->genero      = $request->genero;
            $ref->domicilio   = $request->domicilio;
            $ref->telefono    = $request->telefono;
            $ref->cliente_id  = $request->id;

            $ref->save();

            $solicitante = Cliente::find($request->id);

            $solicitante->referencias;

            return response()->json($solicitante);

        }
    }

    /*
     * ELIMINAR REFERENCIA DEL CLIENTE
     */
    
    public function deletereferencia($id, Request $request)
    {   
        if($request->ajax())
        {
            $ref = Referencia::find($id);

            $ref->delete();

            $sol = Cliente::find($request->cliente);

            $sol->referencias;

            return response()->json($sol);
        }
    }

    /*
     * FUNCION COMPOSICON CURP (ESTADO)
     */
    
    public function is_mx_state($state){    
        $mxStates = [        
            'AS','BS','CL','CS','DF','GT',        
            'HG','MC','MS','NL','PL','QR',        
            'SL','TC','TL','YN','NE','BC',        
            'CC','CM','CH','DG','GR','JC',        
            'MN','NT','OC','QT','SP','SR',        
            'TS','VZ','ZS'    
        ];    
        if(in_array($state,$mxStates)){    
            return true;    
        }    
        return false;
    }

    /*
     * FUNCION COMPOSICON CURP (SEXO/GENERO)
     */

    public function is_sexo_curp($sexo){   
        $sexoCurp = ['H','M'];   
        if(in_array($sexo,$sexoCurp)){    
           return true;   
        }   
        return false;
    }
}
