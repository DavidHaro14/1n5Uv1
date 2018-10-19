<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Ahorrador extends Model
{
    protected $table = "ahorrador";

    protected $primaryKey = "clave_ahorrador";

    public $incrementing = false;

    protected $fillable = ['clave_ahorrador',
                            'monto_total',
    						'pagado',
    						'total_abonado',
    						'demanda_id'
    						];

    public function demanda()
    {
    	return $this->belongsTo('insuvi\Demanda','demanda_id');
    }

    public function abonos_ahorrador()
    {
        return $this->hasMany('insuvi\AbonoAhorrador','ahorrador_clave');
    }

    //SCOPES
    public function scopeSearch($query, $dato)
    {
        if(trim($dato) != "")
        {
            return $query->where('clave_ahorrador', '=', "$dato");
        } 
    }

    public function scopeCaja($query, $dato, $tipo)
    {
         if(trim($dato) != "" && $tipo == "curp"){

            return $query->where('cliente.curp', 'LIKE', "%$dato%");

        } else if(trim($dato) != "" && $tipo == "nombre"){

            return $query->whereRaw("CONCAT(cliente.nombre, ' ',cliente.ape_paterno, ' ',cliente.ape_materno) LIKE '%$dato%'");

        } else if(trim($dato) != "" && $tipo == "credito"){

            return $query->where('clave_ahorrador', '=', "$dato");

        } else if(trim($dato) != "" && $tipo == "cliente"){

            return $query->where('cliente.id_cliente', '=', $dato);

        }
    }
}
