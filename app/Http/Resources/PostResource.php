<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $this->thumbnail,
            'category' => $this->category,
            'user' => $this->user,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'created_at_human' => $this->created_at_human,
            'updated_at' => $this->updated_at,
        ];
    }
}
