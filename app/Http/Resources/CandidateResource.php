<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                  =>  $this->id ?? "",
            'name'                =>  $this->name ?? "",
            'date_of_joining'     =>  $this->date_of_joining ?? "",
            'department_id'       =>  $this->department_id ?? "",
            'designation_id'      =>  $this->designation_id ?? "",
            'created_at'          =>  $this->created_at ?? "",
            'updated_at'          =>  $this->updated_at ?? "",
        ];
    }
}
