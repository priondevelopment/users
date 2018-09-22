<?php

namespace PrionUsers\Providers;

/**
 * This file is part of Prion Development Users,
 * a role & account management Laravel.
 *
 * @license MIT
 * @package Users
 */

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class PrionUsersServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migration' => 'command.prionusers:migration',
        'Setup' => 'command.prionusers.setup',
        'MakeSeeder' => 'command.prionusers.seeder',
    ];

    /**
     * The middlewares to be registered.
     *
     * @var array
     */
    protected $middlewares = [
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        require realpath(__DIR__ . '/../Http/Routes/Account.php');
        require realpath(__DIR__ . '/../Http/Routes/User.php');

        // Register published configuration.
        $this->publishes([
            __DIR__ . '/config/prionusers.php' => config_path('prionusers.php'),
        ], 'prionusers');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPrionUsers();

        $this->registerCommands();

        $this->mergeConfig();
    }


    /**
     * Register PrionUsers Package in Laravel/Lumen
     *
     */
    protected function registerPrionUsers()
    {
        $this->app->bind('prionusers', function ($app) {
            return new PrionUsers($app);
        });

        $this->app->alias('prionusers', 'PrionUsers\PrionUsers');

    }


    /**
     * Register the Available Commands
     *
     */
    protected function registerCommands ()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($this->commands));
    }


    /**
     * Merge Configuration Settings at run time. If the user has not run
     * the configuration setup command, the default setings are merged in
     *
     */
    protected function mergeConfig ()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/prionusers.php',
            'prionusers'
        );
    }
}