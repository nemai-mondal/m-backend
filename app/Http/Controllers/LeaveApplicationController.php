<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveApplicationRequest;
use App\Http\Resources\LeaveApplicationCollection;
use App\Http\Resources\LeaveApplicationResource;
use App\Mail\LeaveUpdateMail;
use App\Models\Department;
use App\Models\EmpDepartment;
use App\Models\EmpEmploymentType;
use App\Models\EmploymentType;
use App\Models\EmpProfessionalDetail;
use App\Models\LeaveApplication;
use App\Models\LeaveRatio;
use App\Models\LeaveReview;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeaveApplicationController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/leave-application/list",
     *     tags={"Leave Application"},
     *     summary="LeaveApplication list.",
     *     operationId="listLeaveApplication",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Leave Application list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Leave Application list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the user after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Leave Application.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Leave Applications list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=5),
     *                      @OA\Property(property="remarks", type="string", example="Going to attend wedding."),
     *                      @OA\Property(property="leave_to", type="date", example="2024-01-25"),
     *                      @OA\Property(property="attachment", type="date", example="null"),
     *                      @OA\Property(property="leave_from", type="date", example="2024-01-23"),
     *                      @OA\Property(property="total_days", type="date", example="3"),
     *                      @OA\Property(property="leave_status", type="date", example="Pending"),
     *                      @OA\Property(property="leave_type_id", type="date", example="1"),
     *                      @OA\Property(property="leave_value_end", type="date", example="Full Day"),
     *                      @OA\Property(property="leave_value_start", type="date", example="Full Day"),
     *                      @OA\Property(property="email_notification_to", type="string", example=""),
     *                      @OA\Property(property="applied_at", type="string", example="2024-01-03 16:27:55"),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $leave_applications = LeaveApplication::where('leave_status', 'pending')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Leave applications fetched.',
                'data'      =>  new LeaveApplicationCollection($leave_applications)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\POST(
     *     path="/v1/leave-application/create",
     *     tags={"Leave Application"},
     *     summary="Create New Leave Application.",
     *     operationId="createLeaveType",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Apply for a Leave Application.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"leave_type_id", "leave_value_start", "leave_value_end", "dates"},
     *                 @OA\Property(
     *                     property="leave_type_id",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_value_start",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_value_end",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="remarks",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="attachments",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="email_notificaiton_to",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                   property="dates",
     *                   type="array",
     *                   @OA\Items(
     *                       type="string",
     *                       format="date",
     *                       description="Array of dates"
     *                      )
     *                  ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Leave Applied successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user when the leave applied successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Leave Application created successfully."),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dates          = $request->dates ?? [];
        $leave_type_id  = $request->leave_type_id ?? 0;

        if (!is_array($dates)) {
            return response()->json([
                'status'            =>  false,
                'message'           =>  'Invalid date range.',
            ], 400);
        }

        if (sizeof($dates) <= 0 || $dates[0] == "") {
            return response()->json([
                'status'            =>  false,
                'message'           =>  'Invalid date range.',
            ], 400);
        }

        $leave_days  = sizeof($dates);
        $leave_end   = $request->till_date;
        $leave_start = $request->from_date;

        $leave_request = new Request([
            'leave_to'              =>  $leave_end,
            'leave_type_id'         =>  $leave_type_id,
        ]);
        $balanceLeaves = $this->availableLeaveBalance($leave_request);

        if ($balanceLeaves == false) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'You do not have enough leave balance.'
            ], 400);
        }

        $total_used_leaves = $this->getTotalUsedLeaves($request->leave_type_id);

        if ($total_used_leaves >= $balanceLeaves) {
            return response()->json([
                'status'                =>  false,
                'message'               =>  'You do not have enough leave balance.'
            ], 400);
        }

        $leaveAppliedInDays = $this->totalLeaveAppliedInDays($request);

        if($balanceLeaves < ($leaveAppliedInDays + $total_used_leaves)) {
            return response()->json([
                'status'                =>  false,
                'message'               =>  'You do not have enough leave balance.'
            ], 400);
        }

        $leaveApply =   LeaveApplication::create([
            'user_id'                   =>  auth()->user()->id,
            'remarks'                   =>  $request->remarks,
            'leave_to'                  =>  $leave_end,
            'total_days'                =>  $leaveAppliedInDays,
            'attachment'                =>  $request->attachment,
            'leave_from'                =>  $leave_start,
            'leave_status'              =>  'pending',
            'leave_type_id'             =>  $request->leave_type_id,
            'leave_value_end'           =>  $request->leave_value_end,
            'leave_value_start'         =>  $request->leave_value_start,
            'email_notification_to'     =>  $request->email_notification_to,
        ]);

        if ($leaveApply) {


            if ($request->hasFile("file")) {
                $leaveApply->clearMediaCollection('leave-prescription');

                $leaveApply->addMediaFromRequest('file')
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('leave-prescription');
            }


            return response()->json([
                'status'    =>  true,
                'message'   =>  'Leave applied successfully.',
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @OA\POST(
     *     path="/v1/leave-application/balance",
     *     tags={"Leave Application"},
     *     summary="Find LeaveBalance Details",
     *     operationId="showLeaveBalance",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Check your Leave Balance.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={},
     *                 @OA\Property(
     *                     property="id",
     *                     type="Integer"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicating that the LeaveBalance found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the LeaveBalance.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of LeaveBalance.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="LeaveBalance Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Casual Leave"),
     *                      @OA\Property(property="abbreviation", type="string", example="CL"),
     *                      @OA\Property(property="comment", type="string", example="Only for confirmed employees."),
     *                  ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Bad Request"
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaveBalance(Request $request)
    {
        $id = $request->id ? (int)$request->id : auth()->user()->id;

        $leaves             = [];

        $empEmploymentType  = EmpEmploymentType::with('employmentType')
            ->where("user_id", $id)
            ->first();

        if (!isset($empEmploymentType) || $empEmploymentType == null) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Please set the Employment type from Joining Details in your Profile settings.',
            ], 404);
        }

        $employment_type_id = $empEmploymentType->employmentType->id;

        if (!isset($employment_type_id) || $employment_type_id == null) {
            if ($request->action == 'validateApplication') {
                return false;
            }
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Invalid employment type.',
            ], 404);
        }

        $leaveRatios        = LeaveRatio::where('employment_type_id', $employment_type_id)->get();
        if (!isset($leaveRatios) || $leaveRatios == null || sizeof($leaveRatios) <= 0) {
            if ($request->action == 'validateApplication') {
                return false;
            }
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Leave not found.',
            ], 404);
        }


        $calculateMonthDifference = new Request([
            'created_at'        =>  $empEmploymentType['created_at'],
            'current_date'      =>  Carbon::now(),
            'leave_to'          =>  $request->leave_to ?? null,
            'employment_type'   =>  $empEmploymentType->employmentType,
            'user_id'           =>  $id,
        ]);

        $month_difference   = $this->calculateMonthDifference($calculateMonthDifference);

        if ($empEmploymentType->employmentType->duration != 0) {

            $i = 0;
            foreach ($leaveRatios as $leave) {

                $leaveType                  = LeaveType::where('id', $leave['leave_type_id'])->first();

                $previous_leaves_approved   = LeaveApplication::where('user_id', $id)
                    ->where('leave_status', 'approved')
                    ->where('leave_type_id', $leave['leave_type_id'])
                    ->sum('total_days');

                $previous_leaves_pending    = LeaveApplication::where('user_id', $id)
                    ->where('leave_status', 'pending')
                    ->where('leave_type_id', $leave['leave_type_id'])
                    ->sum('total_days');

                $total_leave_used           = $previous_leaves_approved + $previous_leaves_pending;
                $total_leave_available      = ($leave['leave_credit'] * $month_difference) - $total_leave_used;


                $leaves[$i]['id']           = $leaveType['id'];
                $leaves[$i]['balance']      = $month_difference * $leave['leave_credit'];
                $leaves[$i]['leave_name']   = $leaveType['name'];
                $leaves[$i]['abbreviation'] = $leaveType['abbreviation'];
                $leaves[$i]['comment']      = $leaveType['comment'];
                $leaves[$i]['used']         = $total_leave_used;
                $leaves[$i]['available']    = $total_leave_available;
                $leaves[$i]['leave_credit'] = $leave['leave_credit'];

                $i++;
            }
        } else {

            $startOfYear           = Carbon::now()->startOfYear();

            $i = 0;
            foreach ($leaveRatios as $leave) {

                $leaveType                  = LeaveType::where('id', $leave['leave_type_id'])->first();

                $previous_leaves_approved   = LeaveApplication::where('user_id', $id)
                    ->where('leave_status', 'approved')
                    ->where('leave_type_id', $leave['leave_type_id'])
                    ->where('leave_from', '>=', $startOfYear)
                    ->sum('total_days');

                $previous_leaves_pending    = LeaveApplication::where('user_id', $id)
                    ->where('leave_status', 'pending')
                    ->where('leave_type_id', $leave['leave_type_id'])
                    ->where('leave_from', '>=', $startOfYear)
                    ->sum('total_days');

                $total_leave_used           = $previous_leaves_approved + $previous_leaves_pending;
                $total_leave_available      = ($leave['leave_credit'] * $month_difference) - $total_leave_used;


                $leaveType                  = LeaveType::where('id', $leave['leave_type_id'])->first();
                $leaves[$i]['id']           = $leaveType['id'];
                $leaves[$i]['balance']      = $leave['leave_credit'] * $month_difference;
                $leaves[$i]['leave_name']   = $leaveType['name'];
                $leaves[$i]['abbreviation'] = $leaveType['abbreviation'];
                $leaves[$i]['comment']      = $leaveType['comment'];
                $leaves[$i]['used']         = $total_leave_used;
                $leaves[$i]['available']    = $total_leave_available;
                $leaves[$i]['leave_credit'] = $leave['leave_credit'];

                $i++;
            }
        }

        if (isset($request->action) && $request->action == 'validateApplication') {

            foreach ($leaves as $leave) {

                if (($leave['id'] == $request->leave_type_id)) {
                    return $leave['balance'];
                }
            }

            return false;
        }

        return response()->json([
            'status'    =>  true,
            'message'   =>  'Balanced Leave Found.',
            'data'      =>  $leaves,
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/v1/leave-application/review",
     *     tags={"Leave Application"},
     *     summary="Review Leave Application.",
     *     operationId="reviewLeaveType",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Review Leave Application Of Employees.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"application_id", "action"},
     *                 @OA\Property(
     *                     property="application_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="action",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="remarks",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="email_ids",
     *                     type="Object"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Leave updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user when the leave status updated successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Leave Application status updated successfully."),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not Found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function review(LeaveApplicationRequest $request)
    {

        try {

            $logged_user_id = auth()->user()->id;
            $application    = LeaveApplication::find($request->application_id);

            if ($logged_user_id == $application->user_id) {
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'You do not have permission to Approve or Reject this leave request.'
                ], 400);
            }

            $user = User::find($application->user_id);

            LeaveReview::updateOrCreate(
                [
                    'application_id'    =>  $request->application_id,
                    'user_id'           =>  $logged_user_id,
                ],
                [
                    'remarks'           =>  $request->remarks ?? "",
                ]
            );

            LeaveApplication::find($request->application_id)->update([
                'leave_status'  =>  strtolower($request->action),
            ]);

            $leave_type = LeaveType::find($application->leave_type_id);

            $leaveData = [
                'user'      => [
                    'honorific'         =>  $user->honorific,
                    'first_name'        =>  $user->first_name,
                    'last_name'         =>  $user->last_name,
                ],
                'type'      =>  $leave_type['name'],
                'action'    =>  ucfirst(strtolower($request->action)),
                'days'      =>  $application->total_days,
                'from'      =>  $application->leave_from,
                'to'        =>  $application->leave_to,
                'remarks'   =>  $request->remarks ?? "N/A",
            ];

            Mail::to($user->email)->cc($request->email_ids)->send(new LeaveUpdateMail($leaveData));


            return response()->json([
                'status'    =>  true,
                'message'   =>  'Leave application updated successfully.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function calculateMonthDifference($request_data)
    {

        $user_id                = $request_data->user_id;
        $employment_type        = $request_data->employment_type;
        $difference             = 1;

        $empEmploymentType      = EmpEmploymentType::where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->first();


        $empConfirmedDate       = $empEmploymentType['created_at'];
        $empConfirmedYear       = $empConfirmedDate->format('Y');
        $empConfirmedMonth      = $empConfirmedDate->format('m');


        $currentDate            = Carbon::now();
        $currentDateYear        = $currentDate->format('Y');
        $currentDateMonth       = $currentDate->format('m');


        $employmentType         = EmploymentType::find($empEmploymentType['employment_type_id']);

        /**
         * If duration is not equals to 0 then
         * The candidate will avail the leaves despite of the reset of the calendar year
         */
        if ($employmentType['duration'] != 0) {

            if ($empConfirmedYear < $currentDateYear) {

                $month = $empConfirmedMonth;

                while ($month != 12) {

                    $difference++;
                    $month++;
                }

                $month = 0;

                while ($month != $currentDateMonth) {

                    $difference++;
                    $month++;
                }
            } else if ($empConfirmedMonth < $currentDateMonth) {

                $month = $empConfirmedMonth;

                while ($month != $currentDateMonth) {

                    $difference++;
                    $month++;
                }
            } else if ($empConfirmedMonth == $currentDateMonth) {

                $difference = 1;
            }
        }

        /**
         * If duration is equals to 0 then
         * The candidate's leaves will be reset to 0 after each calendar year
         * Carry Forward leaves are not being implemented yet.
         */
        else if ($employmentType['duration'] == 0) {

            if ($empConfirmedMonth < $currentDateMonth) {

                $month = 0;

                while ($month != $currentDateMonth) {

                    $difference++;
                    $month++;
                }
            } else if ($empConfirmedMonth == $currentDateMonth) {

                $difference = 1;
            }
        }


        return $difference;










        $currentYear            = $request_data->current_date->format('Y');
        $currentMonth           = $request_data->current_date->format('m');
        $empConfirmedDate       = (new DateTime($request_data->emp_confirmation_date));
        $difference             = 0;





        $empEmploymentCreatedDate =  Carbon::create($empEmploymentType->created_at);

        $empEmploymentCreatedDate = Carbon::create(2023, 11, 25, 12, 9, 19, 'UTC');
        $currentDate = Carbon::create(2024, 1, 18, 12, 9, 19, 'UTC');

        $monthDifference = $currentDate->diffInMonths($empEmploymentCreatedDate) -
            ($currentDate->day < $empEmploymentCreatedDate->day ? 1 : 0);



        dd($monthDifference);


        if ($request_data->leave_to == null) {

            if ($employment_type['duration'] != 0) {
            }

            if ($currentYear > $empConfirmedYear) {

                return $currentMonth;
            } else {

                for ($i = $empConfirmedMonth; $i <= $currentMonth; $i++) {
                    $difference++;
                }
                return $difference;
            }
        }


        $leave_to_date  = new Carbon($request_data->leave_to);
        $leaveToYear    = $leave_to_date->format('Y');
        $leaveToMonth   = $leave_to_date->format('m');

        if ($leaveToYear > $empConfirmedYear) {
            return $leaveToMonth;
        }

        for ($i = $empConfirmedMonth; $i <= $leaveToMonth; $i++) {
            $difference++;
        }

        return $difference;
    }

    public function calculateLeaveAppliedInDays(Request $request)
    {
        $dates              = $request->dates ?? [];
        $total_leave_days   = 0;

        $total_used_leaves  = $this->getTotalUsedLeaves($request->leave_type_id);

        $total_leave_balance_available = $request->balance_leave - $total_used_leaves;

        if (sizeof($dates) == 1) {

            $total_leave_days    = 0;

            if ($request->leave_value_start == 'full_day' || $request->leave_value_end == 'full_day') {
                $total_leave_days = 1;
            } else if ($request->leave_value_start == $request->leave_value_end && $request->leave_value_end != "full_day") {
                $total_leave_days = 0.5;
            } else {
                $total_leave_days = 1;
            }
        } else if (sizeof($dates) == 2) {

            $leave_day_end      = 0;
            $leave_day_start    = 0;

            if ($request->leave_value_start == 'full_day') {
                $leave_day_start = 1;
            } else {
                $leave_day_start = 0.5;
            }

            if ($request->leave_value_end == 'full_day') {
                $leave_day_end = 1;
            } else {
                $leave_day_end = 0.5;
            }

            $total_leave_days = $leave_day_end + $leave_day_start + (sizeof($dates) - 2);
        } else if (sizeof($dates) > 2) {

            $leave_day_end      = 0;
            $leave_day_start    = 0;

            if ($request->leave_value_start == 'full_day') {
                $leave_day_start = 1;
            } else {
                $leave_day_start = 0.5;
            }

            if ($request->leave_value_end == 'full_day') {
                $leave_day_end = 1;
            } else {
                $leave_day_end = 0.5;
            }

            $total_leave_days = $leave_day_end + $leave_day_start + (sizeof($dates) - 2);
        }
        // } else if (sizeof($dates) == 3) {

        //     if ($request->leave_value_start == 'full_day') {
        //         $first_day_count = 1;
        //     } else {
        //         $first_day_count = 0.5;
        //     }

        //     if ($request->leave_value_end == 'full_day') {
        //         $last_day_count = 1;
        //     } else {
        //         $last_day_count = 0.5;
        //     }

        //     $total_leave_days = $last_day_count + $first_day_count;

        // } else if (sizeof($dates) >= 4) {

        //     if ($request->leave_value_start == 'full_day') {
        //         $first_day_count = 1;
        //     } else {
        //         $first_day_count = 0.5;
        //     }

        //     if ($request->leave_value_end == 'full_day') {
        //         $last_day_count = 1;
        //     } else {
        //         $last_day_count = 0.5;
        //     }

        //     $remaining_days     = sizeof($dates) - 1;

        //     $total_leave_days   = $last_day_count + $first_day_count + $remaining_days;
        // }

        if ($total_leave_days <= $total_leave_balance_available) {
            return $total_leave_days;
        }

        return false;
    }

    /**
     * @OA\GET(
     *     path="/v1/leave-application/search",
     *     tags={"Leave Application"},
     *     summary="Leave Application search.",
     *     operationId="searchLeaveApplication",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Search Leave Application.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="employee_name",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="order_by",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="order_type",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_from",
     *                     type="Date"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_to",
     *                     type="Date"
     *                 ),
     *                 @OA\Property(
     *                     property="leave_status",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="employee_ids",
     *                     type="Object"
     *                 ),
     *                 @OA\Property(
     *                     property="department_ids",
     *                     type="Object"
     *                 ),
     *                 @OA\Property(
     *                     property="total_days",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="current_page",
     *                     type="Integer"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leave Application list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Leave Application list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the user after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Leave Application.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Leave Application list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="string", example=3),
     *                      @OA\Property(property="remarks", type="string", example="I need to attend my friend's wedding."),
     *                      @OA\Property(property="leave_to", type="Date", example="2024-01-05"),
     *                      @OA\Property(property="attachment", type="string", example=""),
     *                      @OA\Property(property="leave_from", type="Date", example="2024-01-02"),
     *                      @OA\Property(property="total_days", type="Integer", example="4"),
     *                      @OA\Property(property="leave_status", type="string", example="approved"),
     *                      @OA\Property(property="leave_type_id", type="Integer", example="4"),
     *                      @OA\Property(property="leave_value_end", type="String", example="full_day"),
     *                      @OA\Property(property="leave_value_start", type="String", example="full_day"),
     *                      @OA\Property(property="email_notification_to", type="String", example=""),
     *                      @OA\Property(property="create_at", type="datetime", example="2024-01-05 12:59:29"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-05 12:59:29"),
     *                      @OA\Property(property="user", type="object", example="User details object"),
     *                      @OA\Property(property="leave_type", type="object", example="Leave type object"),
     *                      @OA\Property(property="designation", type="object", example="Designation object"),
     *                      @OA\Property(property="members_on_leave", type="object", example="List of users who are also on leave within this date range."),
     *                      @OA\Property(property="action_taken_by", type="object", example="Admin MMT."),
     *                      @OA\Property(property="action_taken_at", type="datetime", example="2024-02-05T09:30:37.000000Z"),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid leave application Id.",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Invalid leave application Id."
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthenticated"
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Not found"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\MediaType(
     *             mediaType="string",
     *             example="Unprocessable Entity"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Server Error"
     *         ),
     *     )
     * )
     * 
     * 
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $user_id        = $request->user_id ?? "";
        $order_by       = $request->order_by ?? "created_at";
        $leave_to       = $request->end_date ?? "";
        $leave_from     = $request->start_date ?? "";
        $total_days     = $request->total_days ?? "";
        $order_type     = $request->order_type ?? "desc";
        $leave_status   = $request->leave_status ?? "";
        $employee_ids   = $request->employee_ids ?? [];
        $employee_name  = $request->employee_name ?? "";
        $department_ids = $request->department_ids ?? [];

        $perPage        = $request->per_page != "" ? (int)$request->per_page : 10;
        $currentPage    = $request->current_page != "" ? (int)$request->current_page : 1;

        $name_user_ids          = [];
        $unique_user_ids        = [];
        $department_user_ids    = [];

        try {

            // return new LeaveApplicationCollection(LeaveApplication::with('actionTakenBy')->get());

            $applications = LeaveApplication::with('userDetails');

            if ($leave_status != "") {
                $applications = $applications->where('leave_status', strtolower($leave_status));
            }

            if ($leave_from != "") {
                $applications = $applications->where('leave_from', '>=', $leave_from);
            }

            if ($leave_to != "") {
                $applications = $applications->where('leave_to', '<=', $leave_to);
            }

            if ($total_days != "") {
                $applications = $applications->where('total_days', '>=', $total_days);
            }

            if ($employee_name != "") {
                $name_user_ids = User::select('id')
                    ->where('first_name', 'like', '%' . $employee_name . '%')
                    ->orWhere('middle_name', 'like', '%' . $employee_name . '%')
                    ->orWhere('last_name', 'like', '%' . $employee_name . '%')
                    ->pluck('id')
                    ->toArray();

                $applications = $applications->whereIn('user_id', $name_user_ids);
            }

            if ($user_id != "") {
                $applications = $applications->where('user_id', $user_id);
            }

            if (sizeof($employee_ids) > 0) {
                $applications = $applications->whereIn('user_id', $employee_ids);
            }

            if (sizeof($department_ids) > 0) {
                $department_user_ids = EmpDepartment::select('user_id')
                    ->whereIn('department_id', $department_ids)
                    ->pluck('user_id')
                    ->toArray();

                if ($employee_name != "") {

                    $unique_user_ids = array_intersect($name_user_ids, $department_user_ids);
                    $applications = $applications->whereIn('user_id', $unique_user_ids);
                } else {

                    $applications = $applications->whereIn('user_id', $department_user_ids);
                }
            }

            if (isset($order_by)) {

                $applications = $applications->orderBy($order_by, $order_type);
            }

            $applications = $applications->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();


            return new LeaveApplicationCollection($applications);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function dashboard(Request $request)
    {
        $days           = $request->days ?? "";
        $leave_status   = $request->leave_status ?? "";

        if ($days > 0) {
            $days--;
        } else {
            $days = 0;
        }

        try {

            $startDate      = Carbon::now();

            // $applications   = LeaveApplication::with('userDetails', 'leaveType')
            // ->where('leave_status', $leave_status);

            $applications = LeaveApplication::with('userDetails', 'userDetails.designation', 'leaveType')
                ->where('leave_status', $leave_status);

            if (isset($days) && $days > 0) {

                $applications = $applications->where(function ($query) use ($days) {
                    $endDate = Carbon::now()->addDays($days)->toDateString();
                    $query->where('leave_from', '>=', Carbon::now()->toDateString())
                        ->where('leave_from', '<=', $endDate)
                        ->orWhere('leave_to', '>=', Carbon::now()->toDateString())
                        ->where('leave_to', '<=', $endDate)
                        ->orWhere(function ($query) use ($endDate) {
                            $query->where('leave_from', '<=', Carbon::now()->toDateString())
                                ->where('leave_to', '>=', $endDate);
                        });
                });
            }

            $applications = $applications->get();

            $data = [];

            foreach ($applications as $application) {

                $startDate  = Carbon::parse($application->leave_from);
                $endDate    = Carbon::parse($application->leave_to);
                $dateRange  = Carbon::parse($startDate)->toPeriod($endDate);

                foreach ($dateRange as $date) {
                    $dateKey = $date->format('d-m-Y');

                    if (!isset($data[$dateKey])) {
                        $data[$dateKey] = [];
                    }

                    $data[$dateKey][] = [
                        'id'                    => $application->id,
                        'user_id'               => $application->user_id,
                        'leave_type_id'         => $application->leave_type_id,
                        'leave_from'            => $application->leave_from,
                        'leave_to'              => $application->leave_to,
                        'leave_value_start'     => $application->leave_value_start,
                        'leave_value_end'       => $application->leave_value_end,
                        'total_days'            => $application->total_days,
                        'leave_status'          => $application->leave_status,
                        'remarks'               => $application->remarks,
                        'attachment'            => $application->attachment,
                        'email_notification_to' => $application->email_notification_to,
                        'user_details'          => $application->userDetails,
                        'leave_type'            => $application->leaveType,
                    ];
                }
            }

            ksort($data);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Leave applications fetched.',
                'data'      =>  $data
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function getTotalUsedLeaves($leave_type_id)
    {

        $empEmploymentType = EmpEmploymentType::with('employmentType')
            ->where('user_id', auth()->user()->id)
            ->first();

        $startOfYear = Carbon::now()->startOfYear();

        if ($empEmploymentType['duration'] == 0) {

            $previous_leaves_approved   = LeaveApplication::where('user_id', auth()->user()->id)
                ->where('leave_status', 'approved')
                ->where('leave_type_id', $leave_type_id)
                ->where('leave_from', '>=', $empEmploymentType['created_at'])
                ->sum('total_days');

            $previous_leaves_pending    = LeaveApplication::where('user_id', auth()->user()->id)
                ->where('leave_status', 'pending')
                ->where('leave_type_id', $leave_type_id)
                ->where('leave_from', '>=', $empEmploymentType['created_at'])
                ->sum('total_days');
        } else {

            $previous_leaves_approved   = LeaveApplication::where('user_id', auth()->user()->id)
                ->where('leave_status', 'approved')
                ->where('leave_type_id', $leave_type_id)
                ->where('leave_from', '>=', $startOfYear)
                ->sum('total_days');

            $previous_leaves_pending    = LeaveApplication::where('user_id', auth()->user()->id)
                ->where('leave_status', 'pending')
                ->where('leave_type_id', $leave_type_id)
                ->where('leave_from', '>=', $startOfYear)
                ->sum('total_days');
        }

        return $previous_leaves_approved + $previous_leaves_pending;
    }

    public function availableLeaveBalance($request)
    {
        $id = auth()->user()->id;

        $empEmploymentType  = EmpEmploymentType::with('employmentType')
            ->where("user_id", $id)
            ->first();

        $employment_type_id = $empEmploymentType->employmentType->id;

        if (!isset($employment_type_id) || $employment_type_id == null) {
            return false;
        }

        $leaveRatio = LeaveRatio::where('employment_type_id', $employment_type_id)
            ->where('leave_type_id', $request->leave_type_id)
            ->first();

        if (!isset($leaveRatio) || $leaveRatio == null) {
            return false;
        }

        $calculateMonthDifference = new Request([
            'leave_to'          =>  $request->leave_to ?? null,
        ]);

        $month_difference   = $this->calculateFutureMonthDifference($calculateMonthDifference);

        return $month_difference * $leaveRatio['leave_credit'];
    }

    public function calculateFutureMonthDifference($request)
    {

        $user_id                = auth()->user()->id;
        $difference             = 0;
        $leave_end              = $request->leave_to;

        $empEmploymentType      = EmpEmploymentType::where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->first();


        $empConfirmedDate       = $empEmploymentType['created_at'];
        $empConfirmedYear       = $empConfirmedDate->format('Y');
        $empConfirmedMonth      = $empConfirmedDate->format('m');

        /**
         * Current date is Leave end date
         */
        $currentDate            = Carbon::parse($leave_end);
        $currentDateYear        = $currentDate->format('Y');
        $currentDateMonth       = $currentDate->format('m');

        // dd($currentDate);
        $employmentType         = EmploymentType::find($empEmploymentType['employment_type_id']);

        /**
         * If duration is not equals to 0 then
         * The candidate will avail the leaves despite of the reset of the calendar year
         */
        if ($employmentType['duration'] != 0) {
            if ($empConfirmedYear < $currentDateYear) {
                $difference = (12 - $empConfirmedMonth) + $currentDateMonth + 1;
            } else if ($empConfirmedMonth <= $currentDateMonth) {
                $difference = ($currentDateMonth - $empConfirmedMonth) + 1;
            }
        }

        /**
         * If duration is equals to 0 then
         * The candidate's leaves will be reset to 0 after each calendar year
         * Carry Forward leaves are not being implemented yet.
         */
        else if ($employmentType['duration'] == 0) {

            if ($empConfirmedYear < $currentDateYear) {

                $difference = $currentDateMonth;
            } else if ($empConfirmedMonth <= $currentDateMonth) {

                $difference = ($currentDateMonth - $empConfirmedMonth) + 1;
            }
        }

        return (int)$difference;
    }

    public function totalLeaveAppliedInDays(Request $request)
    {

        $dates              = $request->dates ?? [];
        $total_leave_days   = 0;

        if (sizeof($dates) == 1) {
            if ($request->leave_value_start == 'full_day' || $request->leave_value_end == 'full_day') {
                $total_leave_days = 1;
            } else if ($request->leave_value_start == $request->leave_value_end && $request->leave_value_end != "full_day") {
                $total_leave_days = 0.5;
            } else {
                $total_leave_days = 1;
            }
        } else if (sizeof($dates) == 2) {

            $leave_day_end      = 0;
            $leave_day_start    = 0;

            if ($request->leave_value_start == 'full_day') {
                $leave_day_start = 1;
            } else if ($request->leave_value_start == 'first_half_day') {
                $leave_day_start = 1;
            } else {
                $leave_day_start = 0.5;
            }

            if ($request->leave_value_end == 'full_day') {
                $leave_day_end = 1;
            } else if ($request->leave_value_end == 'second_half_day') {
                $leave_day_end = 1;
            } else {
                $leave_day_end = 0.5;
            }

            $total_leave_days = $leave_day_end + $leave_day_start + (sizeof($dates) - 2);
        } else if (sizeof($dates) > 2) {

            $leave_day_end      = 0;
            $leave_day_start    = 0;

            if ($request->leave_value_start == 'full_day') {
                $leave_day_start = 1;
            } else if ($request->leave_value_start == 'first_half_day') {
                $leave_day_start = 1;
            } else {
                $leave_day_start = 0.5;
            }

            if ($request->leave_value_end == 'full_day') {
                $leave_day_end = 1;
            } else if ($request->leave_value_end == 'second_half_day') {
                $leave_day_end = 1;
            } else {
                $leave_day_end = 0.5;
            }

            $total_leave_days = $leave_day_end + $leave_day_start + (sizeof($dates) - 2);
        }

        return $total_leave_days;
    }
}
