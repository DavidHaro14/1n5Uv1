<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = "empleado";

    protected $primaryKey = "id_empleado";

    protected $fillable = ['nombre',
    						'apellido_p',
    						'apellido_m',
    						'telefono',
                            'correo',
                            'modulo',
    						'emp_estatus',
                            'usuario_id',
                            'jefe',
    						'jefe_id'
    						];

    public function usuario()
    {
    	return $this->belongsTo('insuvi\Usuario','usuario_id');
    }

    public function JefeAsignado(){
        return $this->belongsTo('insuvi\Empleado','jefe_id');
    }

    // MUTATORS
    
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

    public function getCorreoAttribute($valor)
    {
        if($valor == null){
            return "Sin Registro";
        } else {
            return $valor;
        }
    }

    public function getTelefonoAttribute($valor)
    {
        if($valor == null){
            return "Sin Registro";
        } else {
            return $valor;
        }
    }
}
