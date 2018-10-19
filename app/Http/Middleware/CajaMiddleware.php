<?php

namespace insuvi\Http\Middleware;

use Closure;

class CajaMiddleware
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

        $m_admon     = str_contains($v_modulos,'ADMON');
        $m_caja      = str_contains($v_modulos,'CAJA');
        $m_descuento = str_contains($v_modulos,'DESCUENTO');

        if (($m_admon || $m_caja || $m_descuento) && $v_estatus) {
            return $next($request);
        }

        return redirect()->route('insuvi')->withErrors('Acceso Denegado');
    }
}
