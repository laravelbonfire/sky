<?php namespace Pingpong\Menus;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class MenusServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerMenusFile();
    }

    /**
     * Require the menus file if that file is exists.
     *
     * @return void
     */
    public function registerMenusFile()
    {
        if (file_exists($file = app_path('Support/menus.php'))) {
            require $file;
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlPackage();

        $this->app['menus'] = $this->app->share(function ($app) {
            return new Menu($app['view'], $app['config']);
        });
    }

    /**
     * Register "iluminate/html" package.
     *
     * @return void
     */
    protected function registerHtmlPackage()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');

        $aliases = [
            'HTML' => 'Collective\Html\HtmlFacade',
            'Form' => 'Collective\Html\FormFacade',
        ];

        AliasLoader::getInstance($aliases)->register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('menus');
    }

    /**
     * Register package's namespaces.
     *
     * @return void
     */
    protected function registerNamespaces()
    {
        $this->mergeConfigFrom(__DIR__ . '/src/config/config.php', 'menus');
        $this->loadViewsFrom(__DIR__ . '/src/views', 'menus');

        $this->publishes([
            __DIR__ . '/src/config/config.php' => config_path('menus.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/src/views' => base_path('resources/views/vendor/pingpong/menus'),
        ], 'views');
    }
}
