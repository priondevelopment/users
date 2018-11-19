<?php

namespace PrionUsers;

/**
 * This file is part of Prion Development API,
 * a api credential and token management.
 *
 * @license MIT
 * @package Api
 */

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class UsersServiceProvider extends ServiceProvider
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
        'Migration' => 'command.prionusers.migration',
        'Setup' => 'command.prionusers.setup',
        'Seeder' => 'command.prionusers.seeder',
        'Config' => 'command.prionusers.config',

        // Models
        'ModelAccount' => 'command.prionusers.model-account',
        'ModelAccountGroup' => 'command.prionusers.model-account-group',
        'ModelAccountGroupPermission' => 'command.prionusers.model-account-group-permission',
        'ModelAccountUser' => 'command.prionusers.model-account-user',
        'ModelAccountUserPermission' => 'command.prionusers.model-account-user-permissions',
        'ModelAddress' => 'command.prionusers.model-address',
        'ModelPhone' => 'command.prionusers.model-phone',
        'ModelUser' => 'command.prionusers.model-user',
        'ModelEmail' => 'command.prionusers.model-email',
        'ModelVerificationCode' => 'command.prionusers.model-verification-code',
    ];


    /**
     * The Routes the Run
     *
     * @var array
     */
    protected $routes = [
        'account',
        'user',
    ];

    /**
     * The middlewares to be registered.
     *
     * @var array
     */
    protected $middlewares = [
        'prion.account' => \PrionUsers\Http\Middleware\Account::class,
        'prion.permission' => \PrionUsers\Http\Middleware\Permission::class,
        'prion.user' => \PrionUsers\Http\Middleware\User::class,
        'prion.access.admin' => \PrionUsers\Http\Middleware\Access\Admin::class,
        'prion.access.authenticated' => \PrionUsers\Http\Middleware\Access\Authenticated::class,
    ];

    protected $app_name = 'prionusers';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Register published configuration.
        $app_path = app()->basePath('config/'. $this->app_name .'.php');
        $this->publishes([
            __DIR__ . '/config/'. $this->app_name .'.php' => $app_path,
        ], $this->app_name);

        $this->registerMiddleware();
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

        $this->registerRoutes();
    }


    /**
     * Register the middlewares automatically.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        if (!$this->app['config']->get('prionusers.middleware.register')) {
            return;
        }

        $router = $this->app['router'];
        if (method_exists($router, 'middleware')) {
            $registerMethod = 'middleware';
        } elseif (method_exists($router, 'aliasMiddleware')) {
            $registerMethod = 'aliasMiddleware';
        } else {
            return;
        }

        foreach ($this->middlewares as $key => $class) {
            $router->$registerMethod($key, $class);
        }
    }


    /**
     * Register PrionUsers Package in Laravel/Lumen
     *
     */
    protected function registerPrionUsers()
    {
        $this->app->bind($this->app_name, function ($app) {
            return new PrionUsers($app);
        });

        $this->app->alias($this->app_name, PrionUsers\PrionUsers::class);
    }


    /**
     * Register the Available Commands
     *
     */
    protected function registerCommands ()
    {
        if (!$this->app->runningInConsole()) {
            return false;
        }

        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($this->commands));
    }


    /**
     * Register all Route Files
     *
     */
    protected function registerRoutes()
    {
        $this->app->router->group([
            'namespace' => 'PrionUsers\Http\Controllers',
            'prefix' => config($this->app_name . 'base_path.users'),
        ], function ($router) use ($route) {
            require __DIR__.'/routes/user.php';
        });

        $this->app->router->group([
            'namespace' => 'PrionUsers\Http\Controllers',
            'prefix' => config($this->app_name . 'base_path.accounts'),
        ], function ($router) use ($route) {
            require __DIR__.'/routes/account.php';
        });
    }


    /**
     * Merge Configuration Settings at run time. If the API has not run
     * the configuration setup command, the default setings are merged in
     *
     */
    protected function mergeConfig ()
    {
        $this->app->configure($this->app_name);
        $this->mergeConfigFrom(
            __DIR__.'/config/'. $this->app_name .'.php',
            $this->app_name
        );
    }


    /**
     * Register the Seeder Command
     *
     */
    protected function registerSeederCommand()
    {
        $this->app->singleton('command.prionapi.seeder', function () {
            return new \Api\Commands\Seeder;
        });
    }


    /**
     * Register the Setup Command
     *
     */
    protected function registerSetupCommand()
    {
        $this->app->singleton('command.prionapi.setup', function () {
            return new \Api\Commands\Setup;
        });
    }


    /**
     * Register the Config Command
     *
     */
    protected function registerConfigCommand()
    {
        $command = $this->commands['Config'];
        $this->app->singleton($command, function () {
            return new \Api\Commands\ConfigCommand;
        });
    }


    /**
     * Register the Account Model
     *
     */
    protected function registerModelAccountCommand()
    {
        $command = $this->commands['ModelAccount'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\Account($app['files']);
        });
    }


    /**
     * Register the Account Group Model
     *
     */
    protected function registerModelAccountGroupCommand()
    {
        $command = $this->commands['ModelAccountGroup'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\AccountGroup($app['files']);
        });
    }


    /**
     * Register the Account Group Permission Model
     *
     */
    protected function registerModelAccountGroupPermissionCommand()
    {
        $command = $this->commands['ModelAccountGroupPermission'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\AccountGroupPermission($app['files']);
        });
    }


    /**
     * Register the Account User Model
     *
     */
    protected function registerModelAccountUserCommand()
    {
        $command = $this->commands['ModelAccountUser'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\AccountUser($app['files']);
        });
    }


    /**
     * Register the Account User Model
     *
     */
    protected function registerModelAccountUserPermissionCommand()
    {
        $command = $this->commands['ModelAccountUserPermission'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\AccountUserPermission($app['files']);
        });
    }


    /**
     * Register the Account User Model
     *
     */
    protected function registerModelAddressCommand()
    {
        $command = $this->commands['ModelAddress'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\Address($app['files']);
        });
    }


    /**
     * Register the Account User Model
     *
     */
    protected function registerModelPhoneCommand()
    {
        $command = $this->commands['ModelPhone'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\Phone($app['files']);
        });
    }


    /**
     * Register the Account User Model
     *
     */
    protected function registerModelUserCommand()
    {
        $command = $this->commands['ModelUser'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\User($app['files']);
        });
    }


    /**
     * Register the Account User Model
     *
     */
    protected function registerModelUserEmailCommand()
    {
        $command = $this->commands['ModelUserEmail'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\UserEmail($app['files']);
        });
    }


    /**
     * Register the Account User Model
     *
     */
    protected function registerModelUserEmailCommand()
    {
        $command = $this->commands['ModelUserEmail'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Model\UserEmail($app['files']);
        });
    }


    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->commands);
    }

}