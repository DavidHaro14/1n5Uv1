<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = "programa";

    protected $primaryKey = "id_programa";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function tipos_programas()
    {
    	return $this->hasMany('insuvi\TipoPrograma','programa_id');
    }
}
