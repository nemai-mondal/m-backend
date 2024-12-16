<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MotivationalQuoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $mediaItem = $this->getMedia("author-avatar")->first();

        return [
            'id'                =>  $this->id ?? "",
            'quote'             =>  $this->quote ?? "",
            'image'             =>  $this->getMedia("author-avatar")->first()->original_url ?? "",
            'said_by'           =>  $this->said_by ?? "",
            'created_at'        =>  $this->created_at ?? "",
            'updated_at'        =>  $this->updated_at ?? "",
            'display_date'      =>  $this->display_date ?? "",
            'created_by_id'     =>  $this->user_id ?? "",
            'created_by_name'   =>  $this->getUserName($this->user_id),
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
