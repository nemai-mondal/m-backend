<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolePermissionRequest;
use App\Models\RolePermission;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/role-permission/list",
     *     tags={"Role Permission"},
     *     summary="Role Permission list.",
     *     operationId="listRolePermission",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Role Permission list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Role Permission list fetched successfully.",
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
     *             description="Contains the object of Role Permission.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role Permission list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="role_id", type="integer", example=1),
     *                      @OA\Property(property="permission_id", type="integer", example=4),
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

            $rolePermissions = RolePermission::all();
    
            return response()->json([
                'status'    =>  true,
                'message'   =>  'Role Permission list fetched.',
                'data'      =>  $rolePermissions ?? []
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ]);
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
     *     path="/v1/role-permission/create",
     *     tags={"Role Permission"},
     *     summary="Create New Role Permission.",
     *     operationId="createRolePermission",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Role Permission.",
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
     *             description="Status indicates that Role Permission created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Role Permission.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role Permission created successfully."),
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
    public function store(RolePermissionRequest $request)
    {
        $role_id        = $request->role_id;
        $permission_ids = $request->permission_ids;

        try {

            foreach($permission_ids as $permission_id)
            {
                RolePermission::create([
                    'role_id'       =>  $role_id,
                    'permission_id' =>  $permission_id,
                ]);
            }

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Role Permission created successfully.',
            ]);

        } catch(Exception $e) {
            
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ]);
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
        try {

            $rolePermissions = RolePermission::with('permission', 'role')->where('role_id', $id)->get();
    
            return response()->json([
                'status'    =>  true,
                'message'   =>  'Role Permission list fetched.',
                'data'      =>  $rolePermissions ?? []
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ]);
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
        $role_id        = $id;
        $permission_ids = $request->permission_ids;

        RolePermission::where('role_id', $role_id)->delete();

        try {

            foreach($permission_ids as $permission_id)
            {
                RolePermission::create([
                    'role_id'       =>  $role_id,
                    'permission_id' =>  $permission_id,
                ]);
            }

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Role Permission updated successfully.',
            ]);

        } catch(Exception $e) {
            
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage(),
            ]);
        }
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
     *     path="/v1/role-permission/search",
     *     tags={"Role Permission"},
     *     summary="Role Permission Search.",
     *     operationId="searchRolePermission",
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
     *         description="Role Permission list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Role Permission list fetched successfully.",
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
     *             description="Contains the object of Role Permission.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role Permission list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="role_id", type="integer", example=1),
     *                      @OA\Property(property="permission_id", type="integer", example=4),
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
        $currentPage    = $request->current_page == "" ? 1 : $request->current_page;;

        try {

            $rolePermissions = RolePermission::paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Role Permissions list fetched.',
                'data'      =>  $rolePermissions ?? [],
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }
}
