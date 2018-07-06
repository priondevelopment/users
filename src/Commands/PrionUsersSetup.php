<?php

namespace PrionUsers\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;

class PrionUsersSetup extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionusers:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the Prion Development Users package.';

    /**
     * Create a new notifications table command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  \Illuminate\Support\Composer $composer
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

    }
}