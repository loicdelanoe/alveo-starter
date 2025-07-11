<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::factory(10)->create();

        Page::factory()->create([
            'title' => 'Home',
            'slug' => 'home',
            'is_home' => true,
        ]);
    }
}
