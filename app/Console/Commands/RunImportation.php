<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class RunImportation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import posts from different sources';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sources = collect(File::files(app_path('ImportationEngine')))
            ->filter(fn (SplFileInfo $file) => $file->getBasename() !== 'BaseEngine.php')
            ->values()
            ->map(fn (SplFileInfo $file) => '\\App\\ImportationEngine\\' . $file->getBasename('.php'));

        foreach ($sources as $className) {
            $this->info("Importing from " . class_basename($className));
            $engine = new $className;
            $numberOfPosts = $engine->run();
            if ($numberOfPosts > 0) {
                $this->info("Successfully imported {$numberOfPosts} posts from " . class_basename($className));
            } else {
                $this->info("There were no new posts from " . class_basename($className));
            }
        }

        return 0;
    }
}
