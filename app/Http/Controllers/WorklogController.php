<?php

namespace App\Http\Controllers;

use App\Exports\WorklogExport;
use App\Http\Requests\WorklogRequest;
use App\Http\Resources\WorkLogCollection;
use App\Http\Resources\WorklogResource;
use App\Models\DepartmentProject;
use App\Models\EmpDepartment;
use App\Models\Project;
use App\Models\User;
use App\Models\Worklog;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class WorklogController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/worklog/list",
     *     tags={"Work Log"},
     *     summary="Worklog list.",
     *     operationId="Wistworklog",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Worklog list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Worklog list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the User after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Worklog.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Worklog list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="Integer", example=1),
     *                      @OA\Property(property="date", type="date", example="2024-01-15"),
     *                      @OA\Property(property="task_url", type="url", example="https://atlassian-mmt.atlassian.net/browse/MAG-134"),
     *                      @OA\Property(property="created_at", type="string", example="2024-01-15"),
     *                      @OA\Property(property="description", type="string", example="Task description"),
     *                      @OA\Property(
     *                          property="client", 
     *                          type="array", 
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="url", example="https://atlassian-mmt.atlassian.net/browse/MAG-134"),
     *                              @OA\Property(property="name", type="string", example="James Ferrari"),
     *                              @OA\Property(property="type", type="string", example="New"),
     *                              @OA\Property(property="site", type="string", example="International"),
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="project", 
     *                          type="array", 
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="url", example="1"),
     *                              @OA\Property(property="honorific", type="string", example="Mr."),
     *                              @OA\Property(property="first_name", type="string", example="Ryan"),
     *                              @OA\Property(property="middle_name", type="string", example="Doweney"),
     *                              @OA\Property(property="last_name", type="string", example="Junior"),
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="activity", 
     *                          type="array", 
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="url", example="2"),
     *                              @OA\Property(property="name", type="string", example="Php Backend Development"),
     *                          ),
     *                      ),
     *                      @OA\Property(property="time_spent", type="string", example="2h 45m"),
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

            $worklogs = Worklog::orderBy('created_at', 'desc')->get();

            if (isset($worklogs) && sizeof($worklogs) > 0) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Worklogs fetched.',
                    'data'      =>  new WorkLogCollection($worklogs),
                ], 200);
            }
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
     *     path="/v1/worklog/create",
     *     tags={"Work Log"},
     *     summary="Create New Work Log.",
     *     operationId="createWorkLog",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Add New Work Log.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"activity_id", "project_id", "client_id", "date", "time_spent", "task_url"},
     *                 @OA\Property(
     *                     property="activity_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="project_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="client_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="time_spent",
     *                     type="time"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="task_url",
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
     *             description="Status indicates that Work Log created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Work Log.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Work Log created successfully."),
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
    public function store(WorklogRequest $request)
    {
        try {

            Worklog::create([
                'date'          =>  $request->date ?? null,
                'target'        =>  $request->target ?? null,
                'user_id'       =>  auth()->user()->id ?? null,
                'task_url'      =>  $request->task_url ?? null,
                'client_id'     =>  $request->client_id ?? null,
                'project_id'    =>  $request->project_id ?? null,
                'time_spent'    =>  $request->time_spent ?? null,
                'activity_id'   =>  $request->activity_id ?? null,
                'description'   =>  strip_tags($request->description) ?? null,
            ]);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Worklog added successfully.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ], 500);
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
     *     path="/v1/worklog/search",
     *     tags={"Work Log"},
     *     summary="Search Work Log.",
     *     operationId="searchWorkLog",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Search Work Log.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={},
     *                 @OA\Property(
     *                     property="project_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="activity_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="start_date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="end_date",
     *                     type="date"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Work Log created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Work Log.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Worklogs fetched."),
     *             @OA\Property(property="data", type="object", 
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="user_id", type="integer", example="1"),
     *                  @OA\Property(property="activity_id", type="integer", example="1"),
     *                  @OA\Property(property="project_id", type="integer", example="1"),
     *                  @OA\Property(property="client_id", type="integer", example="1"),
     *                  @OA\Property(property="date", type="date", example="2024-01-12"),
     *                  @OA\Property(property="time_spent", type="time", example="2h 39m"),
     *                  @OA\Property(property="task_url", type="url", example="https://atlassian-mmt.atlassian.net/browse/MAG-133"),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // $days               =   $request->days == "" ? 60 : (int)$request->days;
        // $currentDate        =   Carbon::now();
        // $pastDate           =   Carbon::now()->subDays($days);
        // $pastFormatted      =   $pastDate->format('Y-m-d');
        // $currentFormatted   =   $currentDate->format('Y-m-d');

        $perPage            =   $request->per_page != "" ? (int)$request->per_page : 10;
        $currentPage        =   $request->current_page != "" ? (int)$request->current_page : 1;
        $user_id            =   $request->user_id ?? "";
        $end_date           =   $request->end_date ?? "";
        $order_by           =   $request->order_by ?? "id";
        $order_type         =   $request->order_type ?? "desc";
        $start_date         =   $request->start_date ?? "";
        $employee_name      =   $request->employee_name ?? "";
        $project_id         =   $request->project_id ?? "";
        $activity_id        =   $request->activity_id ?? "";

        try {

            $worklogs = Worklog::with(
                'userDetails',
                'clientDetails',
                'projectDetails',
                'activityDetails'
            );
            
            if ($user_id != "") {
                $worklogs = $worklogs->where('user_id', $user_id);
            }

            if ($employee_name != "") {
                $name_user_ids = User::select('id')
                    ->where('first_name', 'like', '%' . $employee_name . '%')
                    ->orWhere('middle_name', 'like', '%' . $employee_name . '%')
                    ->orWhere('last_name', 'like', '%' . $employee_name . '%')
                    ->pluck('id')
                    ->toArray();

                $worklogs = $worklogs->whereIn('user_id', $name_user_ids);
            }

            if ($project_id != "") {
                $worklogs = $worklogs->where('project_id', $project_id);
            }

            if ($activity_id != "") {
                $worklogs = $worklogs->where('activity_id', $activity_id);
            }

            if ($start_date != "") {
                $worklogs = $worklogs->where('date', '>=', $start_date);
            }

            if ($end_date != "") {
                $worklogs = $worklogs->where('date', '<=', $end_date);
            }

            $worklogs = $worklogs->orderBy($order_by, $order_type);

            $worklogs = $worklogs->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();


            // This code is used to add excel download file for the search data
            // if(sizeof($worklogs) > 0) {
            //     $excel          = Excel::download(new WorklogExport($worklogs), 'worklogs.xlsx');
            //     $download_link  = $excel->getFile()->getLinkTarget();
            // }

            return response()->json([
                'status'        =>  true,
                'message'       =>  'Worklogs fetched.',
                'download_link' =>  $download_link ?? "",
                'data'          =>  $worklogs,
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function team(Request $request, $id)
    {

        try {

            $project = Project::with("departments")->findOrFail($id);
            $my_department = EmpDepartment::where('user_id', Auth::id())->firstOrFail();

            // return response()->json([
            //     'status'    =>  true,
            //     'message'   =>  "Project details found.",
            //     'data'      =>  [
            //         'project'   =>  $project,
            //         // 'teamworks' =>  $worklogs,
            //         // 'project_id' =>  $project_id,
            //         'my_department_id' =>  $my_department,
            //         // 'teamworks' =>  $groupedWorklogs,
            //     ]
            // ], 200);

            $project_id         = (int)$id ?? null;
            $my_department_id   = $my_department['id'];

            $worklogs = Worklog::where('project_id', $project_id)
                ->where('department_id', $my_department_id)
                ->get();

            $department_project = DepartmentProject::where('project_id', $project_id)
                                                        ->where('department_id', $my_department_id)
                                                        ->first();

            $groupedWorklogs = [];

            foreach ($worklogs as $worklog) {

                $userId = $worklog['user_id'];

                if (!isset($groupedWorklogs[$userId])) {

                    $time           = $worklog['time_spent'];
                    $timeObj        = DateTime::createFromFormat('H:i:s', $time);
                    $totalMinutes   = $timeObj->format('H') * 60 + $timeObj->format('i');

                    $groupedWorklogs[$userId] = [
                        'id'                => $worklog['id'],
                        'estimation_value'  => $department_project['estimation_value'],
                        'estimation_type'   => $department_project['estimation_type'],
                        'user_id'           => $worklog['user_id'],
                        'time_spent'        => $worklog['time_spent'],
                        'time_in_minutes'   => $totalMinutes,
                        'user'              => User::find($worklog['user_id']),
                    ];

                } else {

                    $time           = $worklog['time_spent'];
                    $timeObj        = DateTime::createFromFormat('H:i:s', $time);
                    $totalMinutes   = $timeObj->format('H') * 60 + $timeObj->format('i');

                    $groupedWorklogs[$userId]['time_spent']         = $this->addTimes($groupedWorklogs[$userId]['time_spent'], $worklog['time_spent']);
                    $groupedWorklogs[$userId]['time_in_minutes']   += $totalMinutes;
                }
            }

            return response()->json([
                'status'    =>  true,
                'message'   =>  "Project details found.",
                'data'      =>  [
                    'project'   =>  $project,
                    // 'teamworks' =>  $worklogs,
                    // 'project_id' =>  $project_id,
                    // 'my_department_id' =>  $my_department_id,
                    'teamworks' =>  $groupedWorklogs,
                ]
            ], 200);

            // return $groupedWorklogs;

        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  "Project details not found.",
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  "Something went wrong",
            ], 500);
        }
    }

    function addTimes($time1, $time2)
    {
        $seconds = strtotime("1970-01-01 $time1 UTC");
        $seconds += strtotime("1970-01-01 $time2 UTC") - strtotime("1970-01-01 00:00:00 UTC");
        return gmdate("H:i:s", $seconds);
    }
}
