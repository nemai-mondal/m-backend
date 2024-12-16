<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpFamilyResource extends JsonResource
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

            'id'                =>  $this->id ?? "",
            'title'             =>  $this->title ?? "",
            'gender'            =>  $this->gender ?? "",
            'remarks'           =>  $this->remarks ?? "",
            'relation'          =>  $this->relation ?? "",
            'is_depend'         =>  $this->is_depend ?? "",
            'proffesion'        =>  $this->proffesion ?? "",
            'employment'        =>  $this->employment ?? "",
            'nationality'       =>  $this->nationality ?? "",
            'blood_group'       =>  $this->blood_group ?? "",
            'date_of_birth'     =>  $this->date_of_birth ?? "",
            'marriage_date'     =>  $this->marriage_date ?? "",
            'insurance_name'    =>  $this->insurance_name ?? "",
            'contact_number'    =>  $this->contact_number ?? "",
            'maritial_status'   =>  $this->maritial_status ?? "",
            'health_insurance'  =>  $this->health_insurance ?? "",
            'created_at'        =>  $this->created_at ?? "",
            'updated_at'        =>  $this->updated_at ?? "",
        ];

    }
}
