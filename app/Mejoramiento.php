<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Mejoramiento extends Model
{
    protected $table = "cat_mejoramiento";

    protected $primaryKey = "id_mejoramiento";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function clientes()
    {
    	return $this->hasMany('insuvi\Cliente','mejoramiento_id');
    }
}
