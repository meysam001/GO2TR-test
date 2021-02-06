<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->delete();

        Post::create([
            'title' => 'test title',
            'content' => '<h2>Header 1</h2>
    <h2>Header 2</h2>
    <h3>Header 2.1</h3>
    <h4>Header 2.1.1</h4>
    <h2>Header 3</h2>
    <h3>Header 3.1</h3>',
            'active' => 1,
        ]);
    }
}
