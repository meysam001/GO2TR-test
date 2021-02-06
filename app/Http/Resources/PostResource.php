<?php

namespace App\Http\Resources;

use App\Utils\Utility;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => Utility::transformShortCodes($this->content),
            'list' => '',
        ];
    }
}
