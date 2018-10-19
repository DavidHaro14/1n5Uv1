<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class AbonoCredito extends Model
{
    protected $table = "abono_credito";

    protected $primaryKey = null;
    
    public $timestamps = false;
    
    public $incrementing = false;

    protected $fillable = ['no_pago',
                            'fecha',
    						'abono',
                            'mensualidad_clave',
                            'interes',
                            'capital',
    						'moratorio',
    						'caja_id'
    						];

    public function caja()
    {
    	return $this->belongsTo('insuvi\Caja','caja_id');
    }

    public function mensualidad()
    {
    	return $this->belongsTo('insuvi\Mensualidad','mensualidad_clave');
    }

    //SCOPES
    public function scopeSearch($query, $dato)
    {
        if(trim($dato) != "")
        {
            return $query->where('mensualidad_clave', '=', "$dato");
        } 
    }
}
