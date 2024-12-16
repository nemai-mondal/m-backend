<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'id'                    =>  $this->id ?? "",
            'name'                  =>  $this->name ?? "",
            'type'                  =>  $this->type ?? "",
            'site'                  =>  $this->site ?? "",
            'email'                 =>  $this->email ?? "",
            'phone'                 =>  $this->phone ?? "",
            'status'                =>  $this->status ?? "",
            'country'               =>  $this->country ?? "",
            'industry'              =>  $this->industry ?? "",
            'company_name'         =>  $this->company_name ?? "",
            'contact_person'        =>  $this->contact_person ?? "",
            'company_address'      =>  $this->company_address ?? "",
            'opportunity_source'    =>  $this->opportunity_source ?? "",
            'created_at'            =>  $this->created_at ?? "",
            'updated_at'            =>  $this->updated_at ?? "",
            'projects'              =>  $this->whenLoaded('projects'),
        ];
    }
}
