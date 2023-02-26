<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerRelativeResource extends JsonResource
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
            'relative' => new RelativeResource($this->relative),
            'fullname' => $this->fullname,
            'birth_place' => $this->birth_place,
            'post' => $this->post,
            'address' => $this->address
        ];
    }
}
