<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Muro extends Model
{
    protected $table = "cat_muro";

    protected $primaryKey = "id_muro";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function regularizaciones()
    {
    	return $this->hasMany('insuvi\Regularizacion','muro_id');
    }

    public function estudios_socioeconomicos()
    {
    	return $this->hasMany('insuvi\EstudioSocioeconomico','muro_id');
    }
}
