<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

//class Usuario extends Model
class Usuario extends Authenticatable
{
    protected $table = "usuario";

    protected $primaryKey = "id_usuario";

    protected $fillable = ['usuario',
    						'password',
                            'perfil',
                            'modulos',
                            'permisos',
    						'estatus_us'
    						];

    public function empleado()
    {
        return $this->hasOne('insuvi\Empleado','usuario_id');
    }

    public function caja()
    {
        return $this->hasOne('insuvi\Caja','usuario_id');
    }
}
