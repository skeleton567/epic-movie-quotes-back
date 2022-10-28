<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => $this->type,
            'seen' => $this->seen_by_user,
            'created_at' => $this->created_at,
            'user' => UserResource::make($this->user),
            'user_to_notify' => UserResource::make($this->userToNotify),
        ];
    }
}
