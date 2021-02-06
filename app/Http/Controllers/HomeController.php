<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $post = Post::find(2);


        return $this->response(['data' => $post->content, 'parsed' => $post->parsed_content]);
    }
}
