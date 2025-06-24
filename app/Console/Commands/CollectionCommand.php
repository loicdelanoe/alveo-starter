<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CollectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alveo:collection {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a basic vue file for a collection type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = resource_path("js/components/collections/{$this->argument('name')}.vue");

        $fileContent = <<<'EOT'
        <script setup lang="ts">
        defineProps<{
            collection: any
        }>()
        </script>

        <template>
            <pre>{{ collection }}</pre>
        </template>

        <style scoped></style>
        EOT;

        if (! File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        $written = File::put($path, $fileContent);

        if ($written) {
            $this->info('Created new collection template '.$this->argument('name').' in resources/js/components/collections');
        } else {
            $this->error('Failed to create collection file.');
        }
    }
}
