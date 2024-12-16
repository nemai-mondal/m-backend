<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentTypeResource extends JsonResource
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

            'id'            =>  $this->id ?? "",
            'name'          =>  $this->name ?? "",
            'duration'      =>  $this->duration ?? "",
            'duration_type' =>  $this->duration_type ?? "",
            'created_at'    =>  $this->created_at ?? "",
            'updated_at'    =>  $this->updated_at ?? "",
        ];
    }
}
