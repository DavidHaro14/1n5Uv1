<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cliente extends Model
{
    protected $table = "cliente";

    protected $primaryKey = "id_cliente";

    protected $fillable = ['nombre',
    						'ape_paterno',
    						'ape_materno',
    						'curp',
    						'fecha_nac',
    						'estado_civil',
    						'genero',
    						'correo',
    						'telefono',
    						'num_int',
    						'num_ext',
    						'codigo_postal',
    						'estado_nac',
    						'lugar_nac',
    						'referencia_calles',
    						'dependientes',
    						'grupo_social',
    						'adquisicion_vivienda',
    						'autoconstruccion',
                            'descripcion_ubicacion',
                            'zona_interes',
    						'estatus',
    						'calle_id',
    						'usuario_id',
    						'ocupacion_id',
    						'mejoramiento_id',
                            'grupo_atencion_id',
                            'creado',
    						'escolaridad'
    						];
    public function calle()
    {
        return $this->belongsTo('insuvi\Calle','calle_id');
    }

    public function domicilios(){
        return $this->hasMany('insuvi\CambioDomicilio','cliente_id');
    }

    public function usuario()
    {
        return $this->hasOne('insuvi\Usuario','usuario_id');
    }

    public function ocupacion()
    {
        return $this->belongsTo('insuvi\Ocupacion','ocupacion_id');
    }

    public function mejoramiento()
    {
        return $this->belongsTo('insuvi\Mejoramiento','mejoramiento_id');
    }

    public function conyuge()
    {
        return $this->hasOne('insuvi\Conyuge','cliente_id');
    }

    public function estudios_socioeconomico()
    {
        return $this->hasMany('insuvi\EstudioSocioeconomico','cliente_id');
    }

    public function familiares()
    {
        return $this->hasMany('insuvi\Familiar','cliente_id');
    }

    public function referencias()
    {
        return $this->hasMany('insuvi\Referencia','cliente_id');
    }

     public function grupo_atencion()
    {
        return $this->belongsTo('insuvi\GrupoAtencion','grupo_atencion_id');
    }

    public function demandas()
    {
        return $this->hasMany('insuvi\Demanda','cliente_id');
    }
    
    //Campos NULLS
    public function setCorreoAttribute($valor)
    {
        if($valor == null){
            $this->attributes['correo'] = NULL;
        }else {
            $this->attributes['correo'] = $valor;
        }
    }

    public function setTelefonoAttribute($valor)
    {
        if($valor == null){
            $this->attributes['telefono'] = NULL;
        }else{
            $this->attributes['telefono'] = $valor;
        }
    }

    public function setAdquisicionViviendaAttribute($valor)
    {
        if($valor == null){
            $this->attributes['adquisicion_vivienda'] = NULL;
        }else{
            $this->attributes['adquisicion_vivienda'] = $valor;
        }
    }

    public function setAutoconstruccionAttribute($valor)
    {
        if($valor == null){
            $this->attributes['autoconstruccion'] = NULL;
        }else{
            $this->attributes['autoconstruccion'] = $valor;
        }
    }

    public function setMejoramientoIdAttribute($valor)
    {
        if($valor == ""){
            $this->attributes['mejoramiento_id'] = 0;
        }else{
            $this->attributes['mejoramiento_id'] = $valor;
        }
    }

    public function setDescripcionUbicacionAttribute($valor)
    {
        if($valor == null){
            $this->attributes['descripcion_ubicacion'] = NULL;
        } else {
            $this->attributes['descripcion_ubicacion'] = $valor;
        }
    }

    public function getFechaNacAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }

    public function getNumIntAttribute($valor)
    {
        if ($valor == "0" || $valor == "") {
            return "";
        } else {
            return " - " . $valor;
        }
    }

     //SCOPES
    public function scopeSearch($query, $dato , $tipo)
    {
        if(trim($dato) != "" && $tipo == "curp"){

            return $query->where('curp', 'LIKE', "%$dato%");//quite el groupby

        } else if(trim($dato) != "" && $tipo == "nombre"){

            return $query->whereRaw("CONCAT(nombre, ' ',ape_paterno, ' ',ape_materno) LIKE '%$dato%'");//quite el groupby

        } else if(trim($dato) != "" && $tipo == "domicilio"){

            return $query->select('cliente.nombre','cliente.ape_paterno','cliente.ape_materno','cliente.num_int','cliente.num_ext','cliente.id_cliente','cliente.curp','cat_calle.nombre as nom_calle','cat_municipio.nombre as nom_mun')
            ->join('cat_calle','cat_calle.id_calle','=','cliente.calle_id')
            ->join('cat_colonia','cat_colonia.id_colonia','=','cat_calle.colonia_id')
            ->join('cat_localidad','cat_localidad.id_localidad','=','cat_colonia.localidad_id')
            ->join('cat_municipio','cat_municipio.id_municipio','=','cat_localidad.municipio_id')
            ->whereRaw("CONCAT(cat_calle.nombre, ' ',cliente.num_ext, ' ', cat_municipio.nombre) LIKE '%$dato%'");
        }
    }
}
