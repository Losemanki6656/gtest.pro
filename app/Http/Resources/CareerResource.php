<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CareerResource extends JsonResource
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
            'sort' => $this->sort,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'staff' => $this->staff,
            'status' => $this->status

        ];
    }
}
