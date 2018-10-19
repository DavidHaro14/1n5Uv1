<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DatosEmpresa extends Model
{
    protected $table = "datos_empresa";

    protected $primaryKey = "id_empresa";

    public $timestamps = false;

    protected $fillable = ['limite_monto',
                            'limite_porcentaje',					
                            'director',					
                            'fecha_director',                   
                            'fecha_limite',					
                            'estatus'					
    						];

    //Mutators
    public function getFechaLimiteAttribute($valor){
    	return Carbon::parse($valor)->format('d-m-Y');
    }

    public function getFechaDirectorAttribute($valor){
    	return Carbon::parse($valor)->format('d-m-Y');
    }

    public function setFechaLimiteAttribute($valor){
        $this->attributes['fecha_limite'] = Carbon::parse($valor)->format('Y-m-d');
    }

    public function setFechaDirectorAttribute($valor){
        $this->attributes['fecha_director'] = Carbon::parse($valor)->format('Y-m-d');
    }
}
