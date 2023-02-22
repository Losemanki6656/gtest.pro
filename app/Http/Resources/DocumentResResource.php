<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResResource extends JsonResource
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
            'to_user_id' => new UserInfoResource($this->rec_user),
            'type_document_id' => new TypeDocumentResource($this->type_document),
            'to_date' => $this->to_date,
            'executor_id' => new UserInfoResource($this->executor),
            'comment' => $this->comment,
            'file_url' => url(asset($this->file1)),
            'file_name' => $this->to_file1,

        ];
    }
}
