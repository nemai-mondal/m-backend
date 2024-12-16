<?php

namespace App\Http\Resources;

use App\Models\Interviewer;
use App\Models\InterviewScreening;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
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
            'date'                      =>  $this->date,
            'time'                      =>  $this->time,
            'resume'                    =>  $this->resume,
            'interview_link'            =>  $this->interview_link,
            'designation_applied_for'   =>  $this->designation_applied_for,

            'file'                      =>  $this->getfile(),
            'interviewers'              =>  $this->getInterviewers($this->id),
            'interview_created_by'      =>  $this->getInterviewCreatedBy($this->interview_created_by),
        ];
    }

    public function getfile()
    {
        $interview_document = InterviewScreening::where('user_id ', $this->id)->get();
        foreach ($interview_document as $document) {
            return $document->getMedia("interview-resume")->first()->original_url ?? "";
        }
    }

    public function getInterviewers($id) {

        $interviewer_ids = Interviewer::select('interviewer_id')->where('interview_id', $id)->get();
        if(isset($interviewer_ids) && sizeof($interviewer_ids) > 0) {

            $interviewers   =   User::select(
                'honorific',
                'first_name',
                'middle_name',
                'last_name',
                'employee_id',
                'email',    
            )->whereIn('id', $interviewer_ids)->get();
            
            if(isset($interviewers) && sizeof($interviewers) > 0) {

                return $interviewers;
            }

            return [];
        }

        return [];
    }

    public function getInterviewCreatedBy($id) {

        $user = User::select(
            'honorific',
            'first_name',
            'middle_name',
            'last_name',
            'employee_id',
            'email',
            )->find($id);
            
        if(isset($user) && $user != null) {

            return $user;
        }

        return [];
    }
}
