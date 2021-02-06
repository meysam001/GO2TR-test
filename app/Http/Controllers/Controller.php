<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Info(
     *   title="Go2TR Back-end Test API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="meysam0011212@gmail.com",
     *     name="Meysam Mahmoudi"
     *   )
     * )
     */
    public function response(array $data, $httpStatus = 200) {
        return response()->json($data, $httpStatus);
    }
}
