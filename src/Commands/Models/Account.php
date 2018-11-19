<?php

namespace PrionUsers\Commands\Models;

/**
 * This file is part of Setting,
 * a setting management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Console\GeneratorCommand;

class Account extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionusers:model_account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the Prion Users Account Model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Prion Users Account Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/Account.stub';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return config('prionusers.models.accounts', 'Account');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Models\Account';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}