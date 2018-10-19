<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Subsidio extends Model
{
    protected $table = "subsidio";

    protected $primaryKey = "id_subsidio";

    protected $fillable = ['organizacion',
                           'tipo',
    					   'clave',
    					   'valor',
    					   'estatus'
    					   ];

    public function creditos()
    {
    	return $this->belongsToMany('insuvi\Credito');
    }
}
