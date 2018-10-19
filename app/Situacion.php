<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Situacion extends Model
{
    protected $table = "situacion";

    protected $primaryKey = "id_situacion";

    protected $fillable = ['situacion',
                            'status'					
    						];

    public function seguimientos()
    {
    	return $this->hasMany('insuvi\Seguimiento','situacion_id');
    }
}
