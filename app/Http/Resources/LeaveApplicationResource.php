<?php

namespace App\Http\Resources;

use App\Models\Department;
use App\Models\Designation;
use App\Models\EmpDesignation;
use App\Models\LeaveApplication;
use App\Models\LeaveReview;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveApplicationResource extends JsonResource
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
            'user_id'                   =>  $this->user_id,
            'remarks'                   =>  $this->remarks,
            'leave_to'                  =>  $this->leave_to,
            'attachment'                =>  $this->attachment,
            'leave_from'                =>  $this->leave_from,
            'total_days'                =>  $this->total_days,
            'leave_status'              =>  $this->leave_status,
            'leave_type_id'             =>  $this->leave_type_id,
            'leave_value_end'           =>  $this->leave_value_end,
            'leave_value_start'         =>  $this->leave_value_start,
            'email_notification_to'     =>  $this->email_notification_to,
            'created_at'                =>  $this->created_at,
            'updated_at'                =>  $this->updated_at,
            'leave-prescription'        =>  $this->getMedia("leave-prescription")->first()->original_url ?? "",
            'user'                      =>  $this->getUserDetails($this->user_id),
            'leave_type'                =>  $this->getLeaveType($this->leave_type_id),
            'designation'               =>  $this->getDesignation($this->user_id),
            'members_on_leave'          =>  $this->getMembersOnLeave($this->id, $this->leave_from, $this->leave_to),
            'action_taken_by'           =>  $this->getReviewedByDetails($this->id),
            'action_taken_at'           =>  $this->getReviewedAtDetails($this->id),
            'leave_review'              =>  $this->getReviewedDetails($this->id, $this->leave_status),
        ];
        
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

    protected function getLeaveType($id)
    {

        $leave_type = LeaveType::find($id);

        if (isset($leave_type) && $leave_type != null) {

            return new LeaveTypeResource($leave_type);
        }

        return [];
    }

    protected function getDesignation($user_id)
    {

        $emp_designation = EmpDesignation::with('designation')->where('user_id', $user_id)->first();

        if (isset($emp_designation) && $emp_designation != null) {
            return Designation::find($emp_designation['designation_id']);
        }

        return [];


        // $designation = Designation::find($id);

        // if (isset($designation) && $designation != null) {

        // return new DesignationResource($designation);
        // }

        // return [];
    }
    
    protected function getMembersOnLeave($leaveId, $startDate, $endDate)
    {

        $user_ids = LeaveApplication::select('user_id')
            ->where('leave_status', 'approved')
            ->where('id', '!=', $leaveId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('leave_from', '<=', $endDate)
                        ->where('leave_to', '>=', $startDate);
                });
            })
            ->get();

        $users = User::whereIn("id", $user_ids)->get();

        return $users;
    }

    protected function getReviewedByDetails($id)
    {
        $full_name = "";

        
        if($this->leave_status == "pending") {
            $user   = User::where('id', 1)->first();
        }
        else {
            $review = LeaveReview::select('user_id')->where('application_id', $id)->first();

            if(!isset($review)) {
                return "";
            }

            $user   = User::find($review['user_id']);
            // $user   = User::select('honorific', 'first_name', 'middle_name', 'last_name')->where('id', $review['user_id'])->first();
        }

        if(!isset($user)) {
            return "";
        }

        if($user->honorific != "") {
            $full_name = $user->honorific;
        }
        
        if($user->first_name != "") {
            $full_name = $full_name != "" ? $full_name." ".$user->first_name : $user->first_name;
        }
        
        if($user->middle_name != "") {
            $full_name = $full_name != "" ? $full_name." ".$user->middle_name : $user->middle_name;
        }
        
        if($user->last_name != "") {
            $full_name = $full_name != "" ? $full_name." ".$user->last_name: $user->last_name;
        }
        return $full_name;
    }

    protected function getReviewedAtDetails($id)
    {
        if($this->leave_status == 'pending') {
            return $this->updated_at;
        }
        $review = LeaveReview::select('updated_at')->where('application_id', $id)->first();
        return isset($review['updated_at']) ? $review['updated_at'] : "";
    }

    protected function getReviewedDetails($leave_id, $leave_status) {

        if($leave_status != 'pending') {

            $leaveReview = LeaveReview::with('user')->where('application_id', $leave_id)->get();

            if(isset($leaveReview) && sizeof($leaveReview) > 0) {

                return $leaveReview;
            }
        }

        return [];
    }
}
