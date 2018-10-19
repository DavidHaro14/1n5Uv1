<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conyuge extends Model
{
    protected $table = "conyuge";

    protected $primaryKey = "id_conyuge";

    protected $fillable = ['nombre',
    						'ape_paterno',
    						'ape_materno',
    						'curp',
    						'bienes',
    						'lugar_nac',
    						'fecha_nac',
    						'estatus',
    						'cliente_id'
    						];

    public function cliente()
    {
        return $this->belongsTo('insuvi\Cliente','cliente_id');
    }

    public function getFechaNacAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }
}
