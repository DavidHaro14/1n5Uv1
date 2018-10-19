<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = "cat_banco";

    protected $primaryKey = "id_banco";

    protected $fillable = ['nombre',
    						'cuenta',
    						'estatus'
    						];

    public function tipos_programas()
    {
    	return $this->hasMany('insuvi\TipoPrograma','banco_id');
    }
}
