<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class RegimenPropiedad extends Model
{
    protected $table = "cat_regimen_propiedad";

    protected $primaryKey = "id_propiedad";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function regularizaciones()
    {
    	return $this->hasMany('insuvi\Regularizacion','reg_propiedad_id');
    }
}
