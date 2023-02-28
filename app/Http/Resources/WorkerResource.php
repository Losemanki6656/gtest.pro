<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
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
            'photo' => url(asset($this->photo)),
            'education_id' => $this->education,
            'region_id' => $this->region,
            'city_id' => $this->city,
            'nationality_id' => $this->nationality,
            'academic_degree_id' => $this->academic_degree,
            'academic_title_id' => $this->academic_title,
            'address_region_id' => $this->address_region,
            'address_city_id' => $this->address_city,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'staff_name' => $this->staff_name,
            'birth_date' => $this->birth_date,
            'rail_date' => $this->rail_date,
            'rail_status' => $this->rail_status,
            'old_job_name' => $this->old_job_name,
            'del_rail_comment' => $this->del_rail_comment,
            'passport' => $this->passport,
            'jshshir' => $this->jshshir,
            'address' => $this->address,
            'other_doc' => $this->other_doc,
            'file1' => url(asset($this->file1)),
            'file2' => $this->file2,
            'file3' => $this->file3,
            'comment' => $this->comment,
            'party' => $this->party,
            'deputy' => $this->deputy,
            'military' => $this->military,
            'military_rank' => $this->military_rank,
            'institut' => $this->institut,
            'speciality' => $this->speciality,
            'phone' => $this->phone,
            'languages' => $this->languages,
            'driver_licensies' => $this->driver_licensies,
            'sex' => $this->sex
        ];
    }
}
