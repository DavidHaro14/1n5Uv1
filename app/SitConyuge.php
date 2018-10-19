<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class SitConyuge extends Model
{
    protected $table = "sit_conyuge";

    protected $primaryKey = "id_sit_conyuge";

    protected $fillable = ['antiguedad',
    						'nombre_trabajo',
    						'telefono_trabajo',
    						'servicio_salud',
                            'servicio_vivienda',
                            'no_seg_social',
    						'num_int',
    						'num_ext',
    						'ingr_prin',
    						'asalariado',
    						'ingr_sec',
    						'calle_id',
    						'ocupacion_id',
    						'estudio_socioeconomico_id'
    						];

    public function estudio_socioeconomico()
    {
        return $this->belongsTo('insuvi\EstudioSocioeconomico','estudio_socioeconomico_id');
    }

    public function calle()
    {
        return $this->belongsTo('insuvi\Calle','calle_id');
    }

    public function ocupacion()
    {
        return $this->belongsTo('insuvi\Calle','ocupacion_id');
    }

    //Mutators
    public function setAsalariadoAttribute($valor)
    {
        $this->attributes['asalariado'] = ($valor == null) ? '0' : '1';
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
