<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Models\Activity;
// use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\DepartmentProject;
use App\Models\Designation;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;
use App\Models\EmpOrganization;
use App\Models\ProjectTarget;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/department/list",
     *     tags={"Department"},
     *     summary="Department list.",
     *     operationId="listDepartment",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Department list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Department list fetched successfully.",
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
     *             description="Contains the object of Department.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Department list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Department Name"),
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

            $departments = Department::with("designations", "activities")->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Department list.',
                'data'      =>  $departments ?? []
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
     *     path="/v1/department/create",
     *     tags={"Department"},
     *     summary="Create New Department.",
     *     operationId="createDepartment",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Department.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name"},
     *                 @OA\Property(
     *                     property="name",
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
     *             description="Status indicates that Department created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Department.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Department created successfully."),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {

        try {
            
            Department::create([
                'name'   => trim($request->name) ?? "",
                'status' => 1,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Department created successfully.'
            ], 201);

        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/department/show/{id}",
     *     tags={"Department"},
     *     summary="Find Department Details",
     *     operationId="showDepartment",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
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
     *             description="Status indicating that the Department was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Department.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Department.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Department Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Department Name"),
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

            $department = Department::with("designations", "activities")->findOrFail($id);

            return response()->json([
                'status'    => true,
                'message'   => 'Department details retrieved successfully.',
                'data'      => $department ?? []
            ], 200);

        } catch(ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Department not found.'
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

    /**
     * @OA\PUT(
     *     path="/v1/department/update/{id}",
     *     tags={"Department"},
     *     summary="Update Department Details.",
     *     operationId="updateDepartment",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Department Details.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Department updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Department.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Department updated successfully."),
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
    public function update(DepartmentRequest $request, $id)
    {
        try {

            $department = Department::findOrFail($id);

            $department->update([
                'name'      =>  trim($request->name) ?? ""
            ]);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Department updated successfully.'
            ], 201);

        } catch(ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Department not found.'
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
     * @OA\DELETE(
     *     path="/v1/department/delete/{id}",
     *     tags={"Department"},
     *     summary="Delete Department.",
     *     operationId="deleteDepartment",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
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
     *             description="Status indicating that the Department was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Department.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Department deleted successfully."),
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

            $department = Department::findOrFail($id);



            /**
             * Check if any department's activity is assigned or not to any project
             */
            $activities     = Activity::where('department_id', $id)
                                        ->get();

            if(sizeof($activities) > 0) {

                foreach($activities as $activity) {

                    $project_activity = ProjectTarget::where('activity_id', $activity['id'])
                                                    ->orWhere('department_id', $id)
                                                    ->get();

                    if(sizeof($project_activity) > 0) {

                        return response()->json([
                            'status'    =>  false,
                            'message'   =>  'A Project is assigned to this Department.'
                        ], 422);
                    }
                }
            }



            /**
             * Check if any project is assigned or not to this Department
             */
            $department_project = DepartmentProject::where('department_id', $id)
                                                ->get();

            if(sizeof($department_project) > 0) {

                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'A Project is assigned to this Department.'
                ], 422);
            }



            /**
             * Check if any designation belongging to this department is assigned or not to any user
             */
            $designations = Designation::where('department_id', $id)
                                        ->get();

            foreach($designations as $designation) {

                $emp_designations = EmpDesignation::where('designation_id', $designation['id'])
                                                    ->get();

                if(sizeof($emp_designations) > 0) {

                    return response()->json([
                        'status'    =>  false,
                        'message'   =>  'This Department has some Designations which is assigned to some Users.'
                    ], 422);
                }
            }



            $department->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Department deleted successfully.'
            ], 200);

        } catch(ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Department not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 500);
        }
    }
  
    /**
     * @OA\GET(
     *     path="/v1/department/search",
     *     tags={"Department"},
     *     summary="Find Department Details",
     *     operationId="searchDepartment",
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
     *             description="Status indicating that the Department found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Department.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Department.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Department Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Development"),
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
        $searchKey      = $request->search_key ?? "";
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;;
        $orderBy        = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
        $orderType      = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";
        
        try {

            $departments = Department::with("designations", "activities")->select('id', 'name', 'created_at', 'updated_at')
                            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(trim($searchKey)) . '%'])
                            ->orderBy($orderBy, $orderType)
                            ->paginate($perPage, ['*'], 'page', $currentPage)
                            ->withQueryString();

                            return response()->json([
                                'status' => true,
                                'message' => 'Department retrieved successfully.',
                                'data' => $departments ?? [],
                            ], 200); 
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    public function getDepartmentId(Request $request) {

        if($request->name == "hr") {

            $department = Department::whereIn("name", [
                "HR",
                "H.R.",
                "HR.",
                "Human Resource",
                "Human Resources",
            ])->first();
    
            return response()->json([
                'status'    =>  true,
                'message'   =>  "Matching Department details found",
                'data'      =>  $department ?? []
            ], 200);
        }

        if($request->name == "sales") {

            $department = Department::whereIn("name", [
                "sales",
                "sale",
            ])->first();
    
            return response()->json([
                'status'    =>  true,
                'message'   =>  "Matching Department details found",
                'data'      =>  $department ?? []
            ], 200);
        }

        if($request->name == "marketing") {

            $department = Department::whereIn("name", [
                "marketings",
                "marketing",
                "market",
            ])->first();

            return response()->json([
                'status'    =>  true,
                'message'   =>  "Matching Department details found",
                'data'      =>  $department ?? []
            ], 200);
        }

        return response()->json([
            'status'    =>  false,
            'message'   =>  "Department not found.",
        ], 404);
    }

    public function getUsersByDepartment($id)
    {
        try {
            
            $userIds = EmpDepartment::where('department_id', $id)->pluck('user_id');
            // $userIds = EmpOrganization::where('department_id', $id)->pluck('user_id');

            $users = User::whereIn('id', $userIds)->get();

            return response()->json([
                'status' => true,
                'message' => 'Users belonging to the specified department.',
                'data' => $users ?? []
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
