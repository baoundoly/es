<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $nameSpaceAdmin = 'App\\Http\\Controllers\\Admin';
    protected $nameSpaceMember = 'App\\Http\\Controllers\\Member';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

            Route::middleware(['web','revalidate'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth:admin','permission','revalidate'])
            ->name( 'admin.')
            ->prefix('admin')
            ->namespace($this->nameSpaceAdmin)
            ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'auth:member','revalidate'])
            ->name( 'member.')
            ->prefix('member')
            ->namespace($this->nameSpaceMember)
            ->group(base_path('routes/member.php'));
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
