<?php

namespace insuvi\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \insuvi\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \insuvi\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \insuvi\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \insuvi\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'admon' => \insuvi\Http\Middleware\AdmonMiddleware::class,
        'solicitud' => \insuvi\Http\Middleware\AtencionSolicitudMiddleware::class,
        'estudio' => \insuvi\Http\Middleware\EstudioSocioeconomicoMiddleware::class,
        'demanda' => \insuvi\Http\Middleware\DemandaMiddleware::class,
        'contratacion' => \insuvi\Http\Middleware\ContratacionMiddleware::class,
        'reestructura' => \insuvi\Http\Middleware\ReestructuraMiddleware::class,
        'saiv' => \insuvi\Http\Middleware\SaivMiddleware::class,
        'cancelacion' => \insuvi\Http\Middleware\CancelacionMiddleware::class,
        'seguimiento' => \insuvi\Http\Middleware\SeguimientoMiddleware::class,
        'cesion' => \insuvi\Http\Middleware\CesionDerechoMiddleware::class,
        'caja' => \insuvi\Http\Middleware\CajaMiddleware::class,
        'descuento' => \insuvi\Http\Middleware\DescuentoMiddleware::class,
        'enganche' => \insuvi\Http\Middleware\EngancheMiddleware::class,
        'domicilio' => \insuvi\Http\Middleware\DomicilioMiddleware::class,
    ];
}
