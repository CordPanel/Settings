<?php

namespace Cord\Settings;

use Config;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Cord\Settings\app\Models\Setting;
use Route;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Where the route file lives, both inside the package and in the app (if overwritten).
     *
     * @var string
     */
    public $routeFilePath = '/routes/cord/settings.php';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // - first the published/overwritten views (in case they have any changes)
        $this->loadViewsFrom(resource_path('views/vendor/cord/settings'), 'cord');
        // - then the stock views that come with the package, in case a published view might be missing
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'cord');

        // publish config file
        $this->publishes([__DIR__.'/config' => config_path()], 'config');

        // define the routes for the application
        $this->setupRoutes($this->app->router);

        // publish translation files
        // publish lang files
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/cord')], 'lang');
        
        // Check if Cord\Clients was installed, if so don't load translation path.
        if(!class_exists('Cord\Clients\ClientsServiceProvider')) {
          $this->loadTranslationsFrom(realpath(__DIR__.'/resources/lang'), 'cord');
        }

        // only use the Settings package if the Settings table is present in the database
        if (!\App::runningInConsole() && count(Schema::getColumnListing('settings'))) {
            // get all settings from the database
            $settings = Setting::all();
            // bind all settings to the Laravel config, so you can call them like
            // Config::get('settings.contact_email')
            foreach ($settings as $key => $setting) {
                Config::set('settings.'.$setting->key, $setting->value);
            }
        }

        // publish migrations
        $this->publishes([__DIR__.'/database/migrations/' => database_path('migrations')], 'migrations');

        // publish views
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/cord/settings')], 'views');

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(__DIR__.'/config/cord/settings.php', 'cord.settings');
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        if (file_exists(base_path().$this->routeFilePath)) {
            $routeFilePathInUse = base_path().$this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('settings', function ($app) {
            return new Settings($app);
        });

        // register their aliases
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Setting', \Cord\Settings\app\Http\Models\Setting::class);
    }
}
