<?php

namespace App\Http\Resources;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

use DateTime;

class TimeLogResource extends JsonResource
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
            'id'            =>  $this->id,
            'date'          =>  $this->date,
            'user_id'       =>  $this->user_id,
            'activity'      =>  $this->activity,
            'terminal'      =>  $this->terminal,
            'messages'      =>  $this->messages,
            'created_at'    =>  $this->created_at ?? "",
            'updated_at'    =>  $this->updated_at ?? "",

            'time'          =>  $this->formatTime($this->time),
            'user_name'     =>  $this->getUserName($this->user_id),
        ];
    }


    protected function getUserName($id) {

        try {

            $user = User::findOrFail($id);

            return $user['first_name'] . " " . $user['middle_name'] . " " . $user['last_name'];

        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'        =>  false,
                'message'       =>  'User not found.',
                'exception'     =>  $e->getMessage()
            ], 400);

        } catch (Exception $e) {

            return response()->json([
                'status'        =>  false,
                'message'       =>  'Something went wrong.',
                'exception'     =>  $e->getMessage()
            ], 500);
        }
    }

    // protected function formatTime($time) {

    //     $time = DateTime::createFromFormat('H:i:s', $time)->format('h:i A');
    // }

    protected function formatTime($time) {
        return Carbon::createFromFormat('H:i:s', $time)->format('h:i A');
    }
    
}
