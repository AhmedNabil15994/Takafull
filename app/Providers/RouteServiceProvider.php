<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //$this->mapApiRoutes();

        $this->mapGuestRoutes();

        $this->mapModuleRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    /*protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }*/

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapGuestRoutes()
    {
        @define('DATE_TIME', date("Y-m-d H:i:s"));
        Route::middleware('general')->namespace($this->namespace)->group(function (){
            require app_path('Modules/Home/routes.php');
            require app_path('Modules/Auth/routes.php');
        });
    }

    protected function mapModuleRoutes()
    {
        @define('DATE_TIME', date("Y-m-d H:i:s"));
        Route::middleware('withAuth')->prefix('backend')->namespace($this->namespace)->group(function (){
            require app_path('Modules/Dashboard/routes.php');
            require app_path('Modules/TopMenu/routes.php');
            require app_path('Modules/BottomMenu/routes.php');
            require app_path('Modules/Advantage/routes.php');
            require app_path('Modules/Benefit/routes.php');
            require app_path('Modules/Page/routes.php');
            require app_path('Modules/Slider/routes.php');
            require app_path('Modules/City/routes.php');
            require app_path('Modules/Log/routes.php');
            require app_path('Modules/ContactUs/routes.php');
            require app_path('Modules/BlockedUser/routes.php');
            require app_path('Modules/Order/routes.php');
            require app_path('Modules/Photo/routes.php');
            require app_path('Modules/File/routes.php');
            require app_path('Modules/User/routes.php');
            require app_path('Modules/Group/routes.php');
            require app_path('Modules/Variables/routes.php');
        });
    }


}
