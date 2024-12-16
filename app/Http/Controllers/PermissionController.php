<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
// use App\Models\Permission;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Exception;

class PermissionController extends Controller
{
    /**
     * @OA\GET(
     *     path="/v1/permission/list",
     *     tags={"Permission"},
     *     summary="Permission list.",
     *     operationId="listPermission",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Permission list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Permission list fetched successfully.",
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
     *             description="Contains the object of Permission.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="activity_create"),
     *                      @OA\Property(property="guard_name", type="string", example="api"),
     *                      @OA\Property(property="created_at", type="string", example="2024-02-08T06:24:49.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-02-08T06:24:49.000000Z"),
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

            $permissions = Permission::all();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Permissions list fetched successfully.',
                'data'      =>  $permissions ?? []
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong',
                'exception' =>  $e->getMessage(),
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
     *     path="/v1/permission/create",
     *     tags={"Permission"},
     *     summary="Create New Permission.",
     *     operationId="createPermission",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Permission.",
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
     *             description="Status indicates that Permission created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Permission.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission created successfully."),
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
    public function store(PermissionRequest $request)
    {
        try {

            Permission::create([
                'menu'          =>  trim($request->menu) ?? "",
                'name'          =>  trim($request->name) ?? "",
                'guard_name'    =>  'api'
            ]);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Permission created successfully.',
            ], 201);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/permission/show/{id}",
     *     tags={"Permission"},
     *     summary="Find Permission Details",
     *     operationId="showUser",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Permission ID",
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
     *             description="Status indicating that the Permission was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Permission.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Permission.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Permission Name"),
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

            $permission = Permission::findOrFail($id);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Permission fetched successfully.',
                'data'      =>  $permission ?? []
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Permission not found.',
            ], 500);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        //
    }

    /**
     * @OA\PUT(
     *     path="/v1/permission/update/{id}",
     *     tags={"Permission"},
     *     summary="Update Permission Details.",
     *     operationId="updatePermission",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Permission ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Permission Details.",
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
     *             description="Status indicates that Permission updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Permission.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission updated successfully."),
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
    public function update(PermissionRequest $request, $id)
    {

        $permissionExist = Permission::where('name', trim($request->name))->where('id', '!=', $id)->get();
        if (isset($permissionExist) && sizeof($permissionExist) > 0) {
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

            $permission = Permission::findOrFail($id);

            $permission->update([
                'name'  =>  trim($request->name)
            ]);

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Permission updated successfully.',
            ], 201);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Permission not found.',
            ], 500);
        } catch (Exception $e) {

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong',
                'exception' =>  $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/v1/permission/delete/{id}",
     *     tags={"Permission"},
     *     summary="Delete Permission.",
     *     operationId="deletePermission",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Permission ID",
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
     *             description="Status indicating that the Permission was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Permission.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission deleted successfully."),
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

        return response()->json([
            'status'    =>  false,
            'message'   =>  "Soft delete is not added for permission table, So we have commented out the delete code for the time being.",
        ], 500);


        try {

            $permission = Permission::findOrFail($id);

            $permission->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Permission deleted successfully.'
            ], 201);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Permission not found.',
                'exception' =>  $e->getMessage()
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
     *     path="/v1/permission/search",
     *     tags={"Permission"},
     *     summary="Permission Search.",
     *     operationId="searchPermission",
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
     *         description="Permission Name",
     *         required=false,
     *         @OA\Schema(type="string", default=""),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Permission list fetched successfully.",
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
     *             description="Contains the object of Permission.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             ),
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="H.R."),
     *                      @OA\Property(property="guard_name", type="string", example="api"),
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

            $permissions = Permission::orderBy($orderBy, $orderType);

            if($name != "")
            {
                $permissions = $permissions->where('name', 'like', '%'.$name.'%');
            }

            $permissions = $permissions->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Permissions list fetched.',
                'data'      =>  $permissions ?? [],
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
