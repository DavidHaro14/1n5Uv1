<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SolicitudEliminacion extends Model
{
    protected $table = "solicitud_eliminacion";

    protected $primaryKey = "id_solicitud";

    protected $fillable = ['descripcion',
                            'credito',
                            'usuario',
    						'responsable',
    						'status'
    						];

    public function getCreatedAtAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }

    public function getUpdatedAtAttribute($valor)
    {
        return Carbon::parse($valor)->format('d-m-Y');
    }

    public function ScopeSolicitante($query, $dato, $tipo){
        if(trim($dato) != ""){

            return $query->where($tipo, 'LIKE', "%$dato%");

        }
    }
}
