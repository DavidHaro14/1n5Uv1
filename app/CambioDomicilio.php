<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class CambioDomicilio extends Model
{
    protected $table = "cambio_domicilio";

    protected $primaryKey = "id_domicilio";

    protected $fillable = ['domicilio',
    						'creado',
                            'cliente_id'];

    public function cliente(){
    	return $this->belongsTo('insuvi\Cliente','cliente_id');
    }
}
