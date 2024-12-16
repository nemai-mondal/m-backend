<?php

namespace App\Http\Resources;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) 
    {
        $mediaItem = $this->resource;

        return [
            'id'                    =>  $this->id ?? "",
            'name'                  =>  $this->name ?? "",
            'status'                =>  $this->status ?? "",
            'document'              =>  $this->getMedia("amendment")->first()->original_url ?? "",
            'updated_at'            =>  $this->updated_at ?? "",
            'created_at'            =>  $this->created_at ?? "",
            'description'           =>  $this->description ?? "",
            'added_by_id'           =>  $this->getUserName($this->added_by_id),
        ];    
    }

    protected function getUserName($id)
    {

        $user = User::where('id', $id)->where('status', 1)->first();

        if (isset($user) && $user != null) {
            return $user['first_name'] ?? " " . $user['first_name'] ?? " " . $user['last_name'] ?? " ";
        }

        return "";
    }
}
