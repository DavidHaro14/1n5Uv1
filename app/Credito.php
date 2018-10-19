<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Credito extends Model
{
    protected $table = "credito";

    protected $primaryKey = "clave_credito";

    public $incrementing = false;

    protected $fillable = ['clave_credito',
                            'costo_metro',
    						'costo_terreno',
    						'costo_construccion',
    						'valor_solucion',
    						'enganche',
    						'no_subsidio_fed',
                            'no_subsidio_est',
                            'no_subsidio_mun',
                            'no_subsidio_otr',
                            'costo_contado',
                            'plazo',
                            'taza_interes',
                            'moratorio',
                            'costo_financiamiento',
                            'total_pagar',
                            'fecha_inicio',
                            'total_abonado',
                            'total_descuento',
                            'pago_mensual',
                            'estatus',
                            'observaciones',
                            'plantilla',
                            'saiv',
                            'tabla',
                            'demanda_id',
                            'lote_id',                     
                            'registrado',                     
                            'actualizado',                     
                            'reestructura_id'
    						];

    public function demanda()
    {
    	return $this->belongsTo('insuvi\Demanda','demanda_id');
    }

    public function lote()
    {
        return $this->belongsTo('insuvi\Lote','lote_id');
    }

    public function subsidios()
    {
        return $this->belongsToMany('insuvi\Subsidio');
    }

    public function mensualidades()
    {
        return $this->hasMany('insuvi\Mensualidad','credito_clave')->orderBy('no_mensualidad');
    }

    public function descuentos()
    {
        return $this->hasMany('insuvi\Descuento','credito_clave');
    }

    public function seguimientos()
    {
        return $this->hasMany('insuvi\Seguimiento','credito_clave');
    }

    public function cancelacion()
    {
        return $this->hasOne('insuvi\Cancelacion','credito_clave');
    }

    //Mutadores NULL
    public function setNoSubsidioEstAttribute($valor)
    {
        if($valor == null){
            $this->attributes['no_subsidio_est'] = NULL;
        }else {
            $this->attributes['no_subsidio_est'] = $valor;
        }
    }

    public function setNoSubsidioFedAttribute($valor)
    {
        if($valor == null){
            $this->attributes['no_subsidio_fed'] = NULL;
        }else {
            $this->attributes['no_subsidio_fed'] = $valor;
        }
    }

    public function setNoSubsidioMunAttribute($valor)
    {
        if($valor == null){
            $this->attributes['no_subsidio_mun'] = NULL;
        }else {
            $this->attributes['no_subsidio_mun'] = $valor;
        }
    }

    public function setNoSubsidioOtrAttribute($valor)
    {
        if($valor == null){
            $this->attributes['no_subsidio_otr'] = NULL;
        }else {
            $this->attributes['no_subsidio_otr'] = $valor;
        }
    }

    public function getFechaInicioAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }

    //SCOPES
    public function scopeSearch($query, $dato)
    {
        if(trim($dato) != "")
        {
            return $query->where('clave_credito', '=', "$dato")->select('credito.*', 'demanda.*', 'cliente.*','credito.estatus AS est', 'mensualidad.*', 'mensualidad.estatus AS estatus_mensualidad')->orderBy('mensualidad.no_mensualidad');
        } 
    }

    public function scopeBuscar($query, $dato, $tipo)
    {
         if(trim($dato) != "" && $tipo == "curp"){

            return $query->join('demanda','demanda.id_demanda','=','credito.demanda_id')->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->where('cliente.curp', 'LIKE', "%$dato%");

        } else if(trim($dato) != "" && $tipo == "nombre"){

            return $query->join('demanda','demanda.id_demanda','=','credito.demanda_id')->join('cliente','demanda.cliente_id','=','cliente.id_cliente')->whereRaw("CONCAT(cliente.nombre, ' ',cliente.ape_paterno, ' ',cliente.ape_materno) LIKE '%$dato%'");

        } else if(trim($dato) != "" && $tipo == "credito"){

            return $query->where('clave_credito', '=', "$dato");

        }
    }

    public function scopeCaja($query, $dato, $tipo)
    {
         if(trim($dato) != "" && $tipo == "curp"){

            return $query->where('cliente.curp', 'LIKE', "%$dato%");

        } else if(trim($dato) != "" && $tipo == "nombre"){

            return $query->whereRaw("CONCAT(cliente.nombre, ' ',cliente.ape_paterno, ' ',cliente.ape_materno) LIKE '%$dato%'");

        } else if(trim($dato) != "" && $tipo == "credito"){

            return $query->where('clave_credito', '=', "$dato");

        } else if(trim($dato) != "" && $tipo == "cliente"){

            return $query->where('cliente.id_cliente', '=', $dato);

        }
    }
}
