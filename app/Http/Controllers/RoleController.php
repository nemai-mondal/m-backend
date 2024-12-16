<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
// use App\Models\Permission;
// use App\Models\Role;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/role/list",
     *     tags={"Role"},
     *     summary="Role list.",
     *     operationId="listRole",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Role list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the role list fetched successfully.",
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
     *             description="Contains the object of role.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Admin"),
     *                      @OA\Property(property="description", type="string", example="This role will have access to all the menu options including all privileges."),
     *                      @OA\Property(property="guard_name", type="string", example="gr_role"),
     *                      @OA\Property(property="status", type="boolean", example="1"),
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

            $roles = Role::where('status', 1)->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Role list.',
                'data'      =>  new RoleCollection($roles) ?? []
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
     *     path="/v1/role/create",
     *     tags={"Role"},
     *     summary="Create New Role.",
     *     operationId="createrole",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Role.",
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
     *                 @OA\Property(
     *                     property="description",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="permissions",
     *                     type="array",
     *                     @OA\Items(type="integer"),
     *                     example={1, 2, 3}
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that role created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the role.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role created successfully."),
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
    public function store(RoleRequest $request)
    {
        try {

            $permissions = Permission::whereIn('id', $request->permissions)->get();

            $role = Role::create([
                'name'              =>  trim($request->name) ?? "",
                'description'       =>  trim($request->description) ?? "",
                'status'            =>  1,
            ]);
            
            if ($role) {
                
                $role->syncPermissions($permissions);

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Role created and permissions assigned successfully.'
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
     *     path="/v1/role/show/{id}",
     *     tags={"Role"},
     *     summary="Find Role Details",
     *     operationId="showUser",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
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
     *             description="Status indicating that the Role was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Role.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Role.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Admin"),
     *                      @OA\Property(property="description", type="string", example="This role will have access to all the menu options including all privileges."),
     *                      @OA\Property(property="guard_name", type="string", example="gr_role"),
     *                      @OA\Property(property="status", type="boolean", example="1"),
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

            $role = Role::find($id);

            if (isset($role) && $role != null && $role['status'] == 1) {

                return response()->json([
                    'status'    => true,
                    'message'   => 'Role details retrieved successfully.',
                    'data'      => new RoleResource($role) ?? []
                ], 200);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Role not found.'
                ], 404);
            }
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
     *     path="/v1/role/update/{id}",
     *     tags={"Role"},
     *     summary="Update Role Details.",
     *     operationId="updateRole",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Role Details.",
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
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="permissions",
     *                     type="array",
     *                     @OA\Items(type="integer"),
     *                     example={1, 2, 3}
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Role updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Role.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role updated successfully."),
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
    public function update(Request $request, $id)
    {
        $roleExist = Role::where('name', trim($request->name))->where('id', '!=', $id)->get();
        if (isset($roleExist) && sizeof($roleExist) > 0) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'    =>  [
                    'name'  =>  [
                        "The name has already been taken."
                    ]
                ]
            ], 422);
        }

        try {

            $role = Role::find($id);

            if (isset($role) && $role != null && $role['status'] == 1) {

                $role->name         = trim($request->name) ?? "";
                $role->description  = trim($request->description) ?? "";

                if ($role->save()) {

                    $permissions = Permission::whereIn('id', $request->permissions)->get();
                    $role->syncPermissions($permissions);

                    return response()->json([
                        'status'  => true,
                        'message' => 'Role updated successfully.'
                    ], 201);
                } else {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to update role.'
                    ], 500);
                }
            } else {

                return response()->json([
                    'status'  => false,
                    'message' => 'Role not found.'
                ], 400);
            }
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
     *     path="/v1/role/delete/{id}",
     *     tags={"Role"},
     *     summary="Delete Role.",
     *     operationId="deleteRole",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
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
     *             description="Status indicating that the Role was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Role.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role deleted successfully."),
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

            $role = Role::find($id);

            if (isset($role) && $role != null & $role['status'] == 1) {

                $role->delete();

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Role deleted successfully.'
                ], 201);
            } else {

                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Role not found.'
                ], 404);
            }
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
     *     path="/v1/role/search",
     *     tags={"Role"},
     *     summary="Role Search.",
     *     operationId="searchRole",
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
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Column Name",
     *         required=false,
     *         @OA\Schema(type="string", default="id"),
     *     ),
     *     @OA\Parameter(
     *         name="order_type",
     *         in="query",
     *         description="ASC / DESC",
     *         required=false,
     *         @OA\Schema(type="string", default="desc"),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Role Name",
     *         required=false,
     *         @OA\Schema(type="string", default=""),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Role list fetched successfully.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Show the message to the user after fetching the list.",
     *             @OA\Schema(
     *                 type="Integer",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Role.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="H.R."),
     *                      @OA\Property(property="description", type="string", example="This role will have access to all the menu options including all privileges."),
     *                      @OA\Property(property="guard_name", type="string", example="api"),
     *                      @OA\Property(property="status", type="boolean", example="1"),
     *                      @OA\Property(property="created_at", type="timestamp", example="2024-02-02 11:31:18"),
     *                      @OA\Property(property="updated_at", type="timestamp", example="2024-02-02 11:31:18"),
     *                  ),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(
     *              mediaType="string",   
     *              example="Unauthorized"
     *          ),
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
     *     ),
     * ),
     */
    public function search(Request $request)
    {

        $perPage        = $request->per_page == "" ? 10 : $request->per_page;
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;
        $orderBy        = $request->order_by == "" ? 'id' : $request->order_by;
        $orderType      = $request->order_type == "" ? 'desc' : $request->order_type;
        $name           = $request->name ?? "";

        try {

            $roles = Role::with('permissions')->orderBy($orderBy, $orderType);

            if($name != "")
            {
                $roles = $roles->where('name', 'like', '%'.$name.'%');
            }

            $roles = $roles->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return new RoleCollection($roles);

        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }
}
