<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    protected $table = "cat_colonia";

    protected $primaryKey = "id_colonia";

    protected $fillable = ['nombre',
    						'estatus',
    						'localidad_id',
                            'abreviacion'
    						];

    public function localidad()
    {
    	return $this->belongsTo('insuvi\Localidad','localidad_id');
    }

    public function calles()
    {
    	return $this->hasMany('insuvi\Calle','colonia_id');
    }

    public function lotes()
    {
        return $this->hasMany('insuvi\Lote','colonia_id');
    }

    public function regularizaciones()
    {
        return $this->hasMany('insuvi\Regularizacion','fraccionamiento_id');
    }

    /*-- Select Dinamico de Colonia --*/
    public static function colonias($id)
    {   
        return Colonia::where('localidad_id','=',$id)->where('estatus','=','1')->orderBy('nombre')->get();
    }
}
