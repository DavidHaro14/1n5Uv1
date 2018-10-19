<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Ocupacion extends Model
{
    protected $table = "cat_ocupacion";

    protected $primaryKey = "id_ocupacion";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function clientes()
    {
    	return $this->hasMany('insuvi\Cliente','ocupacion_id');
    }

    public function sit_conyuges()
    {
        return $this->hasMany('insuvi\SitConyuge','ocupacion_id');
    }

    public function clientes_regularizacion()
    {
    	return $this->hasMany('insuvi\ClienteRegularizacion','ocupacion_id');
    }
}
