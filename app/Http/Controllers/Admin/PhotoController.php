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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Photo::all();

        return $this->response(['data' => $post]);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $photo = Photo::findOrFail($id);
        return $this->response(['data' => $photo]);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
