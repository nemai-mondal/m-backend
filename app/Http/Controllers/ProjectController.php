<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Technology;
use App\Models\Client;
use App\Models\Department;
use App\Models\DepartmentProject;
use App\Models\Designation;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;
use App\Models\EmpProject;
use App\Models\ProjectDocument;
use App\Models\ProjectTarget;
use App\Models\TaskTarget;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;



class ProjectController extends Controller
{
    use SoftDeletes;

    /**
     * @OA\GET(
     *     path="/v1/project/list",
     *     tags={"Project"},
     *     summary="Project list.",
     *     operationId="listProject",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Project list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Project list fetched successfully.",
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
     *             description="Contains the object of Project.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Project list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="TCS"),
     *                      @OA\Property(property="client_id", type="integer", example=1),
     *                      @OA\Property(property="manager_id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="department_id", type="integer", example=1),
     *                      @OA\Property(property="start_date", type="date", example="2024-12-12"),
     *                      @OA\Property(property="end_date", type="date", example="2025-03-07"),
     *                      @OA\Property(property="estimation_value", type="integer", example=123),
     *                      @OA\Property(property="estimation_type", type="string", example="Hours"),
     *                      @OA\Property(property="cost", type="string", example="2000"),
     *                      @OA\Property(property="currency_type", type="string", example="currency_type"),
     *                      @OA\Property(property="project_type", type="string", example="Web App Development"),
     *                      @OA\Property(property="project_status", type="string", example="Planning"),
     *                      @OA\Property(property="priority", type="string", example="High"),
     *                      @OA\Property(property="experience", type="string", example="1"),
     *                      @OA\Property(property="salary_range", type="string", example="1"),
     *                      @OA\Property(property="no_of_openings", type="string", example="1"),
     *                      @OA\Property(property="notice_period", type="string", example="1"),
     *                      @OA\Property(property="description", type="string", example="Sample"),
     *                      @OA\Property(property="status", type="boolean", example=1),
     *                      @OA\Property(property="created_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="updated_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
     *                      @OA\Property(property="deleted_at", type="datetime", example="2024-01-22T07:24:22.000000Z"),
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

            $projects = Project::with(
                'user',
                'client',
                'documents',
                'resources',
                'department',
                'technologies',
                'updatedByUser',
                'projectManager',
            )->orderBy('id', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Project list.',
                'data'      =>  $projects
                // 'data'      =>  new ProjectCollection($projects) ?? []
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

    # Store a newly created resource in storage.
    #
    # @param  \Illuminate\Http\Request  $request
    # @return \Illuminate\Http\Response
    public function store(ProjectRequest $request)
    {

        $step    = $request->step ?? "";
        switch ($step) {
            case 1:
                return $this->AddProject($request);
                break;
            case 2:
                return $this->AddProjectResource($request);
                break;
            case 3:
                return $this->UploadProjectDocument($request);
                break;
            case 4:
                return $this->AddTarget($request);
                break;
            case 5:
                return $this->AddSalesResource($request);
                break;
            default:
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Invalid or Missing Step Id.',
                ], 400);
        }
    }

    public function AddProject(Request $request)
    {
        $id                     =   $request->id ?? "";
        $key                    =   $request->key ?? "";
        try {
            if ($key == "create") {
                $project = Project::create([
                    'name'              => trim($request->name),
                    'cost'              => $request->cost ?? null,
                    'status'            => 1,
                    'user_id'           => auth()->user()->id ?? null,
                    'priority'          => $request->priority ?? null,
                    'end_date'          => $request->end_date ?? null,
                    'client_id'         => $request->client_id ?? null,
                    'updated_by'        => auth()->user()->id ?? null,
                    'start_date'        => $request->start_date ?? null,
                    'manager_id'        => $request->manager_id ?? null,
                    'experience'        => $request->experience ?? null,
                    'updated_by'        =>  auth()->user()->id,
                    'description'       => $request->description ?? null,
                    'project_type'      => $request->project_type ?? null,
                    'salary_range'      => $request->salary_range ?? null,
                    'currency_type'     => $request->currency_type ?? null,
                    'department_id'     => $request->department_id ?? null,
                    'notice_period'     => $request->notice_period ?? null,
                    'project_status'    => $request->project_status ?? null,
                    'no_of_openings'    => $request->no_of_openings ?? null,
                    'estimation_type'   => $request->estimation_type ?? null,
                    'department_name'   => $request->department_name ?? null,
                    'estimation_value'  => $request->estimation_value ?? null,
                ]);

                if ($project && (isset($request->technologies) && sizeof($request->technologies) > 0)) {
                    $project->technologies()->sync($request->technologies);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Project created successfully.',
                    'project_id' => $project->id,
                ], 201);
            } elseif ($key == "update") {
                $project = Project::findOrFail($id);
                $project->update([
                    'name'              => trim($request->name),
                    'cost'              => $request->cost ?? null,
                    'status'            => 1,
                    'updated_by'        => auth()->user()->id ?? null,
                    'priority'          => $request->priority ?? null,
                    'end_date'          => $request->end_date ?? null,
                    'client_id'         => $request->client_id ?? null,
                    'start_date'        => $request->start_date ?? null,
                    'manager_id'        => $request->manager_id ?? null,
                    'experience'        => $request->experience ?? null,
                    'updated_by'        =>  auth()->user()->id,
                    'description'       => $request->description ?? null,
                    'project_type'      => $request->project_type ?? null,
                    'salary_range'      => $request->salary_range ?? null,
                    'currency_type'     => $request->currency_type ?? null,
                    'department_id'     => $request->department_id ?? null,
                    'notice_period'     => $request->notice_period ?? null,
                    'project_status'    => $request->project_status ?? null,
                    'no_of_openings'    => $request->no_of_openings ?? null,
                    'estimation_type'   => $request->estimation_type ?? null,
                    'department_name'   => $request->department_name ?? null,
                    'estimation_value'  => $request->estimation_value ?? null,
                ]);

                if ($project && isset($request->technologies) && count($request->technologies) > 0) {
                    // Delete previous data associated with technologies
                    $project->technologies()->detach();

                    // Insert new data based on the incoming request
                    $project->technologies()->sync($request->technologies);
                }


                Project::where('id', $id)->update([
                    'updated_by'    =>  auth()->user()->id
                ]);

                return response()->json([
                    'status'  => true,
                    'message' => 'Project updated successfully .',
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function AddProjectResource(Request $request)
    {

        // if(isset($request->department_name) && $request->department_name != "sales") {

        /**
         * Chek if the total allocated time is less than the 
         * Department wise time (Combined)
         */
        $total_allocated_time   = 0;
        $total_resource_time    = 0;
        foreach ($request->resources as $resource) {
            if (strtolower($resource['estimation_type']) == "day") {
                $total_resource_time += ($resource['estimation_value'] * 8);
            } else {
                $total_resource_time += $resource['estimation_value'];
            }
        }

        $project_time = Project::find($request->resources[0]['project_id']);

        $total_allocated_time   = $project_time['estimation_value'];

        if (strtolower($project_time['estimation_type']) == "hours") {
            if ($total_allocated_time < $total_resource_time) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Total allocated time to the project is less than the total allocated resource time.',
                ], 422);
            }
        } else if (strtolower($project_time['estimation_type']) == "day") {
            if ($total_allocated_time < ($total_resource_time / 8)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Total allocated time to the project is less than the total allocated resource time.',
                ], 422);
            }
        }

        // }    

        try {

            $project_id = $request->resources[0]['project_id'];

            DepartmentProject::where([
                'project_id'        => $request->resources[0]['project_id']
            ])->delete();

            $project = Project::find($project_id);
            $project->projectResources()->delete();

            foreach ($request->resources as $resource) {

                $existingRecord = DepartmentProject::withTrashed()->where([
                    'project_id'        => $resource['project_id'] ?? null,
                    'department_id'     => $resource['department_id'] ?? null,
                    'designation_id'    => $resource['designation_id'] ?? null,
                ])->first();

                if ($existingRecord) {

                    $existingRecord->restore();
                    $existingRecord->update([
                        'estimation_type' => $resource['estimation_type'] ?? null,
                        'estimation_value' => $resource['estimation_value'] ?? null,
                    ]);
                } else {
                    DepartmentProject::updateOrCreate(
                        [
                            'project_id' => $resource['project_id'] ?? null,
                            'department_id' => $resource['department_id'] ?? null,
                            'designation_id' => $resource['designation_id'] ?? null,
                        ],
                        [
                            'estimation_type' => $resource['estimation_type'] ?? null,
                            'estimation_value' => $resource['estimation_value'] ?? null,
                        ]
                    );
                }

                if (isset($resource['project_id']) && $resource['project_id'] != null && $resource['project_id'] != "") {

                    foreach ($resource['user_ids'] as $user_id) {

                        $empProject = EmpProject::withTrashed()
                            ->where('project_id', $project_id)
                            ->where('user_id', $user_id)
                            ->first();

                        if ($empProject) {
                            if ($empProject->trashed()) {
                                $empProject->restore();
                            }
                        } else {
                            EmpProject::create([
                                'project_id' => $resource['project_id'] ?? "",
                                'user_id' => $user_id,
                            ]);
                        }
                    }
                }
            }

            Project::where('id', $project_id)->update([
                'updated_by'    =>  auth()->user()->id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Project Resources added successfully.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function AddTarget(Request $request)
    {

        try {

            $project_id = $request->resources[0]['project_id'];
            $projectTargets = ProjectTarget::where('project_id', $project_id)->get();

            if ($projectTargets->isNotEmpty()) {
                foreach ($projectTargets as $projectTarget) {
                    $projectTarget->delete();
                }
            }

            $project = Project::find($project_id);
            $project->projectResources()->delete();

            foreach ($request->resources as $resource) {

                $department_id  = $resource['department_id'] ?? null;
                $designation_id = $resource['designation_id'] ?? null;
                $project_id     = $resource['project_id'] ?? null;


                foreach ($resource['activity'] as $activity) {

                    $empProject = ProjectTarget::withTrashed()
                        ->where('project_id', $project_id)
                        ->where('activity_id', $activity['id'])
                        ->where('designation_id', $designation_id)
                        ->where('user_id', auth()->user()->id)
                        ->first();
                    if ($empProject) {
                        if ($empProject->trashed()) {
                            $empProject->restore();
                        }
                        $empProject->update([
                            'user_id'           => auth()->user()->id,
                            'activity_id'       => $activity['id'] ?? null,
                            'assigned_by'       => auth()->user()->id,
                            'monthly'           => $activity['monthly'] ?? null,
                            'daily'             => $activity['daily'] ?? null,
                            'weekly'            => $activity['weekly'] ?? null,
                            'project_id'        => $project_id,
                            'department_id'     => $department_id,
                            'designation_id'    => $designation_id,
                        ]);
                    } else {
                        ProjectTarget::Create([
                            'user_id'           => auth()->user()->id,
                            'activity_id'       => $activity['id'] ?? null,
                            'assigned_by'       => auth()->user()->id,
                            'monthly'           => $activity['monthly'] ?? null,
                            'daily'             => $activity['daily'] ?? null,
                            'weekly'            => $activity['weekly'] ?? null,
                            'project_id'        => $project_id,
                            'department_id'     => $department_id,
                            'designation_id'    => $designation_id,
                        ]);
                    }
                }

                if (isset($resource['project_id']) && $resource['project_id'] != null && $resource['project_id'] != "") {

                    foreach ($resource['user_ids'] as $userId) {

                        $empProject = EmpProject::withTrashed()
                            ->where('project_id', $project_id)
                            ->where('user_id', $userId)
                            ->first();

                        if ($empProject) {
                            if ($empProject->trashed()) {
                                $empProject->restore();
                            }
                        } else {
                            EmpProject::create([
                                'project_id'    => $project_id,
                                'user_id'       => $userId,
                            ]);
                        }
                    }
                }
            }

            Project::where('id', $project_id)->update([
                'updated_by'    =>  auth()->user()->id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Project Resources added successfully.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function UploadProjectDocument(Request $request)
    {
        $id                     =   $request->id ?? "";
        $key                    =   $request->key ?? "";
        $name                   =   $request->name ?? "";
        $description            =   $request->description ?? "";
        $project_id             =   $request->project_id ?? "";

        try {
            if ($key == "create") {
                $project_document = ProjectDocument::create([
                    'name'                  =>  $name,
                    'user_id'               =>  auth()->user()->id,
                    'description'           =>  $description,
                    'project_id'            =>  $project_id,
                ]);

                if ($request->hasFile("file")) {
                    // $project_document->clearMediaCollection('project-document');

                    $project_document->addMediaFromRequest('file')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('project-document');
                }

                Project::where('id', $project_id)->update([
                    'updated_by'    =>  auth()->user()->id
                ]);

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Project Document added successfully.',
                ], 201);
            } elseif ($key == "update") {
                $project_document = ProjectDocument::findOrFail($id);

                $project_document->update($request->only('name', 'description', 'user_id'));

                if ($request->hasFile("image")) {
                    $project_document->clearMediaCollection('project-document');

                    $project_document->addMediaFromRequest('image')
                        ->sanitizingFileName(function ($fileName) {
                            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                        })
                        ->toMediaCollection('project-document');

                    $project_document->touch();
                }

                Project::where('id', $project_id)->update([
                    'updated_by'    =>  auth()->user()->id
                ]);


                return response()->json([
                    'status'  => true,
                    'message' => 'Project Document Updated Successfully.',
                    'i' =>  1
                ], 201);
            }
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function AddSalesResource(Request $request)
    {

        try {
            $project_id             =   $request->project_id ?? null;
            $key                    =   $request->key ?? "";
            $existing_project_id    =   null;

            if ($project_id == "" || $project_id == null) {

                $project = Project::create([
                    'name'              => 'sales',
                    // 'cost'              => $request->cost ?? null,
                    // 'status'            => 1,
                    'user_id'           => auth()->user()->id ?? null,
                    // 'priority'          => $request->priority ?? null,
                    // 'end_date'          => $request->end_date ?? null,
                    // 'client_id'         => $request->client_id ?? null,
                    'updated_by'        => auth()->user()->id ?? null,
                    // 'start_date'        => $request->start_date ?? null,
                    // 'manager_id'        => $request->manager_id ?? null,
                    // 'experience'        => $request->experience ?? null,
                    // 'description'       => $request->description ?? null,
                    // 'project_type'      => $request->project_type ?? null,
                    // 'salary_range'      => $request->salary_range ?? null,
                    // 'currency_type'     => $request->currency_type ?? null,
                    // 'department_id'     => $request->department_id ?? null,
                    // 'notice_period'     => $request->notice_period ?? null,
                    // 'project_status'    => $request->project_status ?? null,
                    // 'no_of_openings'    => $request->no_of_openings ?? null,
                    // 'estimation_type'   => $request->estimation_type ?? null,
                    'department_name'   => 'sales',
                    // 'estimation_value'  => $request->estimation_value ?? null,
                ]);

                $project_id = $project['id'];
                $existing_project_id = $project['id'];
            }

            // return response()->json([
            //     'status' => false,
            //     'message' => $project_id
            // ], 201);

            // $project_id = $request->resources[0]['project_id'];

            // DepartmentProject::where([
            //     'project_id'        => $request->resources[0]['project_id']
            // ])->delete();

            // $project = Project::find($project_id);
            // $project->projectResources()->delete();

            $user_resources = EmpProject::where('project_id', $project_id)
                ->get();

            foreach ($user_resources as $user) {
                $user->delete();
            }

            $user_activities = ProjectTarget::where('project_id', $project_id)->get();

            foreach ($user_activities as $activity) {
                $activity->delete();
            }

            foreach ($request->resources as $resource) {

                $designation_id = $resource['designation_id'];
                foreach ($resource['activity'] as $activity) {

                    $empProject = ProjectTarget::withTrashed()
                        ->where('project_id', $project_id)
                        ->where('activity_id', $activity['id'])
                        ->where('designation_id', $designation_id)
                        ->where('user_id', auth()->user()->id)
                        ->first();

                    if ($empProject) {
                        if ($empProject->trashed()) {
                            $empProject->restore();
                            $empProject->update([
                                'user_id'           => auth()->user()->id,
                                'activity_id'       => $activity['id'] ?? null,
                                'assigned_by'       => auth()->user()->id,
                                'monthly'           => isset($activity['monthly']) && $activity['monthly'] != "" ? $activity['monthly'] : null,
                                'daily'             => isset($activity['daily']) && $activity['daily'] != "" ? $activity['daily'] : null,
                                // 'daily'             => $activity['daily'] ?? null,
                                'weekly'            => $activity['weekly'] ?? null,
                                'project_id'        => $project_id,
                                'department_id'     => $department_id ?? null,
                                'designation_id'    => $designation_id,
                            ]);
                        } else {
                            $empProject->update([
                                'user_id'           => auth()->user()->id,
                                'activity_id'       => $activity['id'] ?? null,
                                'assigned_by'       => auth()->user()->id,
                                'monthly'           => isset($activity['monthly']) && $activity['monthly'] != "" ? $activity['monthly'] : null,
                                'daily'             => isset($activity['daily']) && $activity['daily'] != "" ? $activity['daily'] : null,
                                // 'daily'             => $activity['daily'] ?? null,
                                'weekly'            => $activity['weekly'] ?? null,
                                'project_id'        => $project_id,
                                'department_id'     => $department_id ?? null,
                                'designation_id'    => $designation_id,
                            ]);
                        }
                    } else {
                        ProjectTarget::Create([
                            'user_id'           => auth()->user()->id,
                            'activity_id'       => $activity['id'] ?? null,
                            'assigned_by'       => auth()->user()->id,
                            'monthly'           => isset($activity['monthly']) && $activity['monthly'] != "" ? $activity['monthly'] : null,
                            'daily'             => isset($activity['daily']) && $activity['daily'] != "" ? $activity['daily'] : null,
                            // 'monthly'           => $activity['monthly'] ?? null,    
                            // 'daily'             => $activity['daily'] ?? null,
                            'weekly'            => $activity['weekly'] ?? null,
                            'project_id'        => $project_id,
                            'department_id'     => $department_id ?? null,
                            'designation_id'    => $designation_id,
                        ]);
                    }
                }



                $existingRecord = DepartmentProject::withTrashed()->where([
                    'project_id'        => $project_id,
                    'department_id'     => $resource['department_id'] ?? null,
                    'designation_id'    => $resource['designation_id'],
                ])->first();

                if ($existingRecord) {

                    $existingRecord->restore();
                    $existingRecord->update([
                        'daily'     => $resource['daily'] ?? null,
                        'weekly'    => $resource['weekly'] ?? null,
                        'monthly'   => $resource['monthly'] ?? null,
                    ]);
                } else {
                    DepartmentProject::updateOrCreate(
                        [
                            'project_id' => $project_id ?? null,
                            'department_id' => $resource['department_id'] ?? null,
                            'designation_id' => $resource['designation_id'] ?? null,
                        ]
                    );
                }

                if (isset($project_id) && $project_id != null) {

                    foreach ($resource['user_ids'] as $user_id) {

                        $empProject = EmpProject::withTrashed()
                            ->where('project_id', $project_id ?? "")
                            ->where('user_id', $user_id)
                            ->first();

                        if ($empProject) {
                            if ($empProject->trashed()) {
                                $empProject->restore();
                            }
                        } else {
                            EmpProject::create([
                                'project_id'    => $project_id,
                                'user_id'       => $user_id,
                            ]);
                        }
                    }
                }
            }

            Project::where('id', $project_id)->update([
                'updated_by'    =>  auth()->user()->id
            ]);

            return response()->json([
                'status' => true,
                'message' =>  $project_id == null ? 'Sales Resources added successfully.' : 'Sales Resources updated successfully.',
                'project_id'    =>  $project_id
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }



    # Display the specified resource.
    #
    # @param  int  $id
    # @return \Illuminate\Http\Response
    public function show($id)
    {
        try {

            $project = Project::with(
                'user',
                'client',
                'documents',
                'resources',
                'department',
                'departments',
                'technologies',
                'updatedByUser',
                'projectManager',
            )->findOrFail($id);

            if (isset($project) && $project != null && ($project['department_name'] == 'development')) {

                $departments = DepartmentProject::with('department')->where('project_id', $id)->get();

                $i = 0;
                $department_targets = [];
                foreach ($departments as $department) {

                    $userIds = EmpDepartment::where('department_id', $department['department_id'])
                        ->pluck('user_id');

                    $resources = EmpProject::with('user')->where('project_id', $id)
                        ->whereIn('user_id', $userIds)
                        ->get();

                    $department_targets[$i] = $department;
                    $department_targets[$i]['users'] = $resources;
                    $i++;
                }
                $project['task_and_target'] = $department_targets ?? [];
            }

            /**
             * Get the list of the Resources - Task & Target
             */
            $activities = [];
            if (isset($project) && $project != null && ($project['department_name'] == 'hr' || $project['department_name'] == 'sales' || $project['department_name'] == 'marketing')) {

                $designationIds = ProjectTarget::where('project_id', $id)
                    ->pluck('designation_id')->unique();

                $designations = [];
                $designation_targets = [];

                $j = 0;
                foreach ($designationIds as $designation_id) {

                    $designation = Designation::find($designation_id);


                    if (!in_array($designation_id, $designations)) {

                        $task_targets = ProjectTarget::where('project_id', $id)
                            ->where('designation_id', $designation_id)
                            ->get();

                        $userIds = EmpDesignation::where('designation_id', $designation_id)
                            ->pluck('user_id');

                        $project_users = EmpProject::whereIn('user_id', $userIds)
                            ->where('project_id', $id)
                            ->pluck('user_id');

                        $activityIds    = $task_targets->pluck('activity_id');
                        $activities     = Activity::whereIn('id', $activityIds)->get();
                        $users          = User::whereIn('id', $project_users)->get();

                        foreach ($task_targets as $task) {

                            $i = 0;
                            foreach ($activities as $activity) {

                                if ($activity['id'] == $task['activity_id']) {
                                    $activities[$i]['daily'] = $task['daily'];
                                    $activities[$i]['weekly'] = $task['weekly'];
                                    $activities[$i]['monthly'] = $task['monthly'];
                                }
                                $i++;
                            }
                        }


                        $designation_targets[$j]['users']       = $users;
                        $designation_targets[$j]['activities']  = $activities;
                        $designation_targets[$j]['designation']  = $designation;

                        $j++;

                        array_push($designations, $designation_id);
                    }
                }

                $project['task_and_target'] = $designation_targets ?? [];
            }

            // if (isset($project) && $project != null && ($project['department_name'] == 'hr' || $project['department_name'] == 'sales')) {

            //     $designationIds = ProjectTarget::where('project_id', $id)
            //         ->pluck('designation_id')->unique();

            //     $designations = [];
            //     $designation_targets = [];


            //     $j = 0;
            //     foreach ($designationIds as $designation_id) {

            //         $designation = Designation::find($designation_id);


            //         if (!in_array($designation_id, $designations)) {

            //             $task_targets = ProjectTarget::where('project_id', $id)
            //                                             ->where('designation_id', $designation_id)
            //                                             ->get();

            //             $userIds = EmpDesignation::where('designation_id', $designation_id)
            //                                         ->pluck('user_id');

            //             // return response()->json([
            //             //     'status'    => true,
            //             //     'message'   => 'Project details retrieved successfully.',
            //             //     'userIds'      => $userIds,
            //             //     'designationIds'      => $designationIds,
            //             //     'designation_id'      => $designation_id,
            //             //     'task_targets'      => $task_targets,
            //             // ], 200);
            //             $project_users = EmpProject::whereIn('user_id', $userIds)
            //                                         ->where('project_id', $id)
            //                                         ->pluck('user_id');

            //             $activityIds    = $task_targets->pluck('activity_id');
            //             $activities     = Activity::whereIn('id', $activityIds)->get();
            //             $users          = User::whereIn('id', $project_users)->get();

            //             foreach ($task_targets as $task) {

            //                 $i = 0;
            //                 foreach ($activities as $activity) {

            //                     if ($activity['id'] == $task['activity_id']) {
            //                         $activities[$i]['daily'] = $task['daily'];
            //                         $activities[$i]['weekly'] = $task['weekly'];
            //                         $activities[$i]['monthly'] = $task['monthly'];
            //                     }
            //                     $i++;
            //                 }
            //             }


            //             $designation_targets[$j]['users']       = $users;
            //             $designation_targets[$j]['activities']  = $activities;
            //             $designation_targets[$j]['designation']  = $designation;

            //             $j++;

            //             array_push($designations, $designation_id);
            //         }
            //     }

            //     $project['task_and_target'] = $designation_targets ?? [];
            // }


            // $activities = [];
            // if (isset($project) && $project != null && ($project['department_name'] == 'hr' || $project['department_name'] == 'sales' || $project['department_name'] == 'marketing')) {

            //     $project_targets = ProjectTarget::where('project_id', $id)
            //         ->get();

            //     $designations = [];
            //     $designation_targets = [];

            //     $j = 0;
            //     foreach ($project_targets as $target) {

            //         $designation = Designation::find($target['designation_id']);

            //         if (!in_array($target['designation_id'], $designations)) {

            //             $task_targets = ProjectTarget::with('designation')
            //                 ->where('project_id', $project['id'])
            //                 ->where('designation_id', $target['designation_id'])
            //                 ->get();

            //             $userIds        = $task_targets->pluck('user_id')->unique();
            //             $activityIds    = $task_targets->pluck('activity_id');
            //             $activities     = Activity::whereIn('id', $activityIds)->get();
            //             $users          = User::whereIn('id', $userIds)->get();

            //             foreach ($task_targets as $task) {

            //                 $i = 0;
            //                 foreach ($activities as $activity) {

            //                     if ($activity['id'] == $task['activity_id']) {
            //                         $activities[$i]['daily'] = $task['daily'];
            //                         $activities[$i]['weekly'] = $task['weekly'];
            //                         $activities[$i]['monthly'] = $task['monthly'];
            //                     }
            //                     $i++;
            //                 }
            //             }


            //             $designation_targets[$j]['users']       = $users;
            //             $designation_targets[$j]['activities']  = $activities;
            //             $designation_targets[$j]['designation']  = $designation;

            //             $j++;

            //             array_push($designations, $target['designation_id']);
            //         }
            //     }

            //     $project['task_and_target'] = $designation_targets ?? [];
            // }

            return response()->json([
                'status'    => true,
                'message'   => 'Project details retrieved successfully.',
                'data'      => $project,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Project details not found.'
            ], 404);
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

    # Update the specified resource in storage.
    #
    # @param  \Illuminate\Http\Request  $request
    # @param  int  $id
    # @return \Illuminate\Http\Response
    public function update(ProjectRequest $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $project->name          =  trim($request->name);
            $project->client_id     =  $request->client_id ?? 1;
            $project->start_date    =  $request->start_date;
            $project->duration      =  $request->duration;

            $project->technologies()->sync($request->technologies);

            $project->user()->sync($request->resources);

            if ($project->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Project updated successfully.'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to update Project.'
                ], 500);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }


    # Remove the specified resource from storage.
    #
    # @param  int  $id
    # @return \Illuminate\Http\Response
    public function destroy($id)
    {
        try {

            $project = Project::findOrFail($id);

            if (isset($project) && $project != null & $project['status'] == 1) {

                /**
                 * We need to keep the record
                 */
                // $project->user()->detach();
                // $project->technologies()->detach();

                $project->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Project deleted successfully.'
                ], 200);
            } else {

                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Project not found.'
                ], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Project not found.',
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

    public function search(Request $request)
    {

        $orderBy            = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
        $orderType          = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";

        $perPage            = $request->per_page == "" ? 10 : $request->per_page;
        $searchKey          = $request->search_key ?? "";
        $currentPage        = $request->current_page == "" ? 1 : $request->current_page;;
        $department_id      = $request->department_id ?? "";
        $department_name    = $request->department_name ?? "";

        try {

            $projects = Project::with(
                'user',
                'client',
                'documents',
                'resources',
                'department',
                'technologies',
                'updatedByUser',
                'projectManager',
            );

            if ($searchKey != "") {
                $projects = $projects->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(trim($searchKey)) . '%']);
            }

            if ($department_id != "") {
                $projects = $projects->where('department_id', $department_id);
            }

            if ($department_name != "") {
                $projects = $projects->where('department_name', $department_name);
            }


            $projects = $projects->orderBy($orderBy, $orderType)
                ->paginate($perPage, ['*'], 'page', $currentPage)
                ->withQueryString();

            return response()->json([
                'status'    => true,
                'message'   => 'Project retrieved successfully.',
                'data'      => $projects ?? [],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function salesSearch(Request $request)
    {


        $orderBy            = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
        $orderType          = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";

        $perPage            = $request->per_page == "" ? 10 : $request->per_page;
        $searchKey          = $request->search_key ?? "";
        $currentPage        = $request->current_page == "" ? 1 : $request->current_page;;
        $department_id      = $request->department_id ?? "";
        $department_name    = $request->department_name ?? "";

        try {

            $projects = Project::with('user', 'updatedByUser')
                ->where('department_name', 'sales')
                ->orderBy($orderBy, $orderType)
                ->paginate($perPage, ['*'], 'page', $currentPage)
                ->withQueryString();

            $i = 0;
            foreach ($projects as $project) {


                $designation_ids = ProjectTarget::where('project_id', $project['id'])->pluck('designation_id')->unique();

                $designations = Designation::whereIn('id', $designation_ids)->get();

                $j = 0;
                foreach ($designations as $designation) {

                    $task_targets = ProjectTarget::with('activity')
                        ->where('project_id', $project['id'])
                        ->where('designation_id', $designation['id'])
                        ->get();

                    $user_ids = EmpDesignation::where('designation_id', $designation['id'])
                        ->pluck('user_id')
                        ->unique();

                    $assigned_users = EmpProject::with('user')->whereIn('user_id', $user_ids)
                        ->where('project_id', $project['id'])
                        ->get();


                    $designations[$j]['activities'] = $task_targets ?? [];
                    $designations[$j]['users'] = $assigned_users ?? [];
                    $j++;
                }

                $projects[$i]['designations'] = $designations ?? [];
                $i++;
            }


            return response()->json([
                'status'    => true,
                'message'   => 'Project retrieved successfully.',
                'data'      => $projects ?? [],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument($id)
    {
        try {

            $project_document = ProjectDocument::findOrFail($id);

            $project_document->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Project Document deleted successfully.'
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Project Document not found.'
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'exception' =>  $e->getMessage(),
                'message'   =>  'Something went wrong.'
            ], 500);
        }
    }

    public function getProjectByClient($id)
    {
        try {

            $projects = Project::where('client_id', $id)->get();

            return response()->json([
                'status' => true,
                'message' => "Client's forject list fetched.",
                'data' => $projects
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
