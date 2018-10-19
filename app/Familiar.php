<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Familiar extends Model
{
    protected $table = "familiar";

    protected $primaryKey = "id_familiar";

    protected $fillable = ['nombre',
    						'ape_paterno',
    						'ape_materno',
    						'parentesco',
                            'genero',
    						'edad',
    						'ocupacion',
    						'ingresos',
    						'cliente_id'
    						];

    public function cliente()
    {
        return $this->belongsTo('insuvi\Cliente','cliente_id');
    }
}
