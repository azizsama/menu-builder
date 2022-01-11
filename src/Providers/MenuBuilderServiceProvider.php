<?php

namespace AzizSama\MenuBuilder\Providers;

use AzizSama\MenuBuilder\Builder\Builder;
use AzizSama\MenuBuilder\Events\BuildingMenu;
use AzizSama\MenuBuilder\MenuBuilder;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;

class MenuBuilderServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * The event dispatcher instance.
     * 
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $this->registerEvents($events);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath('config/menu-builder.php'), 'menu-builder');

        $this->registerMenuBuilder();
        $this->publish();
    }

    /**
     * Register the MenuBuilder instance.
     *
     * @return void
     */
    protected function registerMenuBuilder()
    {
        $this->app->singleton('menu-builder', function ($app) {
            return new MenuBuilder(
                $app['config']['menu-builder.filters'] ?? [],
                $app['events'],
                $app['config']['menu-builder.cache'] ?? false,
                $app['config']['menu-builder.cache_key'] ?? 'menu-builder',
                $app['config']['menu-builder.cache_time'] ?? 60
            );
        });
    }

    /**
     * Register the event listeners.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    protected function registerEvents(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $builder = $event->builder;
            $groupedMenu = $this->app['config']['menu-builder.menu'];
            foreach($groupedMenu as $group => $items) {
                $builder->add($group, ...$items);
            }
        });
    }

    /**
     * Publish the config file.
     * 
     * @return void
     */
    protected function publish()
    {
        $this->publishes([
            $this->packagePath('config/menu-builder.php') => config_path('menu-builder.php'),
        ], 'menu-builder-config');
    }

    /**
     * Get the package path.
     * 
     * @param string $path
     * @return string
     */
    protected function packagePath($path = '')
    {
        if (Str::startsWith($path, DIRECTORY_SEPARATOR)) {
            $path = substr($path, 1);
        }
        return __DIR__ . '/../' . $path;
    }
}