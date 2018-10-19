<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class CesionDerecho extends Model
{
    protected $table = "cesion_derecho";

    protected $primaryKey = "id_cesion";

    protected $fillable = ['acuerdo',
                            'cliente_anterior',
                            'curp_anterior',
                            'registrado',
    						'demanda_id'];

    public function demanda(){
    	return $this->belongsTo('insuvi\Demanda','demanda_id');
    }
}
