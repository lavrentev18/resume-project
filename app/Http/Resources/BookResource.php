<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
        "name"=> $this->name,
        "author"=> $this->author,
        "slug"=> $this->slug,
        "genre"=> $this->genre,
        "publisher"=> $this->publisher,
        "description"=> $this->description,
        "user_id"=> $this->user_id,
        "reserved_at"=> $this->reserved_at,
        "take_at"=> $this->take_at,
        "created_at"=> $this->created_at,
        "updated_at"=> $this->updated_at,
        ];
    }
}
