<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
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
            'id'                        =>  $this->id,
            'name'                      =>  $this->name,
            'timezone'                  =>  json_decode($this->timezone) ?? null,
            'shift_end'                 =>  date("g:i A", strtotime($this->shift_end)),
            'shift_start'               =>  date("g:i A", strtotime($this->shift_start)),
            'converted_timezone'        =>  json_decode($this->converted_timezone) ?? null,
            'converted_shift_end'       =>  date("g:i A", strtotime($this->converted_shift_end)),
            'converted_shift_start'     =>  date("g:i A", strtotime($this->converted_shift_start)),
            'created_at'                =>  $this->created_at ?? "",
            'updated_at'                =>  $this->updated_at ?? "",
        ];
    }
}
