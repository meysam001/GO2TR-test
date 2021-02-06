<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/home",
     *     operationId="home/content",
     *     summary="show all posts",
     *     tags={"content"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns records",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function index(Request $request)
    {
        $post = Post::all();

        return PostResource::Collection($post);
    }
}
