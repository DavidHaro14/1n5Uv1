<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Cancelacion extends Model
{
    protected $table = "cancelacion";

    protected $primaryKey = "id_cancelacion";

    protected $fillable = ['folio',
                            'usuario_id',
                            'descripcion',
    						'no_cheque',
    						'importe',
                            'registrado',
    						'credito_clave'				
    						];

    public function credito()
    {
    	return $this->belongsTo('insuvi\Credito','credito_clave');
    }
}
