<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'ar_date'                   =>  $this->ar_date ?? '',
            'user_id'                   =>  $this->user_id ?? '',
            'day_type'                  =>  $this->day_type,
            'shift_id'                  =>  $this->shift_id,
            'work_time'                 =>  $this->work_time ?? '',
            'login_remarks'             =>  $this->login_remarks,
            'logout_remarks'            =>  $this->logout_remarks ?? '',
            'is_regularized'            =>  $this->is_regularized,
            'regularized_by'            =>  $this->regularized_by ?? '',
            'actual_shift_end'          =>  $this->actual_shift_end,
            'total_login_hours'         =>  $this->total_login_hours ?? '',
            'actual_shift_start'        =>  $this->actual_shift_start,
            'total_working_hours'       =>  $this->total_working_hours ?? '',
            'leave_application_id'      =>  $this->leave_application_id,
            'regularization_remarks'    =>  $this->regularization_remarks ?? '',
            'created_at'                =>  $this->created_at ?? "",
            'updated_at'                =>  $this->updated_at ?? "",
        ];
    }
}
