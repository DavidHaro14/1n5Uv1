<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AbonoAhorrador extends Model
{
    protected $table = "abono_ahorrador";

    protected $primaryKey = null;

    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = ['folio',
                            'no_pago',
                            'fecha',
    						'abono',
    						'ahorrador_clave',
    						'registrado'
    						];

    // public function caja()
    // {
    // 	return $this->belongsTo('insuvi\Caja','caja_id');
    // }

    public function ahorrador()
    {
    	return $this->belongsTo('insuvi\Ahorrador','ahorrador_clave');
    }

    //Mutators
    public function getFechaAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }

    //SCOPES
    public function scopeSearch($query, $dato)
    {
        if(trim($dato) != "")
        {
            return $query->where('ahorrador_clave', '=', "$dato");
        } 
    }
}
