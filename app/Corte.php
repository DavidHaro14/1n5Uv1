<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    protected $table = "corte";

    protected $primaryKey = "id_corte";

    protected $fillable = ['fecha',
    						'total',
    						'caja_id'
    						];

    public function caja()
    {
        return $this->belongsTo('insuvi\Caja','caja_id');
    }
}
