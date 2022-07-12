<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape(['id' => "mixed", 'user' => "\App\Http\Resources\UserResource", 'content' => "mixed", 'created_at' => "string", 'created_at_human' => "mixed"])] public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->user),
            'content' => $this->content,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'created_at_human' => $this->created_at_human,
        ];
    }
}
