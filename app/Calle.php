<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    protected $table = "cat_calle";

    protected $primaryKey = "id_calle";

    protected $fillable = ['nombre',
    						'estatus',
    						'colonia_id'
    						];

    public function colonia()
    {
    	return $this->belongsTo('insuvi\Colonia','colonia_id');
    }

    public function clientes()
    {
    	return $this->hasMany('insuvi\Cliente','calle_id');
    }

    public function sit_conyuges()
    {
        return $this->hasMany('insuvi\SitConyuge','calle_id');
    }

    public function estudios_socioeconomico()
    {
        return $this->hasMany('insuvi\EstudioSocioeconomico','calle_id');
    }

    /*-- Select Dinamico de Calles --*/
    public static function calles($id)
    {   
        return Calle::where('colonia_id','=',$id)->where('estatus','=','1')->orderBy('nombre')->get();
    }
}
