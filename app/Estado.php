<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = "cat_estado";

    protected $primaryKey = 'id_estado';

    protected $fillable = ['nombre', 
    						'estatus'
    						];

    public function municipios()
    {
    	return $this->hasMany('insuvi\Municipio','estado_id');
    }
}
