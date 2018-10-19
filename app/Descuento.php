<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    protected $table = "descuento";

    protected $primaryKey = "id_descuento";

    protected $fillable = ['observaciones',
                            'vigencia',
                            'desc_capital',
                            'desc_interes',
                            'desc_moratorio',
                            'estatus',
    						'estatus_descuentos',
                            'mensualidades',
                            'registrado',
    						'credito_clave'
    						];

    public function credito()
    {
    	return $this->belongsTo('insuvi\Credito','credito_clave');
    }
}
