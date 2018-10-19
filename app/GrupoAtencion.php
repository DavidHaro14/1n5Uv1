<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class GrupoAtencion extends Model
{
    protected $table = "cat_grupo_atencion";

    protected $primaryKey = "id_grupo";

    protected $fillable = ['nombre',
    						'estatus'
    						];

     public function clientes()
    {
        return $this->hasMany('insuvi\Cliente','grupo_atencion_id');
    }
}
