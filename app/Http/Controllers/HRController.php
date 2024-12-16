<?php

namespace App\Http\Controllers;

use App\Http\Requests\HRRequest;
use App\Http\Resources\HRCollection;
use App\Http\Resources\HRResource;
use App\Models\HR;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HRController extends Controller
{

    /**
     * @OA\GET(
     *     path="/v1/hr-announcement/list",
     *     tags={"HR"},
     *     summary="HR Announcement list.",
     *     operationId="listHR",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="HR Announcement list fetched.",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that the HR Announcement list fetched successfully.",
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
     *             description="Contains the object of HR Announcement.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="HR Announcement list fetched successfully"),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="title", type="string", example="Project Display."),
     *                      @OA\Property(property="description", type="string", example="iOS developer team will display our new project and give you an overview of it's use."),
     *                      @OA\Property(property="created_by_id", type="integer", example=3),
     *                      @OA\Property(property="created_by_name", type="string", example="HR Name"),
     *                      @OA\Property(property="department_id", type="integer", example=7),
     *                      @OA\Property(property="department_name", type="string", example="HR"),
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
    
    public function index(Request $request)
    {
        $perPage            = isset($request->per_page) && $request->per_page != "" ? (int)$request->per_page : 10;
        $currentPage        = isset($request->current_page) && $request->current_page != "" ? (int)$request->current_page : 1;
        $orderBy            = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
        $orderType          = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";
        
        try {

    
            $announcements  = HR::select('id', 'title', 'description', 'user_id', 'department_id', 'event_date', 'event_start_time', 'event_end_time', 'created_at', 'updated_at')
                                    ->orderBy($orderBy, $orderType)
                                    ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

                                        
            return response()->json([
                'status'    => true,
                'message'   => 'HR Announcements fetched.',
                'data'      => new HRCollection($announcements) ?? []
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage()
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
     *     path="/v1/hr-announcement/create",
     *     tags={"HR"},
     *     summary="Create New HR Announcement.",
     *     operationId="createQuote",
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         description="Create New HR Announcement.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"title", "description", "event_time"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="String",
     *                     default="Republic day celebration"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="String",
     *                     default="Republic day celebration on 25 January 2024 at 17:30 PM"
     *                 ),
     *                 @OA\Property(
     *                     property="event_time",
     *                     type="DateTime",
     *                     default="2024-01-25 17:30:00"
     *                 ),
     *                 @OA\Property(
     *                     property="department_id",
     *                     type="Integer",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\Header(
     *             header="status",
     *             description="Status indicates that HR Announcement created successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after creating the HR Announcement.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="HR Announcement created successfully."),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HRRequest $request)
    {
        $title              = $request->title ?? "";
        $description        = $request->description ?? "";
        $department_id      = $request->department_id ?? 0;
        // $designation_id     = $request->designation_id ?? 0;
        $event_date         = $request->event_date ?? null;
        $event_start_time   = $request->event_start_time ?? "";
        $event_end_time     = $request->event_end_time ?? "";
        // dd($request)->all();
        try {
            $announcement = HR::create([
                'title'             => $title,
                'description'       => $description,
                'user_id'           => auth()->user()->id,
                'department_id'     => (int)$department_id,
                // 'designation_id'    => (int)$designation_id,
                'event_date'        => $event_date,
                'event_start_time'  => $event_start_time,
                'event_end_time'    => $event_end_time,
                'status'            => 1,
            ]);

            if ($announcement) {
                return response()->json([
                    'status'  => true,
                    'message' => 'HR Announcement added successfully.',
                ], 201);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
            ], 400);

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
     *     path="/v1/hr-announcement/show/{id}",
     *     tags={"HR"},
     *     summary="Find HR Announcement Details",
     *     operationId="showHR",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="HR Announcement ID",
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
     *             description="Status indicating that the HR Announcement was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the HR Announcement.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of HR Announcement.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="HR Announcement Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="title", type="string", example="Project Display."),
     *                      @OA\Property(property="description", type="string", example="iOS developer team will display our new project and give you an overview of it's use."),
     *                      @OA\Property(property="created_by_id", type="integer", example=3),
     *                      @OA\Property(property="created_by_name", type="string", example="HR Name"),
     *                      @OA\Property(property="department_id", type="integer", example=7),
     *                      @OA\Property(property="department_name", type="string", example="HR"),
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
     *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $announcement = HR::findOrFail($id);
    
            if(isset($announcement) && $announcement != null) {

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'Motivation Quotes fetched.',
                    'data'      =>  new HRResource($announcement) ?? []
                ], 200);
            }
        }
        catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Announce not found.',
            ], 404);
        }
        catch (Exception $e) {
            
            return response()->json([
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
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
     *     path="/v1/hr-announcement/update/{id}",
     *     tags={"HR"},
     *     summary="Update HR Announcement Details.",
     *     operationId="updateHR",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="HR Announcement ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Create New HR Announcement.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                  required={"title", "description", "user_id", "department_id"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="String"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="Integer"
     *                 ),
     *                 @OA\Property(
     *                     property="department_id",
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
     *             description="Status indicates that HR Announcement updated successfully.",
     *             @OA\Schema(
     *                 type="Boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after updating the HR Announcement.",
     *             @OA\Schema(
     *                 type="String",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="HR Announcement updated successfully."),
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HRRequest $request, $id)
    {

        $title              = $request->title ?? "";
        $description        = $request->description ?? "";
        $department_id      = $request->department_id ?? 0;
        // $designation_id     = $request->designation_id ?? 0;
        $event_date         = $request->event_date ?? null;
        $event_start_time   = $request->event_start_time ?? "";
        $event_end_time     = $request->event_end_time ?? "";

        try {

            $quote = HR::findOrFail($id);
    
            if(isset($quote) && $quote != null) {

                $announcement = HR::where('id', $id)->update([
                    'title'             =>  $title,
                    'description'       =>  $description,
                    'user_id'           =>  auth()->user()->id,
                    'department_id'     => (int)$department_id,
                    'event_date'        => $event_date,
                    'event_start_time'  => $event_start_time,
                    'event_end_time'    => $event_end_time,
                ]);

                return response()->json([
                    'status'    =>  true,
                    'message'   =>  'HR Announcement updated successfully.',
                    // 'data'      =>  new HRResource($quote) ?? []
                ], 201);
            }

        }
        catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Announce not found.',
            ], 404);
        }
        catch (Exception $e) {
            
            return response()->json([
                'message'   =>  'Something went wrong.',
                'exception' =>  $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/v1/hr-announcement/delete/{id}",
     *     tags={"HR"},
     *     summary="Delete HR Announcement.",
     *     operationId="deleteHR",
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="HR Announcement ID",
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
     *             description="Status indicating that the HR Announcement was deleted successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after deleting the HR Announcement.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="HR Announcement deleted successfully."),
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

            $announcement = HR::findOrFail($id);
    
            $announcement->delete();
    
            return response()->json([
                'status'  => true,
                'message' => 'HR Announcement deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Announce not found.',
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\GET(
     *     path="/v1/hr-announcement/search",
     *     tags={"HR"},
     *     summary="Find HR Details",
     *     operationId="searchHR",
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
     *             description="Status indicating that the HR found successfully.",
     *             @OA\Schema(
     *                 type="boolean",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="message",
     *             description="Message to show the user after fetching the HR.",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\Header(
     *             header="data",
     *             description="Contains the object of HR.",
     *             @OA\Schema(
     *                 type="Object",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="HR Details Found."),
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="title", type="string", example="Quiz Game"),
     *                      @OA\Property(property="description", type="string", example="Get ready to challenge your self."),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="department_id", type="integer", example=1),
     *                      @OA\Property(property="event_date", type="date", example="2024-04-23"),
     *                      @OA\Property(property="event_start_time", type="time", example="11:00:00"),
     *                      @OA\Property(property="event_end_time", type="time", example="12:00:00"),
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
        $perPage        = isset($request->per_page) && $request->per_page != "" ? (int)$request->per_page : 10;
        $currentPage    = isset($request->current_page) && $request->current_page != "" ? (int)$request->current_page : 1;
        $orderBy        = isset($request->order_by) && $request->order_by != "" ? $request->order_by : "id";
        $orderType      = isset($request->order_type) && $request->order_type != "" ? $request->order_type : "desc";

        try {

            $hr_announcements = HR::select('id', 'title', 'description', 'user_id', 'department_id', 'event_date', 'event_start_time', 'event_end_time', 'created_at', 'updated_at')
                                    ->with('user', 'department')
                                    ->orderBy($orderBy, $orderType)
                                    ->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'HR Announcements list.',
                'data'      =>  $hr_announcements ?? [],
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
