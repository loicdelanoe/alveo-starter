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

        switch ($this->option('type')) {
            case 'collection':
                $fileContent = <<<'EOT'
        <script setup lang="ts">
        defineProps<{
            content: any
            collections: any
        }>()
        </script>

        <template>
            <pre>{{ content }}</pre>
            <pre>{{ collections }}</pre>
        </template>

        <style scoped></style>
        EOT;
                break;
            case 'form':
                $fileContent = <<<'EOT'
        <script setup lang="ts">
        import { useDynamicForm } from '@/composables/useDynamicForm';
        import { Form } from '@/types/models/form';

        const props = defineProps<{
            content: any
            forms: Form[]
        }>()

        /*
        |--------------------------------------------------------------------------
        | Form Selection
        |--------------------------------------------------------------------------
        |
        | We attempt to locate a form by its unique slug. You should replace
        | `'my-form'` with the appropriate slug based on your use case.
        | An error is thrown if the form is not found.
        |
        */
        const myForm = props.forms.find((form) => form.slug === 'my-form');

        if (!myForm) {
            throw new Error('Form not found');
        }

        /*
        |--------------------------------------------------------------------------
        | Dynamic Form Initialization
        |--------------------------------------------------------------------------
        |
        | The `useDynamicForm` composable provides:
        |
        | - `form`        : Reactive form data.
        | - `onSubmit`    : Submission handler.
        | - `errors`      : Validation error state.
        | - `showMessage` : Success message state (e.g. after submission).
        |
        | This allows rendering and managing a fully dynamic form based
        | on the selected configuration.
        |
        */
        const { form, onSubmit, errors, showMessage } = useDynamicForm(myForm);
        </script>

        <template>
            <pre>{{ content }}</pre>
            <pre>{{ form }}</pre>
        </template>

        <style scoped></style>
        EOT;
                break;
            default:
                $fileContent = <<<'EOT'
        <script setup lang="ts">
        defineProps<{
            content: any
        }>()
        </script>

        <template>
            <pre>{{ content }}</pre>
        </template>

        <style scoped></style>
        EOT;
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
