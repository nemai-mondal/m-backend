<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class EventWishResource extends JsonResource
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
            'message'       =>  $this->message ?? "",
            'created_at'    =>  $this->created_at ?? "",
            'user'          =>  $this->getuserDetails($this->wish_from_id) ?? "",
        ];
    }

    protected function getuserDetails($wish_from_id ) {
        $user = User::select('id', 'honorific','first_name','middle_name','last_name')->find($wish_from_id);

        if(isset($user) && $user != null) {

            return $user;
        }

        return [];
    }
}
