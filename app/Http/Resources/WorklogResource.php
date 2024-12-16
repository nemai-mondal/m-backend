<?php

namespace App\Http\Resources;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class WorklogResource extends JsonResource
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
            'id'            =>  $this->id ?? "",
            'date'          =>  $this->date ?? "",
            'task_url'      =>  $this->task_url ?? "",
            'created_at'    =>  $this->created_at ?? "",
            'description'   =>  $this->description ?? "",
            
            'client'        =>  $this->getClientDetails($this->client_id) ?? "",
            'project'       =>  $this->getProjectDetails($this->project_id) ?? "",
            'employee'      =>  $this->getEmployeeDetails($this->user_id) ?? "",
            'activity'      =>  $this->getActivityDetails($this->activity_id) ?? "",
            'time_spent'    =>  $this->getFormattedTime($this->time_spent) ?? "",
        ];
    }

    protected function getClientDetails($id) {
        $client = Client::select('id', 'name', 'type', 'site')->find($id);

        if(isset($client) && $client != null) {

            return $client;
        }

        return [];
    }

    protected function getProjectDetails($id) {
        $project = Project::select('id', 'name', 'start_date', 'duration')->find($id);

        if(isset($project) && $project != null) {

            return $project;
        }

        return [];
    }

    protected function getEmployeeDetails($id) {
        $user = User::select('id', 'honorific', 'first_name', 'middle_name', 'last_name', 'email')->find($id);

        if(isset($user) && $user != null) {

            return $user;
        }

        return [];
    }

    protected function getActivityDetails($id) {
        $activity = Activity::select('id', 'name')->find($id);

        if(isset($activity) && $activity != null) {

            return $activity;
        }

        return [];
    }

    protected function getFormattedTime($timeString) {
        $time = Carbon::parse($timeString);
        return $time->format('g\h i\m');
    }
}
