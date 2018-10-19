<?php

namespace insuvi\Http\Middleware;

use Closure;

class EstudioSocioeconomicoMiddleware
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
        $m_estudio = str_contains($v_modulos,'ESTUDIO');

        if (($m_admon || $m_estudio) && $v_estatus) {
            return $next($request);
        }

        return redirect()->route('insuvi')->withErrors('Acceso Denegado');
    }
}
