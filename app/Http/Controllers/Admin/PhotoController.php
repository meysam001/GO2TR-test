<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateThumbnail;
use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class PhotoController extends Controller
{
    private $photoModel;

    /**
     * @OA\Get(
     *     path="/api/admin/photo",
     *     operationId="/api/admin/photo/index",
     *     summary="Get all photos",
     *     tags={"photo"},
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
        $post = Photo::all();

        return $this->response(['data' => $post]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/photo",
     *     operationId="/api/admin/photo/store",
     *     summary="upload a new photo",
     *     tags={"photo"},
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="file", type="string", description="photo", @OA\Items(type="string", format="binary")),
     *                  @OA\Property(property="description", type="string", description="photo description", @OA\Items(type="string")),
     *                  required={"file", "description"}
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="resource created successfully",
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
            return $this->response(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $path = $file->store('public');
        list($width, $height) = getimagesize($file->path());

        $photo = Photo::create([
            'path' => $path,
            'width' => $width,
            'height' => $height,
            'description' => $request->description,
            'url' => Storage::url($path),
        ]);

        // dispatch job to generate thumbnail in background
        GenerateThumbnail::dispatch($photo);

        return $this->response(['message' => 'resource created successfully'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/photo/{id}",
     *     operationId="/api/admin/photo/show",
     *     summary="Get single photo",
     *     tags={"photo"},
     *      @OA\Parameter(
     *          name="id", required=true, in="path", description="photo id", @OA\Schema(type="string")
     *     ),
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
    public function show($id)
    {
        $photo = Photo::findOrFail($id);
        return $this->response(['data' => $photo]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/photo/{id}",
     *     operationId="/api/admin/photo/update",
     *     summary="upload a photo",
     *     tags={"photo"},
     *      @OA\Parameter(
     *          name="id", required=true, description="photo id", in="path", @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="file", type="string", description="photo path", @OA\Items(type="string", format="binary")),
     *                  @OA\Property(property="description", type="string", description="photo description", @OA\Items(type="string")),
     *                  required={"file", "description"}
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="resource updated successfully",
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
            return $this->response(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $path = $file->store('public');
        list($width, $height) = getimagesize($file->path());

        $photo = Photo::findOrFail($id);
        $photo->update([
            'path' => $path,
            'width' => $width,
            'height' => $height,
            'description' => $request->description,
            'url' => Storage::url($path),
        ]);

        return $this->response(['message' => 'resource updated successfully'], 204);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/photo/{id}",
     *     operationId="/api/admin/photo/destroy",
     *     summary="delete a photo",
     *     tags={"photo"},
     *      @OA\Parameter(
     *          name="id", required=true, in="path", description="photo id", @OA\Schema(type="string")
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
        $post = Photo::findOrFail($id);
        $post->delete();

        return $this->response(['message' => 'resource deleted successfully'], 200);
    }

    private function rules()
    {
        return [
            'file' => 'required|mimes:jpg,png|max:50000',
            'description' => 'required|max:255',
        ];
    }
}
