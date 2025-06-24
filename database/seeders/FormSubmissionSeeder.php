<?php

namespace Database\Seeders;

use App\Models\FormSubmission;
use Illuminate\Database\Seeder;

class FormSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormSubmission::create([
            'form_id' => 1,
            'data' => [
                'name' => 'John Doe',
                'email' => 'jon@example.com',
                'message' => 'Hello, this is a test message.',
            ],
            'submitted_at' => now(),
        ]);
    }
}
