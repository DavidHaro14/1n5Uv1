<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Regularizacion extends Model
{
    protected $table = "regularizacion";

    protected $primaryKey = "id_regularizacion";

    protected $fillable = [ 
                            'valor_metro',
                            'catastral_lote',
                            'catastral_construccion',
                            'insuvi_lote',
                            'insuvi_pie_casa',
                            'drenaje',
                            'agua',
                            'electricidad',
                            'escrituracion',
                            'estado_vivienda',
                            'regimen_id',
                            'piso_id',
                            'lote_id',
    						'muro_id',
    						'techo_id',
    						];

    public function regimen_propiedad()
    {
        return $this->belongsTo('insuvi\RegimenPropiedad','reg_propiedad_id');
    }

    public function piso()
    {
        return $this->belongsTo('insuvi\Piso','piso_id');
    }

    public function muro()
    {
        return $this->belongsTo('insuvi\Muro','muro_id');
    }

    public function techo()
    {
        return $this->belongsTo('insuvi\Techo','techo_id');
    }

    public function lote()
    {
        return $this->belongsTo('insuvi\Lote','lote_id');
    }

    //mutators 
    
    public function setDrenajeAttribute($valor)
    {
        $this->attributes['drenaje'] = ($valor == null) ? '0' : '1';
    }
    
    public function setAguaAttribute($valor)
    {
        $this->attributes['agua'] = ($valor == null) ? '0' : '1';
    }
    
    public function setElectricidadAttribute($valor)
    {
        $this->attributes['electricidad'] = ($valor == null) ? '0' : '1';
    }
}
