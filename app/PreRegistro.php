<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class PreRegistro extends Model
{
    protected $table = "pre_registro";

    protected $primaryKey = "id_pre_registro";

    protected $fillable = ['solicitante_nombre',
    						'solicitante_ape_paterno',
    						'solicitante_ape_materno',
    						'solicitante_curp',
                            'genero',
                            'correo',
                            'telefono',
                            'fecha_nac',
                            'estado_nac',
                            'lugar_nac',
                            'no_dependientes',
    						'estado_civil',
    						'conyuge_nombre',
    						'conyuge_ape_paterno',
    						'conyuge_ape_materno',
    						'conyuge_curp',
                            'conyuge_fecha_nac',
                            'conyuge_lugar_nac',
                            'bienes',
    						'clave_registro',
    						'estatus',
                            'ocupacion_id',
                            'escolaridad_id'
    						];

    //Campos NULLS
    public function setConyugeNombreAttribute($valor)
    {
        if($valor == null){
            $this->attributes['conyuge_nombre'] = NULL;
        }else {
            $this->attributes['conyuge_nombre'] = $valor;
        }
        
    }

    public function setConyugeApePaternoAttribute($valor)
    {
        if($valor == null){
            $this->attributes['conyuge_ape_paterno'] = NULL;
        }else{
            $this->attributes['conyuge_ape_paterno'] = $valor;
        }
    }

    public function setConyugeApeMaternoAttribute($valor)
    {
        if($valor == null){
            $this->attributes['conyuge_ape_materno'] = NULL;
        }else {
            $this->attributes['conyuge_ape_materno'] = $valor;
        }
        
    }

    public function setConyugeCurpAttribute($valor)
    {
        if($valor == null){
            $this->attributes['conyuge_curp'] = NULL;
        }else{
            $this->attributes['conyuge_curp'] = $valor;
        }
    }

    public function setConyugeFechaNacAttribute($valor)
    {
        if($valor == null){
            $this->attributes['conyuge_fecha_nac'] = NULL;
        }else {
            $this->attributes['conyuge_fecha_nac'] = $valor;
        }
        
    }

    public function setConyugeLugarNacAttribute($valor)
    {
        if($valor == null){
            $this->attributes['conyuge_lugar_nac'] = NULL;
        }else {
            $this->attributes['conyuge_lugar_nac'] = $valor;
        }
        
    }

    public function setBienesAttribute($valor)
    {
        if($valor == null){
            $this->attributes['bienes'] = NULL;
        }else {
            $this->attributes['bienes'] = $valor;
        }
        
    }

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
        }else {
            $this->attributes['telefono'] = $valor;
        }
        
    }

    //SCOPES
    public function scopeSearch($query, $dato, $tipo)
    {
        if(trim($dato) != "" && $tipo == "clave")
        {
            return $query->where('clave_registro', 'LIKE', "$dato");

        } else if(trim($dato) != "" && $tipo == "curp"){

            return $query->where('solicitante_curp', 'LIKE', "$dato");
        }
    }
}
