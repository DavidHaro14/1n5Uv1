<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = "cat_documento";

    protected $primaryKey = "id_documento";

    protected $fillable = ['nombre',
    						'opcional',
    						'estatus'
    						];

    public function tipos_programas()
    {
        return $this->belongsToMany('insuvi\TipoPrograma');
    }

    public function setOpcionalAttribute($valor)
    {
        $this->attributes['opcional'] = ($valor == null) ? '0' : '1';
    }
}
