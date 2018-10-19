<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class AbonoConvenio extends Model
{
    protected $table = "abono_convenio";

    protected $primaryKey = null;

    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = ['folio',
                            'no_pago',
                            'fecha',
    						'abono',
    						'seguimiento_id',
    						'caja_id'
    						];

    public function caja()
    {
    	return $this->belongsTo('insuvi\Caja','caja_id');
    }

    public function seguimiento()
    {
    	return $this->belongsTo('insuvi\Seguimiento','seguimiento_id');
    }
}
