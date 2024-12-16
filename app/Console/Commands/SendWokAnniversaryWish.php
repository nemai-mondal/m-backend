<?php

namespace App\Console\Commands;

use App\Mail\SendWorkAnniversaryMail;
use App\Models\EmpProfessionalDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWokAnniversaryWish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:workanniversarywish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to send a Work Anniversary Wish to all the working Employees.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get the current date
        $currentDate = Carbon::now();

        // Get the current day and month
        $currentDay     = $currentDate->day;
        $currentMonth   = $currentDate->month;

        // Query users whose joining date matches the current day and month
        $personal_details = EmpProfessionalDetail::whereMonth('date_of_joining', $currentMonth)
                                        ->whereDay('date_of_joining', $currentDay)
                                        ->get();

        

        foreach($personal_details as $personal_detail) 
        {
            $user           = User::find($personal_detail['user_id']);
            
            $data['name']   = $user['honorific'].' '.$user['first_name'].' '.$user['middle_name'].' '.$user['last_name'];
            
            Mail::to($user['email'])->send(new SendWorkAnniversaryMail($data));
        }

        return true;
    }
}
