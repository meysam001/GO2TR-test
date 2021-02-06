<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="/api/login",
     *     summary="Authentication and get token",
     *     tags={"Login"},
     *     @OA\RequestBody(
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="email", type="string", collectionFormat="multi", @OA\Items(type="string")),
     *                  @OA\Property(property="password", type="string", collectionFormat="multi", @OA\Items(type="string")),
     *                  required={"email", "password"}
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Get Token",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            return $this->response(['token' => $user->api_token]);
        }

        return $this->response(['error' => 'authentication failed'], 403);
    }
}
