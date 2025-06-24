<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Form::create([
            'name' => 'Contact Form',
            'slug' => 'contact-form',
            'fields' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                    'type' => 'text',
                    'validation' => ['required', 'min:3'],
                ],
                [
                    'label' => 'Email',
                    'name' => 'email',
                    'type' => 'email',
                    'validation' => ['required', 'email'],
                ],
                [
                    'label' => 'Message',
                    'name' => 'message',
                    'type' => 'textarea',
                    'validation' => ['required', 'max:500'],
                ],
            ],
            'success_message' => 'A simple contact form for users to reach out.',
        ]);
    }
}
