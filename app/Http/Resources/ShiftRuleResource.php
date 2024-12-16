<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftRuleResource extends JsonResource
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
            'time_in_minutes'       =>  $this->time_in_minutes ?? "",
            'created_at'            =>  $this->created_at ?? "",
            'updated_at'            =>  $this->updated_at ?? "",
        ];    }
}
