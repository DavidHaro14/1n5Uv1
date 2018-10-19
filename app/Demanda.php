<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Demanda extends Model
{
    protected $table = "demanda";

    protected $primaryKey = "id_demanda";

    protected $fillable = ['estatus',
                            'modulo',
                            'observaciones',
    						'tipo_cliente',
    						'tipo_programa_id',
                            'cliente_id',
                            'plantilla',
                            'registrado',
    						'actualizado',
                            'enganche'
    						];

    public function credito()
    {
    	return $this->hasMany('insuvi\Credito','demanda_id');
    }

    public function cesionderechos()
    {
        return $this->hasMany('insuvi\CesionDerecho','demanda_id');
    }

    public function ahorrador()
    {
        return $this->hasOne('insuvi\Ahorrador','demanda_id');
    }

    public function tipo_programa()
    {
    	return $this->belongsTo('insuvi\TipoPrograma','tipo_programa_id');
    }

    public function cliente()
    {
        return $this->belongsTo('insuvi\Cliente','cliente_id');
    }
    // mutators
    public function getCreatedAtAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }
    //Scopes
    public function scopeClave($query, $dato)
    {
        if(trim($dato) != "")
        {
            return $query->where('cliente_id', '=', "$dato");//->groupBy('cliente.id_cliente');
        } 
    }

    public function scopeEnganche($query, $dato, $tipo)
    {
        if(trim($dato) != "" && $tipo == "curp"){

            return $query->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->where('curp', 'LIKE', "%$dato%");

        } else if(trim($dato) != "" && $tipo == "nombre"){

            return $query->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->whereRaw("CONCAT(nombre, ' ',ape_paterno, ' ',ape_materno) LIKE '%$dato%'");

        } else if(trim($dato) != "" && $tipo == "clave_demanda"){

            return $query->where('id_demanda', '=', "$dato");

        } else if(trim($dato) != "" && $tipo == "clave_cliente"){

            return $query->where('cliente_id', '=', "$dato");
        }
    }
}
