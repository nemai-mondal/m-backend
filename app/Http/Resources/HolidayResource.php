<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
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
            'holiday_name'      =>  $this->holiday_name ?? "",
            'date_from'         =>  $this->date_from ?? "",
            'date_to'           =>  $this->date_to ?? "",
            'days'              =>  $this->days ?? "",
            // 'day'               =>  $this->day ?? "",
            'created_at'        =>  $this->created_at ?? "",
            'updated_at'        =>  $this->updated_at ?? "",
            // 'status'            =>  $this->status ?? "",
        ];
    }
}
