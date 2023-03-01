<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutgoingResource extends JsonResource
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
            'type_document' => new TypeDocumentResource($this->type_document),
            'send_user' => new UserDocumentResource($this->send_user),
            'to_date' => $this->to_date,
            'status_file' => $this->status_file,
            'status_send' => $this->status_send,
            'status_doc' => $this->status_send,
            'workers' => $this->workers->count()
        ];
    }
}
