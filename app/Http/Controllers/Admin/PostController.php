<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::all();

        return new PostResource($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->response(['error' => 'Input data is invalid'], 400);
        }

        Post::create($request->all());

        return $this->response(['message' => 'Action completed successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->response(['error' => 'Input data is invalid'], 400);
        }

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return $this->response(['message' => 'Action completed successfully'], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return $this->response(['message' => 'Action completed successfully'], 200);
    }

    public function activePost(Request $request, $id)
    {
        $validator = Validator::make($request->only('active'), ['active' => 'required|boolean']);
        if ($validator->fails()) {
            return $this->response(['error' => $validator->errors()], 400);
        }

        $post = Post::findOrFail($id);
        $post->update($request->only('active'));

        return $this->response(['message' => 'Action completed successfully'], 204);
    }

    private function rules()
    {
        return [
          'title' => 'required|max:255',
          'content' => 'required',
          'active' => 'required|boolean',
        ];
    }
}
