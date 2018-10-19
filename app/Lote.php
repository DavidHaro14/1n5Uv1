<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = "lote";

    protected $primaryKey = "id_lote";

    protected $fillable = ['colonia_id',
    						'no_manzana',
    						'no_lote',
    						'superficie',
    						'norte',
    						'sur',
    						'este',
                            'oeste',
                            'noreste',
                            'sureste',
                            'suroeste',
                            'noroeste',
                            'ochavo',
                            'uso_suelo',
                            'clave_catastral',
                            'calle',
                            'numero',
                            'regularizacion',
                            'estatus'  						
    						];

    public function credito()
    {
    	return $this->hasOne('insuvi\Credito','lote_id');
    }

    public function fraccionamiento()
    {
        return $this->belongsTo('insuvi\Colonia','colonia_id');
    }

    public function regularizar()
    {
        return $this->hasOne('insuvi\Regularizacion','lote_id');
    }

    public function setClaveCatastralAttribute($valor)
    {
        $this->attributes['clave_catastral'] = ($valor == "") ? NULL : $valor;
    }

    //Select Dinamicos
    public static function lotes($id)
    {
        return Lote::where('colonia_id','=',$id)->where('estatus','=','DISPONIBLE')->groupBy('no_manzana')->orderBy('no_manzana')->get();
    }

    public static function lotes2($id,$manzana)
    {
        return Lote::where('colonia_id','=',$id)->where('no_manzana','=',$manzana)->where('estatus','=','DISPONIBLE')->get();
    }
}
