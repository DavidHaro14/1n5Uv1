<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = "caja";

    protected $primaryKey = "id_caja";

    protected $fillable = ['modulo',
    						'folio_inicio',
    						'folio_final',
    						'folio_actual',
    						'estatus',
    						'usuario_id'
    						];

    public function usuario()
    {
    	return $this->belongsTo('insuvi\Usuario','usuario_id');
    }

    public function cortes()
    {
    	return $this->hasMany('insuvi\Corte','caja_id');
    }

    // public function abonos_ahorrador()
    // {
    //     return $this->hasMany('insuvi\AbonoAhorrador','caja_id');
    // }

    // public function abonos_credito()
    // {
    //     return $this->hasMany('insuvi\AbonoCredito','caja_id');
    // }

    // public function abonos_convenio()
    // {
    //     return $this->hasMany('insuvi\AbonoConvenio','caja_id');
    // }

}
