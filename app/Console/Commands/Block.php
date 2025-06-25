<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Block extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alveo:block {name} {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a basic vue file for a block';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = resource_path("js/components/blocks/{$this->argument('name')}.vue");
        $stubPath = base_path('stubs/blocks');

        switch ($this->option('type')) {
            case 'archive':
                $fileContent = File::get("{$stubPath}/collection.stub");
                break;
            case 'form':
                $fileContent = File::get("{$stubPath}/form.stub");
                break;
            default:
                $fileContent = File::get("{$stubPath}/default.stub");
                break;
        }

        if (! File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        $written = File::put($path, $fileContent);

        if ($written) {
            $this->info('Created new Block '.$this->argument('name').' in resources/js/components/blocks');
        } else {
            $this->error('Failed to create block.');
        }
    }
}
