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
     * @OA\Get(
     *     path="/api/admin/post",
     *     operationId="/api/admin/post/index",
     *     summary="Get all posts",
     *     tags={"post"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns records",
     *         @OA\JsonContent()
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function index()
    {
        $post = Post::all();

        return $this->response(['data' => $post]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/post",
     *     operationId="/api/admin/post/store",
     *     summary="insert a new post",
     *     tags={"post"},
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="title", type="string", collectionFormat="multi", @OA\Items(type="string")),
     *                  @OA\Property(property="content", type="string", collectionFormat="multi", @OA\Items(type="string")),
     *                  @OA\Property(property="active", type="integer", collectionFormat="multi", @OA\Items(type="integer")),
     *                  required={"title", "content", "active"}
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Add new post",
     *         @OA\JsonContent()
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
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
     * @OA\Get(
     *     path="/api/admin/post/{id}",
     *     operationId="/api/admin/post/show",
     *     summary="Get single post",
     *     tags={"post"},
     *      @OA\Parameter(
     *          name="id", required=true, in="path", description="photo id", @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns single pos",
     *         @OA\JsonContent()
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return $this->response(['data' => $post]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/post/{id}",
     *     operationId="/api/admin/post/update",
     *     summary="update a single post",
     *     tags={"post"},
     *      @OA\Parameter(
     *          name="id", required=true, in="path", @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="title", type="string", collectionFormat="multi", @OA\Items(type="string")),
     *                  @OA\Property(property="content", type="string", collectionFormat="multi", @OA\Items(type="string")),
     *                  @OA\Property(property="active", type="integer", collectionFormat="multi", @OA\Items(type="integer")),
     *                  required={"title", "content", "active"}
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Add new post",
     *         @OA\JsonContent()
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
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
     * @OA\Delete(
     *     path="/api/admin/post/{id}",
     *     operationId="/api/admin/post/destroy",
     *     summary="delete a post",
     *     tags={"post"},
     *      @OA\Parameter(
     *          name="id", required=true, in="path", description="post id", @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="resource deleted successfully",
     *         @OA\JsonContent()
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return $this->response(['message' => 'Action completed successfully'], 200);
    }

    /**
     * @OA\PATCH(
     *     path="/api/admin/post/{id}/active",
     *     operationId="/api/admin/post/activePost",
     *     summary="active/deactive a post",
     *     tags={"post"},
     *      @OA\Parameter(
     *          name="id", required=true, in="path", @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="active", type="integer", description="0,1", collectionFormat="multi", @OA\Items(type="integer")),
     *                  required={"active"}
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Add new post",
     *         @OA\JsonContent()
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
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
