<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    protected $table = "referencia";

    protected $primaryKey = "id_referencia";

    protected $fillable = ['nombre',
    						'ape_paterno',
    						'ape_materno',
    						'domicilio',
                            'telefono',
    						'parentesco',
    						'genero',
    						'cliente_id'
    						];

    public function cliente()
    {
        return $this->belongsTo('insuvi\Cliente','cliente_id');
    }
}
