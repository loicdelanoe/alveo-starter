<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alveo:menu {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the menu view for the Alveo application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = resource_path("js/components/partials/{$this->argument('name')}.vue");
        $stubPath = base_path('stubs/menus/menu.stub');

        $fileContent = File::get($stubPath);

        if (! File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        $written = File::put($path, $fileContent);

        if ($written) {
            $this->info('Created new Menu '.$this->argument('name').' in resources/js/components/partials');
        } else {
            $this->error('Failed to create menu.');
        }
    }
}
