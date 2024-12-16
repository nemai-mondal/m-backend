<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use App\Models\Technology;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\User;

class ProjectResource extends JsonResource
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
            'name'                      =>  $this->name ?? "",
            'cost'                      =>  $this->cost ?? "",
            'status'                    =>  $this->status ?? 0,
            'client'                    =>  $this->client,
            'end_date'                  =>  $this->end_date ?? "",
            'priority'                  =>  $this->priority ?? "",
            // 'client_id'                 =>  $this->client_id ?? "",
            'start_date'                =>  $this->start_date ?? "",
            'created_at'                =>  $this->created_at ?? "",
            'updated_at'                =>  $this->updated_at ?? "",
            // 'manager_id'                =>  $this->manager_id ?? "",
            'experience'                =>  $this->experience ?? null,
            'description'               =>  $this->description ?? "",
            'salary_range'              =>  $this->salary_range ?? null,
            'project_type'              =>  $this->project_type ?? "",
            'currency_type'             =>  $this->currency_type ?? "",
            'notice_period'             =>  $this->notice_period ?? null,
            'project_status'            =>  $this->project_status ?? "",
            'no_of_openings'            =>  $this->no_of_openings ?? null,
            'manager'                   =>  $this->projectManager,
            'estimation_type'           =>  $this->estimation_type ?? "",
            'estimation_value'          =>  $this->estimation_value ?? "",

            'project_documents'         =>  $this->getdocument(),
            'file'                      =>  $this->getfile(),

        ];
    }

    public function getfile()
    {
        $projectDocuments = ProjectDocument::where('project_id', $this->id)->get();
        foreach ($projectDocuments as $document) {
            return $document->getMedia("project-document")->first()->original_url ?? "";
        }
    }

    public function getdocument()
    {
        $projectDocuments = ProjectDocument::where('project_id', $this->id)->get();
        return $projectDocuments;
    }

    // protected function getClient($id)
    // {
    //     $Client = Client::where('id', $id)->first();
    //     return $Client;
    // }

    // protected function getUser($id)
    // {
    //     $user = User::where('id', $id)->first();
    //     return $user;
    // }
}
