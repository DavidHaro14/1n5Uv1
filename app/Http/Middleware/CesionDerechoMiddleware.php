<?php

namespace insuvi\Http\Middleware;

use Closure;

class CesionDerechoMiddleware
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

        $m_admon  = str_contains($v_modulos,'ADMON');
        $m_cesion = str_contains($v_modulos,'CESION');

        if (($m_admon || $m_cesion) && $v_estatus) {
            return $next($request);
        }

        return redirect()->route('insuvi')->withErrors('Acceso Denegado');
    }
}
