<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeLogRequest;
use App\Http\Resources\TimeLogCollection;
use App\Http\Resources\TimeLogResource;
use App\Models\TimeLog;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimeLogController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/time-log/list",
     *     tags={"Time Log"},
     *     summary="Time Log list.",
     *     operationId="listTimeLog",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Time Log list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Time Log list fetched successfully.",
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
     *             description="Contains the object of Time Log.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Time Log list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="user_name", type="string", example="User Name"),
     *                      @OA\Property(property="activity", type="string", example="Shift start"),
     *                      @OA\Property(property="date", type="date", example="12-Dec-2023"),
     *                      @OA\Property(property="time", type="time", example="10:00:00"),
     *                      @OA\Property(property="terminal", type="string", example="Biometric"),
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
     *         description="Page not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Page not found"
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
    public function index()
    {
        try {

            $timelogs = TimeLog::orderBy('id', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Activity list.',
                'data'      =>  new TimeLogCollection($timelogs) ?? []
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    # Show the form for creating a new resource.
    #
    # @return \Illuminate\Http\Response
    public function create()
    {
        //
    }

    /**
     * @OA\POST(
     *     path="/v1/time-log/create",
     *     tags={"Time Log"},
     *     summary="Create New Time Log.",
     *     operationId="createTimeLog",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Time Log.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"user_id", "actvity", "date", "time", "terminal"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="activity",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="Date"
     *                 ),
     *                 @OA\Property(
     *                     property="time",
     *                     type="Time"
     *                 ),
     *                 @OA\Property(
     *                     property="terminal",
     *                     type="String"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Time Log created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Time Log.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Time Log created successfully."),
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
     *         description="Page not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Page not found"
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TimeLogRequest $request)
    {
        $now = Carbon::now('Asia/Kolkata');
        try {

            $time_log = TimeLog::create([
                'user_id'           =>  auth()->user()->id,
                'activity'          =>  trim(strtolower($request->activity)) ?? "",
                'date'              =>  $now->toDateString(),
                'time'              =>  $now->toTimeString() ?? "",
                'terminal'          =>  trim(strtolower($request->terminal)) ?? "",
                'messages'          =>  trim(strtolower($request->messages)) ?? "",
            ]);

            if ($time_log) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Time Log created successfully.'
                ], 201);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 500);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/time-log/show/{id}",
     *     tags={"Time Log"},
     *     summary="Find Time Log Details",
     *     operationId="showTimeLog",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Time Log ID",
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
     *             description="Status indicating that the time_log found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the time_log.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of time_log.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="time_log Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="user_name", type="string", example="User Name"),
     *                      @OA\Property(property="activity", type="string", example="Shift start"),
     *                      @OA\Property(property="date", type="date", example="12-Dec-2023"),
     *                      @OA\Property(property="time", type="time", example="10:00:00"),
     *                      @OA\Property(property="terminal", type="string", example="Biometric"),
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
     *         description="Page not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Page not found"
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $time_log = TimeLog::findOrFail($id);

            return response()->json([
                'status'    => true,
                'message'   => 'Time Log details retrieved successfully.',
                'data'      => new TimeLogResource($time_log) ?? []
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Timelog not found.',
                'exception' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    # Show the form for editing the specified resource.
    #
    # @param  int  $id
    # @return \Illuminate\Http\Response
    public function edit($id)
    {
        //
    }

    /**
     * @OA\PUT(
     *     path="/v1/time-log/update/{id}",
     *     tags={"Time Log"},
     *     summary="Update Time Log Details.",
     *     operationId="updateTimeLog",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Time Log ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Create New Time Log.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"user_id", "actvity", "date", "time", "terminal"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="activity",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="Date"
     *                 ),
     *                 @OA\Property(
     *                     property="time",
     *                     type="Time"
     *                 ),
     *                 @OA\Property(
     *                     property="terminal",
     *                     type="String"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Time Log updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Time Log.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Time Log updated successfully."),
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
     *         description="Page not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Page not found"
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TimeLogRequest $request, $id)
    {

        try {

            $time_log = TimeLog::findOrFail($id);

            $time_log->user_id      = trim($request->user_id) ?? "";
            $time_log->activity     = trim($request->activity) ?? "";
            $time_log->date         = trim($request->date) ?? "";
            $time_log->time         = trim($request->time) ?? "";
            $time_log->terminal     = trim($request->terminal) ?? "";
            $time_log->status       = (int)$request->status ?? "";
            $time_log->messages     = trim($request->messages) ?? "";

            return response()->json([
                'status'  => true,
                'message' => 'Time Log updated successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Timelog not found.',
                'exception' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/v1/time-log/delete/{id}",
     *     tags={"Time Log"},
     *     summary="Delete Time Log.",
     *     operationId="deleteTimeLog",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Time Log ID",
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
     *             description="Status indicating that the Time Log was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Time Log.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Time Log deleted successfully."),
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
     *         description="Page not found",
     *         @OA\MediaType(
     *             mediaType="string",
     *              example="Page not found"
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

        try {

            $time_log = TimeLog::findOrFail($id);

            $time_log->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Activity deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Timelog not found.',
                'exception' =>  $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/v1/time-log/search",
     *     tags={"Time Log"},
     *     summary="User specific time-logs.",
     *     operationId="searchTimeLog",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Time Log list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Time Log list fetched successfully.",
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
     *             description="Contains the object of Time Log.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Time Log list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="date", type="date", example="2024-01-05"),
     *                      @OA\Property(property="user_id", type="string", example=3),
     *                      @OA\Property(property="activity", type="string", example="Shift start"),
     *                      @OA\Property(property="terminal", type="string", example="Biometric"),
     *                      @OA\Property(property="Message", type="string", example="Came late because of office work."),
     *                      @OA\Property(property="create_at", type="datetime", example="2024-01-05 12:59:29"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-05 12:59:29"),
     *                      @OA\Property(property="time", type="time", example="10:00:00"),
     *                      @OA\Property(property="user_name", type="string", example="Magicminds Admin"),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid User Id.",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Invalid User Id."
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
        $perPage        = $request->per_page == "" ? 10 : $request->per_page;
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;;
        $days = 7;

        try {
            $userId = Auth::id();
            $sevenDaysAgo = Carbon::now()->subDays($days);
            $endDate = Carbon::now()->endOfDay();

            $time_logs = TimeLog::select('date', 
                DB::raw('MIN(CASE WHEN activity = "shift start" THEN DATE_FORMAT(time, "%h:%i %p") END) as first_shift_start'), 
                DB::raw('MAX(CASE WHEN activity = "shift end" THEN DATE_FORMAT(time, "%h:%i %p") END) as last_shift_end'))
                ->where('user_id', $userId)
                ->where('date', '>=', $sevenDaysAgo)
                ->where('date', '<=', $endDate) 
                ->whereIn('activity', ['shift start', 'shift end'])
                ->groupBy('date')
                ->orderBy('date', 'desc')
                // ->orderBy('time', 'desc')
                ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    => true,
                'message'   => 'User shifts for the last 7 days.',
                'data'      => $time_logs
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching user shifts.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }


    
    public function attendance()
    {
        $my_id = auth()->user()->id;

        try {
            $startDate = \Carbon\Carbon::now()->subDays(6)->startOfDay();
            $endDate = \Carbon\Carbon::now()->endOfDay();

            $attendances = TimeLog::where('user_id', $my_id)
            ->whereDate('date', '>=', $startDate) 
            ->whereDate('date', '<=', $endDate)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Timelog Activities retrieved successfully.',
                'data' => new TimeLogCollection($attendances),
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}
