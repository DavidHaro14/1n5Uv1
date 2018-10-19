<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class EstudioSocioeconomico extends Model
{
    protected $table = "estudio_socioeconomico";

    protected $primaryKey = "id_estudio";

    protected $fillable = ['antiguedad',
    						'nombre_trabajo',
    						'telefono_trabajo',
    						'servicio_salud',
                            'servicio_vivienda',
    						'num_int',
    						'num_ext',
    						'ingr_principal',
    						'ingr_secundario',
    						'asalariado',
    						'gst_alimentacion',
    						'gst_luz',
    						'gst_transporte',
    						'gst_renta',
    						'gst_educacion',
    						'gst_agua',
    						'gst_otros',
    						'gst_total',
    						'ingr_total',
                            'ingr_familiar',
                            'no_seg_social',
                            'num_cuartos',
                            'escrituracion',
                            'estado_vivienda',
    						'entrevistador',
    						'responsable',
    						'estatus',
    						'cliente_id',
    						'calle_id',
                            'techo_id',
                            'muro_id',
                            'piso_id'
    						];

    public function cliente()
    {
        return $this->belongsTo('insuvi\Cliente','cliente_id');
    }

    public function calle()
    {
        return $this->belongsTo('insuvi\Calle','calle_id');
    }

    public function sit_conyuge()
    {
        return $this->hasOne('insuvi\SitConyuge','estudio_socioeconomico_id');
    }

    public function techo()
    {
        return $this->belongsTo('insuvi\Techo','techo_id');
    }

    public function muro()
    {
        return $this->belongsTo('insuvi\Muro','muro_id');
    }

    public function piso()
    {
        return $this->belongsTo('insuvi\Piso','piso_id');
    }

    //mutators 
    
    public function setAsalariadoAttribute($valor)
    {
        $this->attributes['asalariado'] = ($valor == null) ? '0' : '1';
    }

    public function setEscrituracionAttribute($valor)
    {
        $this->attributes['escrituracion'] = ($valor == null) ? '0' : '1';
    }

    public function setTelefonoTrabajoAttribute($valor)
    {
        if($valor == null){
            $this->attributes['telefono_trabajo'] = NULL;
        }else {
            $this->attributes['telefono_trabajo'] = $valor;
        }
        
    }

    public function setNoSegSocialAttribute($valor)
    {
        if($valor == null){
            $this->attributes['no_seg_social'] = NULL;
        }else {
            $this->attributes['no_seg_social'] = $valor;
        }
        
    }
}
