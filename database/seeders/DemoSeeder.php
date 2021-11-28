<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->getOutput()->text('Generating posts');

        Post::factory()->draft()->count(5)->create();
        Post::factory()->active()->count(5)->create();
        Post::factory()->blocked()->count(5)->create();
    }
}
