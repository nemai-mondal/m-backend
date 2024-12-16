<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceCollection;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\AttendanceAssign;
use App\Models\AttendanceLog;
use App\Models\AttendanceRegularize;
use App\Models\AttendanceReport;
use App\Models\AttendanceWork;
use App\Models\EmpShift;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\Shift;
use App\Models\TimeLog;
use App\Models\User;
use App\Models\Worklog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/attendance/list",
     *     tags={"Attendance"},
     *     summary="Attendance list.",
     *     operationId="listAttendance",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Attendance list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Attendance list fetched successfully.",
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
     *             description="Contains the object of Attendance.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Attendance list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="ar_date", type="date", example="2024-01-22"),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="day_type", type="string", example="working day"),
     *                      @OA\Property(property="shift_id", type="integer", example=1),
     *                      @OA\Property(property="work_time", type="string", example="half day"),
     *                      @OA\Property(property="login_remarks", type="string", example="late because of rain"),
     *                      @OA\Property(property="logout_remarks", type="string", example="transportation problem"),
     *                      @OA\Property(property="is_regularized", type="boolean", example=1),
     *                      @OA\Property(property="regularized_by", type="boolean", example=1),
     *                      @OA\Property(property="actual_shift_end", type="time", example="19:00:00"),
     *                      @OA\Property(property="total_login_hours", type="time", example="08:00:00"),
     *                      @OA\Property(property="actual_shift_start", type="time", example="08:00:00"),
     *                      @OA\Property(property="total_working_hours", type="time", example="08:00:00"),
     *                      @OA\Property(property="leave_application_id", type="integer", example=1),
     *                      @OA\Property(property="regularization_remarks", type="string", example="test"),
     *                      @OA\Property(property="created_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
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

            $attendance = Attendance::orderBy('created_at', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Attendance list.',
                'data'      =>  new AttendanceCollection($attendance),
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\GET(
     *     path="/v1/attendance/show/{id}",
     *     tags={"Attendance"},
     *     summary="Find Attendance Details",
     *     operationId="showAttendance",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Attendance ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicating that the Attendance found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Attendance.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Attendance.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Attendance Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="ar_date", type="date", example="2024-01-22"),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="day_type", type="string", example="working day"),
     *                      @OA\Property(property="shift_id", type="integer", example=1),
     *                      @OA\Property(property="work_time", type="string", example="half day"),
     *                      @OA\Property(property="login_remarks", type="string", example="late because of rain"),
     *                      @OA\Property(property="logout_remarks", type="string", example="transportation problem"),
     *                      @OA\Property(property="is_regularized", type="boolean", example=1),
     *                      @OA\Property(property="regularized_by", type="boolean", example=1),
     *                      @OA\Property(property="actual_shift_end", type="time", example="19:00:00"),
     *                      @OA\Property(property="total_login_hours", type="time", example="08:00:00"),
     *                      @OA\Property(property="actual_shift_start", type="time", example="08:00:00"),
     *                      @OA\Property(property="total_working_hours", type="time", example="08:00:00"),
     *                      @OA\Property(property="leave_application_id", type="integer", example=1),
     *                      @OA\Property(property="regularization_remarks", type="string", example="test"),
     *                      @OA\Property(property="created_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
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
     *         description="Not found",
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
    public function show($id)
    {
        try {

            $attendance = Attendance::findOrFail($id);

            return response()->json([
                'status'    => true,
                'message'   => 'Attendance details retrieved successfully.',
                'data'      => new AttendanceResource($attendance) ?? []
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance not found.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
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
     * @OA\GET(
     *     path="/v1/attendance/search",
     *     tags={"Attendance"},
     *     summary="Find Attendance Details",
     *     operationId="searchAttendance",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=true,
     *         @OA\Schema(type="integer", default=10),
     *     ),
     *     @OA\Parameter(
     *         name="current_page",
     *         in="query",
     *         description="Current page number",
     *         required=true,
     *         @OA\Schema(type="integer", default=1),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicating that the Attendance found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Attendance.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Attendance.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Attendance Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="ar_date", type="date", example="2024-01-22"),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="day_type", type="string", example="working day"),
     *                      @OA\Property(property="shift_id", type="integer", example=1),
     *                      @OA\Property(property="work_time", type="string", example="half day"),
     *                      @OA\Property(property="login_remarks", type="string", example="late because of rain"),
     *                      @OA\Property(property="logout_remarks", type="string", example="transportation problem"),
     *                      @OA\Property(property="is_regularized", type="boolean", example=1),
     *                      @OA\Property(property="regularized_by", type="boolean", example=1),
     *                      @OA\Property(property="actual_shift_end", type="time", example="19:00:00"),
     *                      @OA\Property(property="total_login_hours", type="time", example="08:00:00"),
     *                      @OA\Property(property="actual_shift_start", type="time", example="08:00:00"),
     *                      @OA\Property(property="total_working_hours", type="time", example="08:00:00"),
     *                      @OA\Property(property="leave_application_id", type="integer", example=1),
     *                      @OA\Property(property="regularization_remarks", type="string", example="test"),
     *                      @OA\Property(property="created_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
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

    public function search(Request $request)
    {
        $perPage        = $request->per_page == "" ? 10 : $request->per_page;
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;;

        try {

            $attendance = Attendance::select('id', 'user_id', 'ar_date', 'day_type', 'shift_id', 'work_time', 'login_remarks', 'logout_remarks', 'is_regularized', 'regularized_by', 'actual_shift_end', 'total_login_hours', 'actual_shift_start', 'total_working_hours', 'leave_application_id', 'regularization_remarks', 'created_at', 'updated_at')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Attendance list.',
                'data'      =>  $attendance ?? [],
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function workingHoursCreate(Request $request)
    {

        try {

            AttendanceWork::updateOrCreate(
                [
                    'id'                        =>  $request->working_hours_id
                ],
                [
                    'name'                      => $request->name ?? null,
                    'status'                    => $request->status ?? null,
                    'created_by'                => auth()->user()->id,
                    'total_hours'               => $request->total_hours ?? null,
                    'full_day_hours'            => $request->full_day_hours ?? null,
                    'half_day_hours'            => $request->half_day_hours ?? null,
                    'grace_for_checkin'         => $request->grace_for_checkin ?? null,
                    'late_checkin_count'        => $request->late_checkin_count ?? null,
                    'late_checkin_minutes'        => $request->late_checkin_minutes ?? null,
                    'grace_for_checkout'        => $request->grace_for_checkout ?? null,
                    'early_checkout_count'      => $request->early_checkout_count ?? null,
                    'late_checkin_allowed'      => $request->late_checkin_allowed ?? null,
                    'early_checkout_minutes'      => $request->early_checkout_minutes ?? null,
                    'late_checkin_frequency'    => $request->late_checkin_frequency ?? null,
                    'early_checkout_allowed'    => $request->early_checkout_allowed ?? null,
                    'early_checkout_frequency'  => $request->early_checkout_frequency ?? null,
                    'working_hours_calculation' => $request->working_hours_calculation ?? null,
                ]
            );

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Attendance Working Hours data added.',
            ], 201);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function regularizationCreate(Request $request)
    {

        try {

            AttendanceRegularize::updateOrCreate(
                [
                    'id'                            =>  $request->regularization_id,
                ],
                [
                    'name'                          =>  $request->name ?? null,
                    'times'                         =>  $request->times ?? null,
                    'past_days'                     =>  $request->past_days ?? null,
                    'frequency'                     =>  $request->frequency ?? null,
                    'after_days'                    =>  $request->after_days ?? null,
                    'past_month'                    =>  $request->past_month ?? null,
                    'created_by'                    =>  auth()->user()->id,
                    'future_days'                   =>  $request->future_days ?? null,
                    'current_day'                   =>  $request->current_day ?? null,
                    'before_salary'                 =>  $request->before_salary ?? null,
                    'attendance_working_hour_id'    =>  $request->attendance_working_hour_id ?? null,
                ]
            );

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Attendance Regularization Added.'
            ], 201);
        } catch (Exception $e) {
        }
    }

    public function assign(Request $request)
    {

        try {

            AttendanceAssign::updateOrCreate(
                [
                    'id'                            =>  $request->assign_id,
                ],
                [
                    'status'                        =>  $request->status ?? null,
                    'user_id'                       =>  $request->user_id ?? null,
                    'shift_id'                      =>  $request->shift_id ?? null,
                    'created_by'                    =>  auth()->user()->id,
                    'effective_to'                  =>  $request->effective_to ?? null,
                    'effective_from'                =>  $request->effective_from ?? null,
                    'attendance_working_hour_id'    =>  $request->attendance_working_hour_id ?? null,
                    'attendance_regularization_id'  =>  $request->attendance_regularization_id ?? null,
                ]
            );

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Attendance Assign Added.'
            ], 201);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 500);
        }
    }

}
