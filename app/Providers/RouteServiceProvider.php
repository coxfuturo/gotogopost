<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/website.php'));

            Route::middleware(['web', 'guard:franchise'])
                ->prefix('franchise')
                ->namespace($this->namespace)
                ->group(base_path('routes/franchise.php'));

            Route::middleware(['web', 'guard:admin'])
                ->prefix('admin')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'guard:delboy'])
                ->prefix('delboy')
                ->namespace($this->namespace)
                ->group(base_path('routes/deliveryBoy.php'));

            Route::middleware(['web', 'guard:cms'])
                ->prefix('cms')
                ->namespace($this->namespace)
                ->group(base_path('routes/cms.php'));

            Route::middleware(['web', 'guard:pph'])
                ->prefix('pph')
                ->namespace($this->namespace)
                ->group(base_path('routes/pph.php'));

                Route::middleware(['web', 'guard:customer'])
                ->prefix('customer')
                ->namespace($this->namespace)
                ->group(base_path('routes/customer.php'));


                Route::middleware(['web', 'guard:market'])
                ->prefix('market')
                ->namespace($this->namespace)
                ->group(base_path('routes/market.php'));

                Route::middleware(['web', 'guard:prepaid'])
                ->prefix('prepaid')
                ->namespace($this->namespace)
                ->group(base_path('routes/prepaid.php'));


        });
    }
}
