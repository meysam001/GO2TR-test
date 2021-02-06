<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use mysql_xdevapi\Collection;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $post = Post::all();

        return PostResource::Collection($post);
        return $this->response(['data' => $post->content, 'parsed' => $post->parsed_content]);
    }
}
