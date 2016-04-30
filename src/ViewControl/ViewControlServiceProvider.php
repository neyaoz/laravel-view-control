<?php
namespace Rephole\ViewControl;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Rephole\ViewControl\Vendor\Illuminate\View\Engines\ControlEngine;

class ViewControlServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindInstancesInContainer();
        $this->includeHelpers();
    }

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->getFactory()->addExtension('cs.php', 'control', function() {
            return new ControlEngine($this->getFactory());
        });
    }

    /**
     * Bind all of the instances in the container.
     *
     * @return void
     */
    protected function bindInstancesInContainer()
    {
        $this->app->instance("view-control.path", $this->path());
        $this->app->instance("view-control.path.base", $this->basePath());
    }
    
    protected function includeHelpers()
    {
        $file = $this->basePath('helpers.php');

        if ($this->getFilesystem()->isFile($file)) {
            $this->getFilesystem()->requireOnce($file);
        }
    }

    /**
     * @param array|string $providers
     */
    protected function registerProviders($providers)
    {
        foreach ((array) $providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * @param array $aliases
     */
    protected function registerAliases($aliases)
    {
        AliasLoader::getInstance($aliases);
    }

    /**
     * @param array|string $middlewares
     */
    protected function registerMiddlewares($middlewares)
    {
        foreach ((array) $middlewares as $middleware) {
            $this->app->make(Kernel::class)->pushMiddleware($middleware);
        }
    }

    /**
     * @param string $name
     * @param array|string $middlewares
     */
    protected function registerGroupMiddlewares($name, $middlewares)
    {
        foreach ((array) $middlewares as $middleware) {
            $this->app->make(Router::class)->pushMiddlewareToGroup($name, $middleware);
        }
    }

    /**
     * @param array $middlewares
     */
    protected function registerRouteMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $key => $middleware) {
            $this->app->make(Router::class)->middleware($key, $middleware);
        }
    }

    /**
     * Get the path to the plugin directory.
     *
     * @param  string $path
     * @return string
     */
    public function path($path = '')
    {
        return __DIR__ . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the base path of the plugin installation.
     *
     * @param  string $path
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->getFilesystem()->dirname($this->path()) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }


    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->app->make('files');
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function getFactory()
    {
        return $this->app->make('view');
    }
    
}