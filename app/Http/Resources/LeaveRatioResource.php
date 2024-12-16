<?php

namespace App\Http\Resources;

use App\Models\EmploymentType;
use App\Models\LeaveType;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRatioResource extends JsonResource
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
            'id'                    =>  $this->id,
            'leave_credit'          =>  $this->leave_credit,
            'leave_frequency'       =>  $this->frequency,
            
            'leave_type'            =>  $this->getLeaveType($this->leave_type_id),
            'employment_type'       =>  $this->getEmploymentType($this->employment_type_id),
            'leave_credit_yearly'   =>  $this->getYearlyLeaveCrdit($this->leave_credit, $this->frequency),
        ];
    }

    protected function getLeaveType($id) {

        $leave_type = LeaveType::find($id);

        if(isset($leave_type) && $leave_type != null) {

            return new LeaveTypeResource($leave_type);
        }

        return [];
    }

    protected function getEmploymentType($id) {

        $employment_type = EmploymentType::find($id);

        if(isset($employment_type) && $employment_type != null) {

            return new EmploymentTypeResource($employment_type);
        }

        return [];
    }
    
    protected function getYearlyLeaveCrdit($leave_credit, $frequency) {

        if(strtolower($frequency) == 'monthly') {
            return $leave_credit * 12;
        }

        return "";
    }

}
