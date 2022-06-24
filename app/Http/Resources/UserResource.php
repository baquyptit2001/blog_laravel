<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address ?? config('constants.null'),
            'phone_number' => $this->phone_number ?? config('constants.null'),
            'live' => $this->live ?? config('constants.null'),
            'birthday' => $this->birthday ? Carbon::parse($this->birthday)->format('Y-m-d') : config('constants.null'),
        ];
    }
}
