<?php

namespace insuvi\Http\Middleware;

use Closure;

class DemandaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $v_modulos   = \Auth::user()->modulos;
        $v_estatus   = \Auth::user()->estatus_us;

        $m_admon   = str_contains($v_modulos,'ADMON');
        $m_demanda = str_contains($v_modulos,'DEMANDA');

        if (($m_admon || $m_demanda) && $v_estatus) {
            return $next($request);
        }

        return redirect()->route('insuvi')->withErrors('Acceso Denegado');
    }
}
