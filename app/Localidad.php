<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = "cat_localidad";

    protected $primaryKey = "id_localidad";

    protected $fillable = ['nombre',
    						'estatus',
    						'municipio_id'
    						];

    public function municipio()
    {
    	return $this->belongsTo('insuvi\Municipio','municipio_id');
    }

    public function colonias()
    {
    	return $this->hasMany('insuvi\Colonia','localidad_id');
    }

    /*-- Select Dinamico de Localidad --*/
    public static function localidades($id)
    {   
        return Localidad::where('municipio_id','=',$id)->where('estatus','=','1')->orderBy('nombre')->get();
    }
}
