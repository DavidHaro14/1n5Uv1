<?php

namespace insuvi\Http\Controllers;

use Illuminate\Http\Request;

use insuvi\Http\Requests;
use insuvi\Credito;
use insuvi\Mensualidad;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class PdfController extends Controller
{
    // public function plantilla1($credito,$stream = 0){
    //     $cre = Credito::find($credito);
    //     $men = Mensualidad::where('credito_clave','=',$credito)->orderBy('fecha_vencimiento')->get();
    //     $pdf = \PDF::loadview('sistema_interno.Pdf.Credito.pdf_plantilla1',['credito'=>$cre,'mensualidades'=>$men]);
    //     if($stream > 0){
    //         return $pdf->stream();
    //     }
    //     return $pdf->download($credito.'_Credito.pdf');
    // }

    // public function plantilla2($credito,$stream = 0){
    //     /*$templateWord = new TemplateProcessor(storage_path('contratos') . '\prueba.docx');
    //     $temporal  = $templateWord->getVariables()[0];
    //     $buscar    = ['$[V_CLIENTE]','$[V_CLAVE_CATASTRAL]','$[V_PLAZO]'];
    //     $remplazar = ['ORLANDO ANTONIO GONZALEZ JUAREZ','123-456-789','120 MESES'];
    //     $temporal = str_replace($buscar, $remplazar, $temporal);*/

    //     $cre = Credito::find($credito);
    //     $men = Mensualidad::where('credito_clave','=',$credito)->orderBy('fecha_vencimiento')->get();
    //     $pdf = \PDF::loadview('sistema_interno.Pdf.Credito.pdf_plantilla2',['credito'=>$cre,'mensualidades'=>$men/*,'contrato'=>$temporal*/]);
    //     if($stream > 0){
    //         return $pdf->stream();
    //     }
    //     return $pdf->download($credito.'_Credito.pdf');
    // }

    // public function plantilla3($credito,$stream = 0){
    //     //dd($credito);
    //     $cre = Credito::find($credito);
    //     $men = Mensualidad::where('credito_clave','=',$credito)->orderBy('fecha_vencimiento')->get();
    //     $pdf = \PDF::loadview('sistema_interno.Pdf.Credito.pdf_plantilla3',['credito'=>$cre,'mensualidades'=>$men]);
    //     if($stream > 0){
    //         return $pdf->stream();
    //     }
    //     return $pdf->download($credito.'_Credito.pdf');
    // }

    public function plantilla($credito, $plantilla, $stream = 0){
        //dd($credito);
        $cre  = Credito::find($credito);
        $men  = Mensualidad::where('credito_clave','=',$credito)->orderBy('fecha_vencimiento')->get();
        //dd($cre->subsidios->filter(function($value,$index){return $value->tipo == "ESTATAL";})->first()->valor);
        if ($plantilla === "3") {
            $inicial         = ($cre->lote->regularizacion === 1) ? "REG" : "ESC";
            $programa        = str_pad($cre->demanda->tipo_programa->programa_id, 2, "0", STR_PAD_LEFT);
            $tipo            = str_pad($cre->demanda->tipo_programa_id, 2, "0", STR_PAD_LEFT);
            $fraccionamiento = $cre->lote->fraccionamiento->abreviacion;
            $clave = $inicial . "-" . $programa . $tipo . "-" . $fraccionamiento;
            //dd($clave);
            $dia  = Carbon::parse($cre->created_at)->day;
            $mes  = StringMes($cre->created_at);
            $anio = Carbon::parse($cre->created_at)->year;
            $pdf = \PDF::loadview('sistema_interno.Pdf.Credito.pdf_plantilla'.$plantilla,['credito'=>$cre,'mensualidades'=>$men,'dia'=>$dia,'mes'=>$mes,'anio'=>$anio,'clave'=>$clave]);
        } else {
            $pdf = \PDF::loadview('sistema_interno.Pdf.Credito.pdf_plantilla'.$plantilla,['credito'=>$cre,'mensualidades'=>$men]);
        }

        if($stream > 0){
            return $pdf->stream();
        }
        return $pdf->download($credito.'_Credito.pdf');
    }

}
