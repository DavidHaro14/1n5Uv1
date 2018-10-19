<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = "cat_municipio";

    protected $primaryKey = "id_municipio";

    protected $fillable = ['nombre', 
    						'estatus',
    						'estado_id',
    						];

    public function estado()
    {
    	return $this->belongsTo('insuvi\Estado','estado_id');
    }

    public function localidades()
    {
    	return $this->hasMany('insuvi\Localidad','municipio_id');
    }

    /*-- Select Dinamico de Municipio --*/
    public static function municipios($id)
    {   
        return Municipio::where('estado_id','=',$id)->where('estatus','=','1')->orderBy('nombre')->get();
    }
}