<?php

namespace insuvi;

use Illuminate\Database\Eloquent\Model;

class TipoPrograma extends Model
{
    protected $table = "tipo_programa";

    protected $primaryKey = "id_tipo_programa";

    protected $fillable = ['nombre',
                            'descripcion',
    						'estatus',
    						'programa_id',
                            'banco_id',
                            'plantilla'
    						];

    public function demandas()
    {
    	return $this->hasMany('insuvi\Demanda','tipo_programa_id');
    }

    public function programa()
    {
    	return $this->belongsTo('insuvi\Programa','programa_id');
    }

    public function documentos()
    {
        return $this->belongsToMany('insuvi\Documento');
    }

    public function banco()
    {
        return $this->belongsTo('insuvi\Banco','banco_id');
    }

    public function contratos()
    {
        return $this->belongsToMany('insuvi\Contrato');
    }

    /*-- Select Dinamico de Tipo Programa --*/
    public static function tipos($id)
    {   
        return TipoPrograma::where('programa_id','=',$id)->where('estatus','=','1')->get();
    }
}
