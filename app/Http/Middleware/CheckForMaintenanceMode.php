<?php

namespace ProVision\Administration\Http\Middleware;

use Closure;
use File;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use ProVision\Administration\Administration;

class CheckForMaintenanceMode {
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next) {

        if (Administration::routeInAdministration()) {
            return $next($request);
        }

        if (File::exists($this->app->storagePath() . '/framework/down-provision-administration')) {
            $data = json_decode(file_get_contents($this->app->storagePath() . '/framework/down-provision-administration'), true);

            throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
        }

        return $next($request);
    }
}
