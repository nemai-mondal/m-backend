<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceAssign;
use App\Models\AttendanceLog;
use App\Models\AttendanceReport;
use App\Models\AttendanceWork;
use App\Models\EmpShift;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\Shift;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
// use Illuminate\Support\Facades\Date;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to run the Attendance Cron Job.';

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
    /**
     * Time logs API
     */
    public function handle()
    {
        $users = User::get();

        foreach ($users as $user) {
            $shift = EmpShift::with('shift')->where('user_id', $user['id'])->first();
            $shift_start = TimeLog::where('user_id', $user['id'])
                ->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('activity', ['shift_start', 'shift start'])
                ->orderBy('id', 'asc')
                ->first();

            $shift_end = TimeLog::where('user_id', $user['id'])
                ->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('activity', ['shift_end', 'shift end'])
                ->orderBy('id', 'desc')
                ->first();

            $user_regularization_data = [
                'user_id'                       =>  $user['id'] ?? null,
                'regularizaion_date'            =>  Carbon::now()->format('Y-m-d'),
                'shift_id'                      =>  $shift['shift']['id'] ?? null,
                'shift_start_time'              =>  $shift['shift']['shift_start'] ?? null,
                'shift_end_time'                =>  $shift['shift']['shift_end'] ?? null,
                'is_regularized'                =>  0,
                'regularization_requested_by'   =>  null,
                'regularization_approved_by'    =>  null,
                'regularization_date'           =>  null,
                'regularization_remarks'        =>  null,
                'user_login_time'               =>  isset($shift_start) && $shift_start != null ? $shift_start['time'] : null,
                'user_logout_time'              =>  isset($shift_end) && $shift_end != null ? $shift_end['time'] : null,
                'login_remarks'                 =>  isset($shift_start) && $shift_start != null ? $shift_start['messages'] : null,
                'logout_remarks'                =>  isset($shift_end) && $shift_end != null ? $shift_end['messages'] : null,
                'processing_status'             =>  'successful',
                'processing_remarks'            =>  null,
            ];

            $attendance_assign  = false;
            $totalWorkBreakup   = false;

            /**
             * Response sample for - $this->getAttendanceAssignedToUser()
             * 01
             * {
             *     "id": 1,
             *     "user_id": null,
             *     "shift_id": 1,
             *     "attendance_regularization_id": 1,
             *     "attendance_working_hour_id": 1,
             *     "effective_from": "2024-04-01",
             *     "effective_to": "2024-04-30",
             *     "status": "1",
             *     "created_by": 2,
             *     "created_at": "2024-03-29T13:04:14.000000Z",
             *     "updated_at": "2024-03-29T13:04:40.000000Z",
             *     "deleted_at": null
             * }
             * 
             * 02
             * false
             */
            $attendance_assign = $this->getAttendanceAssignedToUser($user['id']);

            if ($attendance_assign == false) {

                $user_regularization_data['processing_status']  = 'failed';
                $user_regularization_data['processing_remarks'] = 'Attendance Assign was not assigned.';

                AttendanceReport::create($user_regularization_data);

                continue;
            } else {

                $user_regularization_data['attendance_assign_id'] = $attendance_assign['id'] ?? null;

                /**
                 * Response sample for - $this->getUserWorkingHours()
                 * Times are in seconds
                 * 01
                 * {
                 *  "workTime": 8820,
                 *  "breakTime": 9,
                 *  "totalTime": 8829
                 *  "user_login_time": null,
                 *  "user_logout_time": null,
                 * }
                 * 
                 * 02
                 * {
                 *  "workTime": 0,
                 *  "breakTime": 0,
                 *  "totalTime": 0
                 *  "user_login_time": null,
                 *  "user_logout_time": null,
                 * }
                 * 
                 * 03
                 * false
                 */
                $totalWorkBreakup = $this->getUserWorkingHours($attendance_assign, $user);
            }

            if ($totalWorkBreakup != false && $totalWorkBreakup['totalTime'] != 0) {

                $user_regularization_data['break_duration'] = $this->convertSecondsInTime($totalWorkBreakup['breakTime']);
                $user_regularization_data['login_duration'] = $this->convertSecondsInTime($totalWorkBreakup['totalTime']);
                $user_regularization_data['work_duration']  = $this->convertSecondsInTime($totalWorkBreakup['workTime']);


                /**
                 * Response sample for - $this->getUserWorkDay()
                 * 01
                 * {
                 *   "user_work": "full_day",
                 *   "user_grace_period": {
                 *       "late_checkin": false,
                 *       "early_checkout": false
                 *   }
                 * }
                 * 
                 * 02
                 * {
                 *   "user_work": "half_day",
                 *   "user_grace_period": {
                 *       "late_checkin": false,
                 *       "early_checkout": true
                 *   }
                 * }
                 * 
                 * 03
                 * {
                 *   "user_work": "no_work_count",
                 *   "user_grace_period": {
                 *       "late_checkin": false,
                 *       "early_checkout": true
                 *   }
                 * }
                 * 
                 */

                $user_work_day = $this->getUserWorkDay($attendance_assign, $totalWorkBreakup, $user);

                $user_regularization_data['user_work']              = $user_work_day['user_work'] ?? null;
                $user_regularization_data['late_checkin']           = $user_work_day['user_grace_period']['late_checkin'] ?? null;
                $user_regularization_data['early_checkout']         = $user_work_day['user_grace_period']['early_checkout'] ?? null;

                $user_regularization_data['absent_value']           = 'unknown';
                $user_regularization_data['absent_reason']          = 'unknown';
                $user_regularization_data['leave_application_id']   = null;

                /**
                 * Check if the user worked for half day and 
                 * if so then check if he was on leave for the other half day or not
                 */
                if ($user_work_day['user_work'] == "half_day") {

                    /**
                     * Response sample for - $this->isUserHadAHalfDayOff()
                     * 01
                     * {
                     *     "leave_application_id": 3
                     *     "absent_reason": leave,
                     *     "absent_value": first_half_day,
                     * }
                     * 
                     * 02
                     * {
                     *     "leave_application_id": 24,
                     *     "absent_reason": leave,
                     *     "absent_value": second_half_day,
                     * }
                     * 
                     * 03
                     * false
                     */
                    $half_day_leave = $this->isUserHadAHalfDayOff($user['id']);

                    if ($half_day_leave != false) {
                        $user_regularization_data['absent_value']           = $half_day_leave['absent_value'] ?? null;
                        $user_regularization_data['absent_reason']          = $half_day_leave['absent_reason'] ?? null;
                        $user_regularization_data['leave_application_id']   = $half_day_leave['leave_application_id'] ?? null;

                        // return "user had a half day";
                    }
                }

                // return $user_work_day['user_work'];
            } else {

                /**
                 * Check if user was on leave or not
                 * 
                 * Response sample for - $this->getUserAbsentReason()
                 * 01
                 * {
                 *     "leave_application_id" => null,
                 *     "absent_reason": weekend,
                 *     "absent_value": saturday
                 * }
                 * 
                 * 02
                 * {
                 *     "leave_application_id" => null,
                 *     "absent_reason": weekend,
                 *     "absent_value": sunday
                 * }
                 * 
                 * 03
                 * {
                 *     "leave_application_id" => null,
                 *     "absent_reason": holiday,
                 *     "absent_value": new_year
                 * }
                 * 
                 * 04
                 * {
                 *     "leave_application_id" => 32,
                 *     "absent_reason": leave,
                 *     "absent_value": Sick Leave
                 * }
                 * 
                 * 05
                 * {
                 *     "leave_application_id" => null,
                 *     "absent_reason": unknown,
                 *     "absent_value": unknown
                 * }
                 * 
                 */
                $user_absent_reason = $this->getUserAbsentReason($user['id']);

                $user_regularization_data['absent_value']           = $user_absent_reason['absent_value'] ?? null;
                $user_regularization_data['absent_reason']          = $user_absent_reason['absent_reason'] ?? null;
                $user_regularization_data['leave_application_id']   = $user_absent_reason['leave_application_id'] ?? null;


                // return $user_absent_reason;
            }

            AttendanceReport::create($user_regularization_data);
        }
    }


    public function getAttendanceAssignedToUser($user_id)
    {

        $attendance_assign = AttendanceAssign::where('user_id', $user_id)->first();

        if (isset($attendance_assign) || $attendance_assign != null) {
            return $attendance_assign;
        }




        $user_shift = EmpShift::where('user_id', $user_id)->first();

        if (isset($user_shift) || $user_shift != null) {

            // return $user_shift['shift_id'];
            $attendance_assign = AttendanceAssign::where('shift_id', $user_shift['shift_id'])->first();

            if (isset($attendance_assign) || $attendance_assign != null) {
                return $attendance_assign;
            }
        }


        return false;
    }

    public function getUserWorkingHours($attendance_assign, $user)
    {

        $today = Carbon::today();

        $attendance_working_hour_id = $attendance_assign['attendance_working_hour_id'];
        // return $attendance_working_hour_id;

        $working_hours = AttendanceWork::find($attendance_working_hour_id);

        if (isset($working_hours) && $working_hours != null) {

            $breakTime          = 0;
            $worktimeFinal      = 0;
            // $user_login_time    = "";
            // $user_logout_time   = "";

            if ($working_hours['working_hours_calculation'] == 'sequential') {

                $timelogs = TimeLog::where('user_id', $user['id'])
                    ->whereDate('date', $today)
                    ->orderBy('time', 'asc')
                    ->get();


                if (sizeof($timelogs) > 0) {

                    $previousShiftEnd = null;

                    foreach ($timelogs as $timelog) {
                        if ($timelog->activity === 'shift start') {

                            if ($previousShiftEnd !== null) {
                                $breakTime += strtotime($timelog->time) - strtotime($previousShiftEnd);
                            }
                        } elseif ($timelog->activity === 'shift end') {

                            $previousShiftEnd = $timelog->time;
                        }
                    }


                    $firstTimelog = $timelogs[0];
                    $lastTimelog = $timelogs[sizeof($timelogs) - 1];

                    $firstTimeTimestamp = strtotime($firstTimelog->time);
                    $lastTimeTimestamp = strtotime($lastTimelog->time);

                    $timeDifference = $lastTimeTimestamp - $firstTimeTimestamp;

                    $worktimeFinal = $timeDifference - $breakTime;
                }
            } else if ($working_hours['working_hours_calculation'] == 'first_in_last_out') {

                $timelogs = TimeLog::where('user_id', $user['id'])
                    ->whereDate('date', $today)
                    ->orderBy('id', 'asc')
                    ->get();


                if (sizeof($timelogs) > 0) {


                    $firstTimelog = $timelogs[0];
                    $lastTimelog = $timelogs[sizeof($timelogs) - 1];

                    $firstTimeTimestamp = strtotime($firstTimelog->time);
                    $lastTimeTimestamp = strtotime($lastTimelog->time);

                    $timeDifference = $lastTimeTimestamp - $firstTimeTimestamp;

                    $worktimeFinal = $timeDifference - $breakTime;
                }
            }

            // return gmdate("H:i:s", $worktimeFinal);
            return array("user_login_time" => null, "user_logout_time" => null, "workTime" => $worktimeFinal, "breakTime" => $breakTime, "totalTime" => $worktimeFinal + $breakTime);
        }

        return false;
    }

    public function getUserWorkDay($attendance_assign, $totalWorkBreakup, $user)
    {

        $fullDayHours   = 0;
        $halfDayHours   = 0;
        $totalDayHours  = 0;
        $userWorkLog    = "";

        // $attendance_working_hour_id = $attendance_assign['attendance_working_hour_id'];

        $working_hours = AttendanceWork::find($attendance_assign['attendance_working_hour_id']);

        if (isset($working_hours) && $working_hours != null) {

            $fullDayHours   = $working_hours['full_day_hours'];
            $halfDayHours   = $working_hours['half_day_hours'];
            $totalDayHours  = $working_hours['total_hours'];

            if ($totalWorkBreakup['workTime'] >= $fullDayHours) {
                $userWorkLog = "full_day";
            }

            if ($totalWorkBreakup['workTime'] < $fullDayHours && $totalWorkBreakup['workTime'] >= $halfDayHours) {
                $userWorkLog = "half_day";
            }

            if ($totalWorkBreakup['workTime'] < $halfDayHours) {
                $userWorkLog = "no_work_count";
            }

            $user_grace_period = $this->getUserGracePeriodData($working_hours, $user);

            return array('user_work' => $userWorkLog, "user_grace_period" => $user_grace_period);
            // return $user_grace_period;
        }

        return false;
    }

    public function getUserGracePeriodData($working_hours, $user)
    {
        $today = Carbon::today()->format('Y-m-d');

        /**
         * Start - Check if user checkin within the Grace Period
         */
        $is_user_late_checkin   = false;

        $checkin_grace_period_time  = $working_hours['grace_for_checkin'];

        $user_shift = EmpShift::where('user_id', $user['id'])->first();
        $shift      = Shift::find($user_shift['shift_id']);

        $user_shift_starts_at   = $shift['shift_start'];

        $checkinlogs        = TimeLog::where('user_id', $user['id'])
            ->whereDate('date', $today)
            ->whereIn('activity', ["shift_start", "shift start"])
            ->orderBy('time', 'asc')
            ->get();

        if (sizeof($checkinlogs) <= 0) {
            return array(
                'late_checkin'      => null,
                'early_checkout'    => null,
            );
        }
        $user_checkin_at            = $checkinlogs[0]['time'];
        $checkinTimeStamp           = strtotime($user_checkin_at);
        $shiftStartTimeStamp        = strtotime($user_shift_starts_at);
        $shiftStartWithGracePeriod  = $shiftStartTimeStamp + ($checkin_grace_period_time * 60);

        if ($checkinTimeStamp > $shiftStartWithGracePeriod) {
            $is_user_late_checkin = true;
        }
        /**
         * End - Check if user checkin within the Grace Period
         */





        /**
         * Start - Check if user checkout within the Grace Period
         */
        $user_shift_ends_at         = $shift['shift_end'];
        $is_user_early_checkout     = false;
        $checkout_grace_period_time = $working_hours['grace_for_checkout'];

        $checkoutlogs       = TimeLog::where('user_id', $user['id'])
            ->whereDate('date', $today)
            ->whereIn('activity', ["shift_end", "shift end"])
            ->orderBy('time', 'desc')
            ->get();

        if (sizeof($checkoutlogs) <= 0) {
            return array(
                'late_checkin'      => null,
                'early_checkout'    => null,
            );
        }
        $user_checkout_at   = $checkoutlogs[0]['time'];

        $checkoutTimeStamp          = strtotime($user_checkout_at);
        $shiftEndTimeStamp          = strtotime($user_shift_ends_at);
        $shiftEndWithGracePeriod    = $shiftEndTimeStamp - ($checkout_grace_period_time * 60);

        if ($checkoutTimeStamp < $shiftEndWithGracePeriod) {
            $is_user_early_checkout = true;
        }
        /**
         * End - Check if user checkout within the Grace Period
         */





        /**
         * Start - Check if late checkin allowed and user checkin within the time
         * 
         * Use this condition only is the user checkin After the Grace Period - Late checkin
         */
        if ($is_user_late_checkin) {

            if ($working_hours['late_checkin_allowed'] == 1) {

                $date = Carbon::today();

                if ($working_hours['late_checkin_frequency'] == 'monthly') {
                    $date = Carbon::today()->firstOfMonth();
                }

                if ($working_hours['late_checkin_frequency'] == 'weekly') {
                    $date = Carbon::today()->firstOfWeek();
                }

                $attendance_logs_count = AttendanceLog::where('user_id', $user['id'])
                    ->where('date', '>=', $date)
                    ->where('activity', 'late_checkin')
                    ->count();

                if ($attendance_logs_count < $working_hours['late_checkin_count']) {


                    $checkinTimeStamp           = strtotime($user_checkin_at);
                    $shiftStartTimeStamp        = strtotime($user_shift_starts_at);
                    $shiftStartWithGracePeriod  = $shiftStartTimeStamp + ($working_hours['late_checkin_minutes'] * 60);

                    $shiftStartWithGracePeriodFormatted = (gmdate("H:i:s", $shiftStartWithGracePeriod));

                    if ($user_checkin_at <= $shiftStartWithGracePeriodFormatted) {
                        $is_user_late_checkin = false;
                    } else {

                        AttendanceLog::create([
                            'user_id'   =>  $user['id'],
                            'date'      =>  $today,
                            'time'      =>  $user_checkin_at,
                            'activity'  =>  'late_checkin'
                        ]);
                    }
                }
            }
        }
        /**
         * End - Check if late checkin allowed and user checkin within the time
         */







        /**
         * Start - Check if early checkout allowed and user checkout within the time
         * 
         * Use this condition only is the user checkout After the Grace Period - Early checkout
         */
        if ($is_user_early_checkout) {

            if ($working_hours['early_checkout_allowed'] == 1) {

                $date = Carbon::today();

                if ($working_hours['early_checkout_frequency'] == 'monthly') {
                    $date = Carbon::today()->firstOfMonth();
                }

                if ($working_hours['early_checkout_frequency'] == 'weekly') {
                    $date = Carbon::today()->firstOfWeek();
                }

                $attendance_logs_count = AttendanceLog::where('user_id', $user['id'])
                    ->where('date', '>=', $date)
                    ->where('activity', 'early_checkout')
                    ->count();

                if ($attendance_logs_count < $working_hours['late_checkin_count']) {

                    $checkoutTimeStamp          = strtotime($user_checkout_at);
                    $shiftEndTimeStamp          = strtotime($user_shift_ends_at);
                    $shiftEndWithGracePeriod    = $shiftEndTimeStamp + ($working_hours['early_checkout_minutes'] * 60);

                    $shiftEndWithGracePeriodFormatted = (gmdate("H:i:s", $shiftEndWithGracePeriod));

                    if ($user_checkout_at <= $shiftEndWithGracePeriodFormatted) {
                        $is_user_early_checkout = false;
                    } else {

                        AttendanceLog::create([
                            'user_id'   =>  $user['id'],
                            'date'      =>  $today,
                            'time'      =>  $user_checkin_at,
                            'activity'  =>  'early_checkout'
                        ]);
                    }
                }
            }
        }
        /**
         * End - Check if early checkout allowed and user checkout within the time
         */


        return array(
            'late_checkin'      => $is_user_late_checkin,
            'early_checkout'    => $is_user_early_checkout,
        );
    }

    public function isUserHadAHalfDayOff($user_id)
    {

        $today          = Carbon::now();

        /**
         * Check if the User was on Leave or Not
         */
        $user_leave = LeaveApplication::with('leaveType')
            ->where('user_id', $user_id)
            ->where('leave_from', '<', $today->format('Y-m-d'))
            ->OrWhere('leave_to', '>', $today->format('Y-m-d'))
            ->where('leave_status', 'approved')
            ->first();

        if (isset($user_leave) && $user_leave != null) {

            if ($user_leave['leave_value_start'] == "first_half_day" || $user_leave['leave_value_start'] == "second_half_day" || $user_leave['leave_value_end'] == "first_half_day" || $user_leave['leave_value_end'] == "second_half_day") {
                return array("leave_application_id" => $user_leave['id'] ?? null, "absent_reason" => "leave", "absent_value" => $user_leave['leave_value_start'] ?? $user_leave['leave_value_end']);
            }
        }

        return false;
    }

    public function getUserAbsentReason($user_id)
    {
        $today          = Carbon::now();

        /**
         * Check if it was a weekend
         */
        $weekday_name = $today->format("l");
        if (strtolower($weekday_name) == "saturday" || strtolower($weekday_name) == "sunday") {
            return array("leave_application_id" => null, "absent_reason" => 'weekend', "absent_value" => $weekday_name);
        }

        /**
         * Check if it was a Holiday or Not
         */
        $holiday = Holiday::where('date_from', $today->format('Y-m-d'))
            ->first();

        if (isset($holiday) && $holiday != null) {
            return array("leave_application_id" => null, "absent_reason" => 'holiday', "absent_value" => $holiday['name']);
        }

        /**
         * Check if the User was on Leave or Not
         */
        $user_leave = LeaveApplication::with('leaveType')
            ->where('user_id', $user_id)
            ->where('leave_to', '>=', $today->format('Y-m-d'))
            ->where('leave_from', '<=', $today->format('Y-m-d'))
            ->where('leave_status', 'approved')
            ->first();

        if (isset($user_leave) && $user_leave != null) {
            return array("leave_application_id" => $user_leave['id'] ?? null, "absent_reason" => 'leave', "absent_value" => $user_leave['leaveType']['name']);
        }

        return array("leave_application_id" => null, "absent_reason" => 'unknown', "absent_value" => 'unknown');
    }

    public function convertSecondsInTime($seconds)
    {

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        // Format the time
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        return $timeFormat;
    }
}
