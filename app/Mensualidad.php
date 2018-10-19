<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mensualidad extends Model
{
    protected $table = "mensualidad";

    protected $primaryKey = "clave_mensualidad";

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['clave_mensualidad',
                            'no_mensualidad',
                            'fecha_vencimiento',
                            'interes',
                            'capital',
    						'descuento_interes',
                            'descuento_capital',
    						'descuento_moratorio',
    						'saldo',
                            'resto',
    						'estatus',
    						'credito_clave'
    						];

    public function credito()
    {
    	return $this->belongsTo('insuvi\Credito','credito_clave');
    }

    public function abonos_credito()
    {
    	return $this->hasMany('insuvi\AbonoCredito','mensualidad_clave');
    }

    //Mutators
    public function getFechaVencimientoAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }
}
