<?php

namespace App\Http\Resources;

use App\Models\Department;
use App\Models\HR;
use App\Models\User;
use App\Models\EmpDesignation;
use App\Models\Designation;
use Illuminate\Http\Resources\Json\JsonResource;

class HRResource extends JsonResource
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
            'id'                =>  $this->id,
            'title'             =>  $this->title,
            'event_date'        =>  $this->event_date,
            'event_start_time'  =>  $this->event_start_time,
            'event_end_time'    =>  $this->event_end_time,
            'created_at'        =>  $this->created_at ?? "",
            'updated_at'        =>  $this->updated_at ?? "",
            'description'       =>  $this->description,
            'created_by_id'     =>  $this->user_id,
            'department_id'     =>  $this->department_id,

            'designation_name'  =>  $this->getDesignationName($this->user_id),
            'user_details'      =>  $this->getUserDetails($this->user_id),

            'department_name'   =>  $this->getDepartmentName($this->department_id),
            'created_by_name'   =>  $this->getCreatedByName($this->user_id),
        ];
    }

    protected function getCreatedByName($id) {

        $user = User::where('id', $id)->where('status', 1)->first();

        if(isset($user) && $user != null) {
            return $user['first_name'] ?? " " .$user['middle_name'] ?? " ".$user['last_name'] ?? " ";
        }

        return "";
    }

    protected function getDepartmentName($id) {

        if($id == 0) {
            return "All";
        }

        $department = Department::where('id', $id)->where('status', 1)->first();

        if(isset($department) && $department != null) {
            return $department['name'];
        }

        return "";
    }

    protected function getDesignationName($userId) {
        $designation = EmpDesignation::where('user_id', $userId)->first();

        if(isset($designation) && $designation != null) {
            $designationId = $designation->designation_id;
            $designationInfo = Designation::where('id', $designationId)->where('status', 1)->first();

            if(isset($designationInfo) && $designationInfo != null) {
                return $designationInfo['name'];
            }
        }

        return "";
    }

    protected function getUserDetails($id)
    {
        $user = User::find($id);

        if (isset($user) && $user != null) {

            $user['image'] = $user->getMedia("profile-picture")->first()->original_url ?? "";
            return $user;
        }

        return null; 
    }
}
