<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = "seguimiento";

    protected $primaryKey = "id_seguimiento";

    protected $fillable = ['situacion_id',
                            'descripcion_seguimiento',
    						'restriccion',
    						'monto_convenio',
                            'estatus_seguimiento',
    						'registrado',
                            'credito_clave'					
    						];

    public function credito()
    {
    	return $this->belongsTo('insuvi\Credito','credito_clave');
    }

    public function situacion()
    {
        return $this->belongsTo('insuvi\Situacion','situacion_id');
    }

    public function abonos_convenio()
    {
        return $this->hasMany('insuvi\AbonoConvenio','seguimiento_id');
    }

    //Falta relacion usuario
}
