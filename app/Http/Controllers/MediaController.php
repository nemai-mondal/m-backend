<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Http\Resources\AmendmentCollection;
use App\Http\Resources\MediaCollection;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use App\Models\Amendment;
use App\Models\MotivationalQuote;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{

    
    /**
     * @OA\GET(
     *     path="/v1/amendment/list",
     *     tags={"Amendment"},
     *     summary="Amendment list.",
     *     operationId="listAmendment",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Amendment list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the Amendment list fetched successfully.",
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
     *             description="Contains the object of Amendment.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Amendment list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Sample"),
     *                      @OA\Property(property="document", type="String", example="http://localhost/storage/media/40/parentreceived.pdf"),
     *                      @OA\Property(property="description", type="string", example="test"),
     *                      @OA\Property(property="added_by_id", type="string", example="MMT"),
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

            $amendment = Amendment::orderBy('id', 'desc')->get();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Amendment fetched.',
                'data'      =>  new MediaCollection($amendment) ?? []
            ], 200);
        } catch (Exception $e) {

            return response()->json([
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
    }
    
    /**
     * @OA\POST(
     *     path="/v1/amendment/create",
     *     tags={"Amendment"},
     *     summary="Create New Amendment.",
     *     operationId="createAmendment",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New Amendment.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name","file"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="String"
     *                 ),
     *                  @OA\Property(
     *                     property="file",
     *                     type="file"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Amendment created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the Amendment.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Amendment created successfully."),
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
    public function store(MediaRequest $request)
    {
        $name                           =   $request->name ?? "";
        $status                         =   $request->status ?? 0;
        $description                    =   $request->description ?? "";
        $added_by_id                    =   auth()->user()->id;
        $department_id                  =   $request->department_id ?? null;
        $employment_type_id             =   $request->employment_type_id ?? null;

        try {

            $amendment = Amendment::create([
                'name'                          =>  $name,
                'status'                        =>  $status,
                'description'                   =>  $description,
                'added_by_id'                   =>  $added_by_id,
                // 'department_id'                 =>  $department_id,
                // 'employment_type_id'            =>  $employment_type_id,
            ]);

            if ($request->hasFile("file")) {
                $amendment->clearMediaCollection('amendment');

                $amendment->addMediaFromRequest('file')
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('amendment');
            }



            if ($amendment) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Amendment added successfully.',
                ], 201);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.'
            ], 400);
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
     *     path="/v1/amendment/show/{id}",
     *     tags={"Amendment"},
     *     summary="Find Amendment Details",
     *     operationId="showAmendment",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Amendment ID",
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
     *             description="Status indicating that the Amendment found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the Amendment.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of Amendment.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Amendment Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Sample"),
     *                      @OA\Property(property="document", type="String", example="http://localhost/storage/media/40/parentreceived.pdf"),
     *                      @OA\Property(property="description", type="string", example="test"),
     *                      @OA\Property(property="added_by_id", type="string", example="MMT"),
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

            $amendment = Amendment::findOrFail($id);

            if (isset($amendment) && $amendment != null) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Amendment fetched.',
                    'data'      =>  new MediaResource($amendment) ?? []
                ], 200);
            }
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Amendment not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
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
     * @OA\PUT(
     *     path="/v1/amendment/update/{id}",
     *     tags={"Amendment"},
     *     summary="Update Amendment Details.",
     *     operationId="updateAmendment",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Amendment ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update Amendment Details.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"name", "file"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     type="file"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that Amendment updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the Amendment.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Amendment updated successfully."),
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
     * 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MediaRequest $request, $id)
    {
        try {
            $amendment = Amendment::findOrFail($id);

            DB::beginTransaction();

            $amendment->update($request->only('name', 'description', 'added_by_id'));

            if ($request->hasFile("file")) {
                $amendment->clearMediaCollection('amendment');

                $amendment->addMediaFromRequest('file')
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('amendment');
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Amendment Updated Successfully.',
                'i' => 1
            ], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Amendment not found.',
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/v1/amendment/delete/{id}",
     *     tags={"Amendment"},
     *     summary="Delete Amendment.",
     *     operationId="deleteAmendment",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Amendment ID",
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
     *             description="Status indicating that the Amendment was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the Amendment.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Amendment deleted successfully."),
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $amendment = Amendment::findOrFail($id);

            $amendment->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Amendment deleted successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Amendment not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $orderBy            = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
            $perPage            = $request->input("per_page", 10);
            $orderType          = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";
            $searchKey          = $request->search_key ?? ""; 
            $currentPage        = $request->input("current_page", 1);

            $amendments = Amendment::whereNotNull("name");

            if (!empty($searchKey)) {
                $amendments->where(function ($query) use ($searchKey) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(trim($searchKey)) . '%']);
                });
            }

            $amendments = $amendments->orderBy($orderBy, $orderType)
                ->paginate($perPage, ['*'], 'page', $currentPage)
                ->withQueryString();

            return new MediaCollection($amendments);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong.",
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function multiDelete(Request $request)
    {
        try {
            $ids = $request->amendment_ids ?? [];
            $deletedIds = [];
            $notFoundIds = [];
            $not_found_id = '';

            foreach ($ids as $id) {
                $amendment = Amendment::find($id);

                if ($amendment) {
                    $amendment->delete();
                    $deletedIds[] = $id;
                } else {
                    $notFoundIds[] = $id; 
                }
            }

            $message = 'Amendments deleted successfully ' . implode(', ', $deletedIds);
            if (!empty($notFoundIds)) {
                $not_found_id= ' Amendments with IDs ' . implode(', ', $notFoundIds) . ' not found.';
            }

            return response()->json([
                'status'            =>  true,
                'message'           =>  $message,
                'not_found_id'      =>  $not_found_id,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }       
    }

    public function publishAmendment(Request $request, $id)
    {
        try {
            $amendment = Amendment::findOrFail($id);
            $status = $request->status ?? 1;
    
            $amendment->update([
                'status' => $status,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Amendment status updated successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function publishAmendmentList()
    {
        try { 
            $amendments = Amendment::where('status', 1)->orderBy('id', 'desc')->get();
    
            return response()->json([
                'status' => true,
                'message' => 'Publish Amendments fetched Successfully.',
                'data' => new MediaCollection($amendments) ?? []
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
