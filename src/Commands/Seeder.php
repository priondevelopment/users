<?php

namespace Api\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;

class Seeder extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionapi:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the Prion Development Api tables.';

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
        $this->laravel->view->addNamespace('prionapi', substr(__DIR__, 0, -8).'views');

        if (file_exists($this->seederPath())) {
            $this->line('');

            $this->warn("The Prion Api Seeder file already exists. Delete the existing one if you want to create a new one.");
            $this->line('');
            return;
        }

        if ($this->createSeeder()) {
            $this->info("Seeder successfully created!");
        } else {
            $this->error(
                "Couldn't create seeder.\n".
                "Check the write permissions within the database/seeds directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the seeder
     *
     * @return bool
     */
    protected function createSeeder()
    {
        $models = config('prionapi.models');

        $output = $this->laravel->view->make('prionapi::seeder')
            ->with(compact([
                'models',
            ]))
            ->render();

        if ($fs = fopen($this->seederPath(), 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Get the seeder path.
     *
     * @return string
     */
    protected function seederPath()
    {
        return database_path("seeds/PrionApiSeeder.php");
    }

}