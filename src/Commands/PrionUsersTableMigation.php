<?php

namespace PrionUsers\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;

class PrionUsersTableMigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionusers:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the users table';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new notifications table command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  \Illuminate\Support\Composer    $composer
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->laravel->view->addNamespace('prionusers\\PrionUsers', substr(__DIR__, 0, -8).'views');
        $this->line('');
        $this->info("Prion Development Users Migration Creation.");
        $this->line('');
        $this->comment($this->generateMigrationMessage());

        $existingMigrations = $this->alreadyExistingMigrations();
        $defaultAnswer = true;

        if ($existingMigrations) {
            $this->line('');

            $this->warn($this->getExistingMigrationsWarning($existingMigrations));

            $defaultAnswer = false;
        }

        $this->line('');

        if (! $this->confirm("Proceed with the migration creation?", $defaultAnswer)) {
            return;
        }

        $this->line('');

        $this->line("Creating migration");

        if ($this->createMigration()) {
            $this->info("Migration created successfully.");
        } else {
            $this->error(
                "Couldn't create migration.\n".
                "Check the write permissions within the database/migrations directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the migration.
     *
     * @return bool
     */
    protected function createMigration()
    {
        $migrationPath = $this->getMigrationPath();

        $output = $this->laravel->view
            ->make('prionusers:migration')
            ->with(['prionusers' => config('prionusers')])
            ->render();

        if (!file_exists($migrationPath) && $fs = fopen($migrationPath, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Generate the message to display when running the
     * console command showing what tables are going
     * to be created.
     *
     * @return string
     */
    protected function generateMigrationMessage()
    {
        $tables = Collection::make(config('prionusers.tables'))
            ->sort();

        return "A migration that creates {$tables->implode(', ')} "
            . "tables will be created in database/migrations directory";
    }

    /**
     * Build a warning regarding possible duplication
     * due to already existing migrations.
     *
     * @param  array  $existingMigrations
     * @return string
     */
    protected function getExistingMigrationsWarning(array $existingMigrations)
    {
        $base = "Prion Development Users migrations already exist.\nFollowing";
        if (count($existingMigrations) > 1) {
            $base .= " files were found: ";
        } else {
            $base .= " file was found: ";
        }

        return $base . array_reduce($existingMigrations, function ($carry, $fileName) {
                return $carry . "\n - " . $fileName;
            });
    }

    /**
     * Check if there is another migration
     * with the same suffix.
     *
     * @return array
     */
    protected function alreadyExistingMigrations()
    {
        $matchingFiles = glob($this->getMigrationPath('*'));

        return array_map(function ($path) {
            return basename($path);
        }, $matchingFiles);
    }

    /**
     * Get the migration path.
     *
     * The date parameter is optional for ability
     * to provide a custom value or a wildcard.
     *
     * @param  string|null  $date
     * @return string
     */
    protected function getMigrationPath($date = null)
    {
        $date = $date ?: date('Y_m_d_His');

        return database_path("migrations/${date}_{$this->migrationSuffix}.php");
    }

}