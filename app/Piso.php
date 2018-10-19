<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    protected $table = "cat_piso";

    protected $primaryKey = "id_piso";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function regularizaciones()
    {
    	return $this->hasMany('insuvi\Regularizacion','piso_id');
    }

    public function estudios_socioeconomicos()
    {
    	return $this->hasMany('insuvi\EstudioSocioeconomico','piso_id');
    }
}
