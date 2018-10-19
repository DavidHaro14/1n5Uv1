<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Techo extends Model
{
    protected $table = "cat_techo";

    protected $primaryKey = "id_techo";

    protected $fillable = ['nombre',
    						'estatus'
    						];

    public function regularizaciones()
    {
    	return $this->hasMany('insuvi\Regularizacion','techo_id');
    }

    public function estudios_socioeconomicos()
    {
    	return $this->hasMany('insuvi\EstudioSocioeconomico','techo_id');
    }
}
