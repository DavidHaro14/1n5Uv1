<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = "contrato";

    protected $primaryKey = "id_contrato";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function tipos_programas()
    {
        return $this->belongsToMany('insuvi\TipoPrograma');
    }
}
