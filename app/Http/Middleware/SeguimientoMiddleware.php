<?php

namespace insuvi\Http\Middleware;

use Closure;

class SeguimientoMiddleware
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

        $m_admon       = str_contains($v_modulos,'ADMON');
        $m_seguimiento = str_contains($v_modulos,'SEGUIMIENTO');

        if (($m_admon || $m_seguimiento) && $v_estatus) {
            return $next($request);
        }

        return redirect()->route('insuvi')->withErrors('Acceso Denegado');
    }
}
