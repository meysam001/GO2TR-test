<?php

namespace App\Http\Resources;

use App\Utils\GenerateList;
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
            'title' => $this->title,
            'content' => $this->transformed_content,
            'list' => GenerateList::handle($this->content),
        ];
    }
}
