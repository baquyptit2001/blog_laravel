<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'id' => $this->id,
            'user' => UserResource::make($this->user),
//            'post' => PostResource::make($this->post),
            'content' => $this->content,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'created_at_human' => $this->created_at_human,
        ];
    }
}
